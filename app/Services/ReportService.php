<?php

namespace App\Services;

use App\Helpers\PriceHelper;
use App\Models\Event;
use App\Models\Order;
use App\Models\PaymentMethod;
use App\Models\Role;
use App\Models\Shipping;
use App\Models\ShippingZone;
use App\Models\Ticket;
use App\Models\User;
use App\Modules\Admin\Requests\ByBookkepperReportRequest;
use App\Modules\Admin\Requests\ByPartnersReportRequest;
use App\Modules\Admin\Requests\EventReportRequest;
use App\Modules\Admin\Requests\OverallReportRequest;
use App\Modules\Admin\Requests\PartnerReportRequest;
use Carbon\Carbon;
use DB;
use Auth;


class ReportService
{
    public function getEventsByDatesRange($start, $end, $eventNames)
    {
        $q = DB::table('events');

        if ($start && $end) $q->whereBetween('date', [$start, $end]);
        if ($eventNames) {
            $q->where(function($q) use($eventNames) {
                foreach ($eventNames as $name) {
                    $q->orWhere('name', 'like', "%$name%");
                }
            });
        }

        $ids = $q->pluck('id');

        return $this->_getComposedEventNames($ids, true);
    }

    public function getByPartnerData(ByPartnersReportRequest $request)
    {
        $eventNames = $request->get('event_name', []);
        $eventPeriodStart = $request->get('event_period_start');
        $eventPeriodEnd = $request->get('event_period_end');
        $eventIds = $request->get('event_ids', []);
        $salePeriodStart = $request->get('sale_period_start');
        $salePeriodEnd = $request->get('sale_period_end');
        $withPartners = $request->get('with', []);
        $withoutPartners = $request->get('without', []);
        $orderStatuses = $request->get('order_statuses', []);
        $groupBy = $request->get('group_by', 'event_name');

        $query = DB
            ::table('orders')
            ->whereIn('manager_id', self::getPartnersOptions(true, true))
        ;

        if ($eventIds || $eventNames || $eventPeriodStart && $eventPeriodEnd)
            $query->whereExists(function ($q) use ($eventIds, $eventNames, $eventPeriodStart, $eventPeriodEnd) {
                $q
                    ->select(DB::raw(1))
                    ->from('tickets')
                    ->leftJoin('events', 'events.id', '=', 'tickets.event_id')
                    ->whereRaw('tickets.order_id = orders.id')
                ;

                if ($eventIds) {
                    $q->whereIn('event_id', $eventIds);
                } else if ($eventNames) {
                    $q->where(function ($q) use($eventNames) {
                        foreach ($eventNames as $name) {
                            $q->orWhere('events.name', 'like', "%$name%");
                        }
                    });
                }

                if ($eventPeriodStart && $eventPeriodEnd) {
                    $q->whereBetween('events.date', ["$eventPeriodStart 00:00:00", "$eventPeriodEnd 23:59:59"]);
                }
            });

        if ($salePeriodStart && $salePeriodEnd) $query->whereBetween('created_at', [
            "$salePeriodStart 00:00:00",
            "$salePeriodEnd 23:59:59",
        ]);

        if ($withPartners) $query->whereIn('manager_id', $withPartners);
        if ($withoutPartners) $query->whereNotIn('manager_id', $withoutPartners);

        if ($orderStatuses) $query->whereIn('status', $orderStatuses);

        $orderIds = $query->pluck('id');

        $dataQuery = DB
            ::table('tickets')
            ->leftJoin('orders', 'orders.id', '=', 'tickets.order_id')
            ->select([
                DB::raw('(select concat(coalesce(u.first_name, ""), " ", coalesce(u.last_name, "")) from users u where u.id = orders.manager_id) as manager_name'),
                DB::raw('count(tickets.id) as count'),
                DB::raw('sum(tickets.discount) as discount'),
                'manager_id',
                'event_id',
                'price_id',
                'price_group_id',
                'orders.status as status',
            ])
            ->whereIn('order_id', $orderIds)
            ->groupBy([
                'manager_name',
                'manager_id',
                'event_id',
                'price_id',
                'price_group_id',
                'status',
            ])
        ;

        if ($eventIds) $dataQuery->whereIn('event_id', $eventIds);

        $secondaryGrouping = $this->_getSecondaryGrouping($groupBy);
        $data = $dataQuery->get();
        $eventIds = $data->pluck('event_id');

        $eventNames = $this->_getComposedEventNames($eventIds);

        foreach ($data as $row) {
            $row->event_name = $eventNames[$row->event_id];
            $row->price = PriceHelper::getPriceWithGroup($row->price_id, $row->price_group_id);
        }

        $data = $data->groupBy($groupBy);
        foreach ($data as $key => &$sub) {
            $data[$key] = $sub->groupBy($secondaryGrouping);
        }

        return $data;
    }

    public function displayByPartnerData(ByPartnersReportRequest $request)
    {
        $data = $this->getByPartnerData($request);
        $totals = $this->_getByPartnersTotals($data);
        $tickets = $this->_getByPartnersTicketsCalculation($data);
        $salePeriodStart = $request->get('sale_period_start');
        $salePeriodEnd = $request->get('sale_period_end');
        $eventPeriodStart = $request->get('event_period_start');
        $eventPeriodEnd = $request->get('event_period_end');

        return view('Admin::report.by_partner_data', [
            'data' => $data,
            'totals' => $totals,
            'request' => $request,
            'tickets' => $tickets,
            'salePeriodStart' => $salePeriodStart,
            'salePeriodEnd' => $salePeriodEnd,
            'eventPeriodStart' => $eventPeriodStart,
            'eventPeriodEnd' => $eventPeriodEnd,
            'displayStatuses' => self::getOrderStatusesOptions(),
        ]);
    }

    public function exportByPartnerData(ByPartnersReportRequest $request)
    {
        $data = $this->getByPartnerData($request);
        $totals = $this->_getByPartnersTotals($data);
        $tickets = $this->_getByPartnersTicketsCalculation($data);
        $salePeriodStart = $request->get('sale_period_start');
        $salePeriodEnd = $request->get('sale_period_end');
        $eventPeriodStart = $request->get('event_period_start');
        $eventPeriodEnd = $request->get('event_period_end');

        return view('Admin::report.by_partner_export', [
            'data' => $data,
            'totals' => $totals,
            'request' => $request,
            'tickets' => $tickets,
            'salePeriodStart' => $salePeriodStart,
            'salePeriodEnd' => $salePeriodEnd,
            'eventPeriodStart' => $eventPeriodStart,
            'eventPeriodEnd' => $eventPeriodEnd,
            'displayStatuses' => self::getOrderStatusesOptions(),
        ]);
    }

    public function getByBookkeeperData(ByBookkepperReportRequest $request, $returnTickets = false)
    {
        $eventPeriodStart = $request->get('event_period_start');
        $eventPeriodEnd = $request->get('event_period_end');
        $eventIds = $request->get('event_ids', []);
        $salePeriodStart = $request->get('sale_period_start');
        $salePeriodEnd = $request->get('sale_period_end');
        $bookkeepers = $request->get('bookkeeper', []);
        $authors = $request->get('author', []);
        $paymentOptions = $request->get('payment_options', []);
        $paidWith = $request->get('paid_with', []);
        $ticketStatuses = $request->get('ticket_statuses', []);
        $groupBy = $request->get('group_by', 'event_name');

        $query = DB::table('orders')->whereIn('manager_id', array_keys(self::getBookkeeperOptions()->toArray()));

        if ($eventIds || $eventPeriodStart && $eventPeriodEnd)
            $query->whereExists(function ($q) use ($eventIds, $eventPeriodStart, $eventPeriodEnd) {
                $q
                    ->select(DB::raw(1))
                    ->from('tickets')
                    ->whereRaw('tickets.order_id = orders.id')
                ;

                if ($eventIds) {
                    $q->whereIn('event_id', $eventIds);
                }

                if ($eventPeriodStart && $eventPeriodEnd) {
                    $q->whereBetween('date', ["$eventPeriodStart 00:00:00", "$eventPeriodEnd 23:59:59"]);
                }
            });

        if ($salePeriodStart && $salePeriodEnd) $query->whereBetween('created_at', [
            "$salePeriodStart 00:00:00",
            "$salePeriodEnd 23:59:59",
        ]);

        if ($bookkeepers) $query->whereIn('manager_id', $bookkeepers);
        if ($authors) $query->whereIn('manager_id', $authors);

        if ($paymentOptions) $query->whereIn('payment_method_id', $paymentOptions);

        if ($paidWith === 'paid_cash') {
            $query
                ->whereNull('payment_method_id')
                ->where('paid_cash', '>', 0)
            ;
        }

        $orderIds = $query->pluck('id');

        if ($returnTickets) {
            $dataQuery = DB
                ::table('tickets')
                ->leftJoin('orders', 'orders.id', '=', 'tickets.order_id')
                ->select([
                    DB::raw('(select concat(coalesce(u.first_name, ""), " ", coalesce(u.last_name, "")) from users u where u.id = orders.manager_id) as manager_name'),
                    'event_id',
                    'barcode',
                    'tickets.status as status',
                    'price_id',
                    'price_group_id',
                    'order_id',
                    'tickets.discount as discount',
                ])
                ->whereIn('order_id', $orderIds)
            ;

            if ($eventIds) $dataQuery->whereIn('event_id', $eventIds);
            if ($ticketStatuses) $dataQuery->whereIn('status', $ticketStatuses);

            $data = $dataQuery->get();

            $eventNames = $this->_getComposedEventNames($data->pluck('event_id'));
            $displayStatuses = self::getTicketStatusesOptions();

            $result = collect();
            foreach ($data as $row) {
                $result->push([
                    __('Event') => $eventNames[$row->event_id],
                    __('Bookkeeper') => $row->manager_name,
                    __('Barcode') => $row->barcode,
                    __('Status') => $displayStatuses[$row->status],
                    __('Price') => PriceHelper::getPriceWithGroup($row->price_id, $row->price_group_id),
                    __('Order') => $row->order_id,
                    __('Amount') => 1,
                    __('Discount') => $row->discount,
                    __('Total') => PriceHelper::getPriceWithGroup($row->price_id, $row->price_group_id),
                ]);
            }

            return $result;
        }

        $dataQuery = DB
            ::table('tickets')
            ->leftJoin('orders', 'orders.id', '=', 'tickets.order_id')
            ->select([
                DB::raw('(select concat(coalesce(u.first_name, ""), " ", coalesce(u.last_name, "")) from users u where u.id = orders.manager_id) as manager_name'),
                DB::raw('count(tickets.id) as count'),
                DB::raw('sum(tickets.discount) as discount'),
                'manager_id',
                'event_id',
                'price_id',
                'price_group_id',
                'tickets.status as status',
            ])
            ->whereIn('order_id', $orderIds)
            ->groupBy([
                'manager_name',
                'manager_id',
                'event_id',
                'price_id',
                'price_group_id',
                'status',
            ])
        ;

        if ($eventIds) $dataQuery->whereIn('event_id', $eventIds);
        if ($ticketStatuses) $dataQuery->whereIn('status', $ticketStatuses);

        $secondaryGrouping = $this->_getSecondaryGrouping($groupBy);
        $data = $dataQuery->get();
        $eventIds = $data->pluck('event_id');

        $eventNames = $this->_getComposedEventNames($eventIds);

        foreach ($data as $row) {
            $row->event_name = $eventNames[$row->event_id];
            $row->price = PriceHelper::getPriceWithGroup($row->price_id, $row->price_group_id);
        }

        $data = $data->groupBy($groupBy);
        foreach ($data as $key => &$sub) {
            $data[$key] = $sub->groupBy($secondaryGrouping);
        }

        return $data;
    }

    public function displayByBookkeeperData(ByBookkepperReportRequest $request)
    {
        $data = $this->getByBookkeeperData($request);
        $totals = $this->_getByBookkeepersTotals($data);

        return view('Admin::report.by_bookkeeper_data', [
            'data' => $data,
            'totals' => $totals,
            'displayStatuses' => self::getTicketStatusesOptions(),
        ]);
    }

    public function exportByBookkeeperData(ByBookkepperReportRequest $request)
    {
        $data = $this->getByBookkeeperData($request);
        $totals = $this->_getByBookkeepersTotals($data);
        $salePeriodStart = $request->get('sale_period_start');
        $salePeriodEnd = $request->get('sale_period_end');

        return view('Admin::report.by_bookkeeper_export', [
            'data' => $data,
            'totals' => $totals,
            'salePeriodStart' => $salePeriodStart,
            'salePeriodEnd' => $salePeriodEnd,
            'displayStatuses' => self::getTicketStatusesOptions(),
        ]);
    }

    public function getOverallData(OverallReportRequest $request)
    {
        $eventNames = $request->get('event_name', []);
        $eventPeriodStart = $request->get('event_period_start');
        $eventPeriodEnd = $request->get('event_period_end');
        $eventIds = $request->get('event_ids', []);
        $salePeriodStart = $request->get('sale_period_start');
        $salePeriodEnd = $request->get('sale_period_end');
        $withPartners = $request->get('with', []);
        $withoutPartners = $request->get('without', []);
        $sortBy = $request->get('sort_by');
        $shippingMethod = $request->get('shipping_method');
        $paymentMethod = $request->get('payment_method');
        $isActive = $request->get('active');

        $q = DB::table('events');

        if ($eventIds) {
            $q->whereIn('id', $eventIds);
        } else if ($eventNames) {
            $q->where(function ($q) use($eventNames) {
                foreach ($eventNames as $name) {
                    $q->orWhere('name', 'like', "%$name%");
                }
            });
        }

        if ($eventPeriodStart && $eventPeriodEnd) {
            $q->whereBetween('date', ["$eventPeriodStart 00:00:00", "$eventPeriodEnd 23:59:59"]);
        }

        if (
            $salePeriodStart && $salePeriodEnd ||
            $withPartners ||
            $withoutPartners ||
            $shippingMethod ||
            $paymentMethod ||
            $isActive
        ) {
            $q->whereExists(function ($q) use(
                $salePeriodStart,
                $salePeriodEnd,
                $withPartners,
                $withoutPartners,
                $isActive,
                $shippingMethod,
                $paymentMethod
            ) {
                $q
                    ->select('orders.id')
                    ->from('orders')
                    ->leftJoin('tickets', 'tickets.order_id', '=', 'orders.id')
                    ->whereRaw('tickets.event_id = events.id')
                ;

                if ($salePeriodStart && $salePeriodEnd) {
                    $q->whereBetween('orders.created_at', [
                        "$salePeriodStart 00:00:00",
                        "$salePeriodEnd 23:59:59",
                    ]);
                }

                if ($withPartners) $q->whereIn('manager_id', $withPartners);
                if ($withoutPartners) $q->whereNotIn('manager_id', $withoutPartners);
                if ($isActive) $q->where('is_active', 1);

                if ($shippingMethod) {
                    switch ($shippingMethod) {
                        case 'e_ticket':
                            $q->whereNull('orders.shipping_zone_id')->where('orders.shipping_type', 0);
                            break;
                        case 't_office':
                            $q->whereNull('orders.shipping_zone_id')->where('orders.shipping_type', 1);
                            break;
                        default:
                            $zoneIds = ShippingZone::whereShippingId($shippingMethod)->pluck('id');
                            $q->whereIn('orders.shipping_zone_id', $zoneIds);
                    }
                }

                if ($paymentMethod) $q->where('orders.payment_method_id', $paymentMethod);
                
            });
        }

        $eventIds = $q->pluck('id');
        $eventNames = $this->_getComposedEventNames($eventIds);
        $ticketStatistics = $this->_getEventsSaleStatistics(
            $eventIds,
            compact(
                'salePeriodStart',
                'salePeriodEnd',
                'withPartners',
                'withoutPartners',
                'shippingMethod',
                'paymentMethod'
            ),
            [
                Order::STATUS_REALIZATION,
                Order::STATUS_CONFIRMED,
            ]
        );

        $result = [];

        foreach ($eventIds as $id) {
            $row = [
                'event_id' => $id,
                'event_name' => $eventNames[$id],
                'realization_count' => 0,
                'realization_price' => 0,
                'sold_count' => 0,
                'sold_price' => 0,
            ];

            if (isset($ticketStatistics[$id])) {
                foreach ($ticketStatistics[$id] as $stats) {
                    switch ($stats->status) {
                        case Order::STATUS_CONFIRMED:
                            $row['sold_count'] += $stats->count;
                            $row['sold_price'] +=
                                PriceHelper::getPriceWithGroup($stats->price_id, $stats->price_group_id) *
                                $stats->count
                            ;

                            break;
                        case Order::STATUS_REALIZATION:
                            $row['realization_count'] += $stats->count;
                            $row['realization_price'] +=
                                PriceHelper::getPriceWithGroup($stats->price_id, $stats->price_group_id) *
                                $stats->count
                            ;

                            break;
                    }
                }
            }

            $result[] = $row;
        }

        return collect($result)->sort(function ($r) use ($sortBy) {
            switch ($sortBy) {
                case 'sold_count': return $r['sold_count'];
                case 'all_transactions_count': return $r['sold_count'] + $r['realization_count'];
                default: return $r['event_id'];
            }
        });
    }

    public function displayOverallData(OverallReportRequest $request)
    {
        $data = $this->getOverallData($request);

        return view('Admin::report.overall_data', [
            'data' => $data,
        ]);
    }

    public function exportOverallData(OverallReportRequest $request)
    {
        $data = $this->getOverallData($request);

        return view('Admin::report.overall_export', compact('data'));
    }

    public function exportEventTickets(Event $event, $orderStatuses = [])
    {
        $q = Ticket
            ::with([
                'place.zone',
                'order' => function ($q) {
                    $q->with([
                        'paymentMethod',
                        'manager',
                        'user',
                    ]);
                },
            ])
            ->whereEventId($event->id)
        ;

        if ($orderStatuses) {
            $q->whereHas('order', function($q) use($orderStatuses) {
                $q->whereIn('status', $orderStatuses);
            });
        } else {
            $q->whereDoesntHave('order');
        }

        $tickets = $q->get();

        $collection = collect();
        /** @var Ticket $ticket */
        foreach ($tickets as $ticket) {
            $row = [
                'zone_name' => $ticket->place->zone->name,
                'row' => $ticket->place->row,
                'num' => $ticket->place->num,
                'price' => PriceHelper::getPriceWithGroup($ticket->price_id, $ticket->price_group_id),
                'barcode' => $ticket->barcode,
                'order_number' => $ticket->order->id,
                'status' => $ticket->order->displayStatus,
                'paid_at' => $ticket->order->paid_at,
                'shipping_method' => $ticket->order->displayShippingType ?: __('Not set'),
                'payment_method' => $ticket->order->paymentMethod->name ?? __('Not set'),
                'partner' => $ticket->order->manager->full_name ?? __('Not set'),
                'client' => $ticket->order->user->full_name ?? __('Not set'),
            ];

            $collection->push($row);
        }

        return $collection;
    }

    public function getEventUnsoldTicketsData(Event $event)
    {
        $tickets = DB::table('tickets')
            ->leftJoin('places', 'tickets.place_id', '=', 'places.id')
            ->leftJoin('zones', 'places.zone_id', '=', 'zones.id')
            ->leftJoin('prices', 'tickets.price_id', '=', 'prices.id')
            ->select([
                'zones.name as zone_name',
                'prices.price as price',
                'places.row as row',
                'places.num as place',
            ])
            ->where('tickets.event_id', $event->id)
            ->whereNull('order_id')
            ->whereNull('deleted_at')
            ->orderByRaw('cast(place as unsigned) asc')
            ->orderByRaw('cast(row as unsigned) asc')
            ->orderBy('price', 'asc')
            ->get()
        ;

        $tickets = $tickets->groupBy([
            'zone_name',
            'price',
            'row',
        ]);

        foreach ($tickets as $zoneName => $zoneTickets) {
            foreach ($zoneTickets as $price => $priceTickets) {
                foreach ($priceTickets as $rowName => $rowTickets) {
                    $composedRow = [];

                    $oldTicket = null;
                    $inLine = null;
                    foreach ($rowTickets as $ticket) {
                        if (empty($oldTicket) || $this->_notConnected($oldTicket, $ticket)) {
                            if ($inLine) $composedRow[] = $inLine;

                            $inLine = new \stdClass();
                            $inLine->row = $rowName;
                            $inLine->start = $ticket->place;
                            $inLine->end = $ticket->place;
                            $inLine->count = 0;
                        }

                        $inLine->end = $ticket->place;
                        $inLine->count++;

                        $oldTicket = $ticket;
                    }
                    $composedRow[] = $inLine;

                    $priceTickets[$rowName] = $composedRow;
                }
            }
        }

        return $tickets;
    }

    public function displayEventUnsoldTickets(Event $event)
    {
        $data = $this->getEventUnsoldTicketsData($event);
        $table = $this->_buildUnsoldTicketsTable($data);
        $composedNames = $this->_getComposedEventNames([$event->id]);
        $fullEventName = $composedNames[$event->id];

        return view('Admin::report.unsold_tickets', compact('table', 'fullEventName'));
    }

    public function getEventData(EventReportRequest $request)
    {
        $eventsPeriodStart = $request->get('event_period_start');
        $eventsPeriodEnd = $request->get('event_period_end');
        $eventId = $request->get('event_id');
        $isActive = $request->get('is_active');
        $eventName = $request->get('event_name');
        $city = $request->get('event_cidy');
        $building = $request->get('event_building');
        $hall = $request->get('event_hall');

        $q = DB::table('events')
            ->select([
                'events.id as id',
                'is_active',
                'date',
                'events.name as name',
                'halls.name as hall_name',
                'buildings.name as building_name',
                'cities.name as city_name',

            ])
            ->leftJoin('halls', 'halls.id', '=', 'events.hall_id')
            ->leftJoin('buildings', 'buildings.id', '=', 'halls.building_id')
            ->leftJoin('cities', 'cities.id', '=', 'buildings.city_id')
        ;

        if ($eventsPeriodStart && $eventsPeriodEnd) $q->whereBetween('date', [
            "$eventsPeriodStart 00:00:00",
            "$eventsPeriodEnd 23:59:59",
        ]);
        if ($eventId) $q->where('events.id', $eventId);
        if ($isActive === '1') $q->where('is_active', 1);
        if ($isActive === '0') $q->where('is_active', 0);
        if ($eventName) $q->where('events.name', 'like', "%$eventName%");
        if ($city) $q->where('cities.name', 'like', "%$city%");
        if ($building) $q->where('buildings.name', 'like', "%$building%");
        if ($hall) $q->where('halls.name', 'like', "%$hall%");

        $data = $q->get();

        return $data;
    }

    public function displayEventData(EventReportRequest $request)
    {
        $data = $this->getEventData($request);

        return view('Admin::report.event_data', compact('data'));
    }

    protected function _getComposedEventNames($ids, $forJS = false)
    {
        $events = DB::table('events')
            ->whereIn('events.id', $ids)
            ->leftJoin('halls', 'halls.id', '=', 'events.hall_id')
            ->leftJoin('buildings', 'buildings.id', '=', 'halls.building_id')
            ->leftJoin('cities', 'cities.id', '=','buildings.city_id')
            ->select([
                'events.id as id',
                'events.name as name',
                'cities.name as city',
                'events.date as date',
            ])
            ->get()
        ;

        $data = [];
        /** @var \stdClass $event */
        foreach ($events as $event) {
            $date = Carbon::createFromFormat('Y-m-d H:i:s', $event->date);
            $name = "{$event->name} ({$date->format('d.m.Y H:i')}), {$event->city}";

            if ($forJS) {
                $data[] = [
                    'id' => $event->id,
                    'name' => $name,
                ];
            } else {
                $data[$event->id] = $name;
            }
        }

        return $data;
    }

    protected function _getByPartnersTotals($data)
    {
        $totals = [
            'count' => 0,
            'price' => 0,
            'discount' => 0,
        ];
        foreach ($data as $primaryGrouping => $primaryData)
        {
            if (!isset($totals[$primaryGrouping])) {
                $totals[$primaryGrouping] = [];
                $totals[$primaryGrouping]['count'] = 0;
                $totals[$primaryGrouping]['price'] = 0;
                $totals[$primaryGrouping]['discount'] = 0;
            }

            foreach ($primaryData as $secondaryGrouping => $secondaryData) {
                if (!isset($totals[$primaryGrouping][$secondaryGrouping])) {
                    $totals[$primaryGrouping][$secondaryGrouping] = [];
                    $totals[$primaryGrouping][$secondaryGrouping]['count'] = 0;
                    $totals[$primaryGrouping][$secondaryGrouping]['price'] = 0;
                    $totals[$primaryGrouping][$secondaryGrouping]['discount'] = 0;
                }

                $count = $secondaryData->sum('count');
                $price = $secondaryData->sum(function ($row) {
                    return $row->count * $row->price;
                });
                $discount = $secondaryData->sum('discount');

                $totals[$primaryGrouping][$secondaryGrouping]['count'] += $count;
                $totals[$primaryGrouping][$secondaryGrouping]['price'] += $price;
                $totals[$primaryGrouping][$secondaryGrouping]['discount'] += $discount;

                $totals[$primaryGrouping]['count'] += $count;
                $totals[$primaryGrouping]['price'] += $price;
                $totals[$primaryGrouping]['discount'] += $discount;

                $totals['count'] += $count;
                $totals['price'] += $price;
                $totals['discount'] += $discount;
            }
        }

        return $totals;
    }

    protected function _getByPartnersTicketsCalculation($data)
    {
        $calc = [];
        foreach ($data as $firstGrouping) {
            foreach ($firstGrouping as $secondGrouping) {
                foreach ($secondGrouping as $row) {
                    if (empty($calc[$row->price])) $calc[$row->price] = [
                        'price' => $row->price,
                        'count' => 0,
                        'sum' => 0,
                    ];

                    $calc[$row->price]['count'] += $row->count;
                    $calc[$row->price]['sum'] += $row->count * $row->price;
                }
            }
        }

        return collect($calc)->sortBy('price');
    }

    protected function _getByBookkeepersTotals($data)
    {
        $totals = [
            'count' => 0,
            'price' => 0,
            'discount' => 0,
        ];
        foreach ($data as $primaryGrouping => $primaryData)
        {
            if (!isset($totals[$primaryGrouping])) {
                $totals[$primaryGrouping] = [];
                $totals[$primaryGrouping]['count'] = 0;
                $totals[$primaryGrouping]['price'] = 0;
                $totals[$primaryGrouping]['discount'] = 0;
            }

            foreach ($primaryData as $secondaryGrouping => $secondaryData) {
                if (!isset($totals[$primaryGrouping][$secondaryGrouping])) {
                    $totals[$primaryGrouping][$secondaryGrouping] = [];
                    $totals[$primaryGrouping][$secondaryGrouping]['count'] = 0;
                    $totals[$primaryGrouping][$secondaryGrouping]['price'] = 0;
                    $totals[$primaryGrouping][$secondaryGrouping]['discount'] = 0;
                }

                $count = $secondaryData->sum('count');
                $price = $secondaryData->sum(function ($row) {
                    return $row->count * $row->price;
                });
                $discount = $secondaryData->sum('discount');

                $totals[$primaryGrouping][$secondaryGrouping]['count'] += $count;
                $totals[$primaryGrouping][$secondaryGrouping]['price'] += $price;
                $totals[$primaryGrouping][$secondaryGrouping]['discount'] += $discount;

                $totals[$primaryGrouping]['count'] += $count;
                $totals[$primaryGrouping]['price'] += $price;
                $totals[$primaryGrouping]['discount'] += $discount;

                $totals['count'] += $count;
                $totals['price'] += $price;
                $totals['discount'] += $discount;
            }
        }

        return $totals;
    }

    protected function _getSecondaryGrouping($primaryGrouping)
    {
        switch ($primaryGrouping) {
            case 'event_name': return 'manager_name';
            case 'manager_name': return 'event_name';
        }

        return null;
    }

    protected function _getEventsSaleStatistics($eventIds, $constraints, $orderStatuses = [])
    {
        $q = DB
            ::table('tickets')
            ->select([
                'tickets.event_id as event_id',
                'tickets.price_id as price_id',
                'tickets.price_group_id as price_group_id',
                DB::raw('count(tickets.id) as count'),
                'orders.status as status',
            ])
            ->leftJoin('orders', 'orders.id', '=', 'tickets.order_id')
            ->whereIn('tickets.event_id', $eventIds)
            ->whereBetween('orders.created_at', [
                "{$constraints['salePeriodStart']} 00:00:00",
                "{$constraints['salePeriodEnd']} 23:59:59",
            ])
            ->groupBy([
                'event_id',
                'price_id',
                'price_group_id',
                'status',
            ])
        ;

        if ($orderStatuses) $q->whereIn('orders.status', $orderStatuses);
        if ($constraints['withPartners']) $q->whereIn('manager_id', $constraints['withPartners']);
        if ($constraints['withoutPartners']) $q->whereNotIn('manager_id', $constraints['withoutPartners']);

        if ($constraints['shippingMethod']) {
            switch ($constraints['shippingMethod']) {
                case 'e_ticket':
                    $q->whereNull('orders.shipping_zone_id')->where('orders.shipping_type', 0);
                    break;
                case 't_office':
                    $q->whereNull('orders.shipping_zone_id')->where('orders.shipping_type', 1);
                    break;
                default:
                    $zoneIds = ShippingZone::whereShippingId($constraints['shippingMethod'])->pluck('id');
                    $q->whereIn('orders.shipping_zone_id', $zoneIds);
            }
        }

        if ($constraints['paymentMethod']) $q->where('orders.payment_method_id', $constraints['paymentMethod']);

        $data = $q->get()->groupBy('event_id');

        return $data;
    }

    protected function _notConnected($ticketAData, $ticketBData)
    {
        try {
            return (
                $ticketAData->place !== $ticketBData->place &&
                intval($ticketAData->place) + 1 !== intval($ticketBData->place)
            );
        } catch (\Exception $e) {
            true;
        }
    }

    protected function _buildUnsoldTicketsTable($data)
    {
        $html = "<table>";

        $html .= "<thead><tr>";
        $html .= "<th>" . __('Zone') . "</th>";
        $html .= "<th>" . __('Price') . "</th>";
        $html .= "<th>" . __('Row') . ":" . __('Place') . "</th>";
        $html .= "<th>" . __('Amount') . "</th>";
        $html .= "<th>" . __('Total price') . "</th>";
        $html .= "</tr></thead>";

        $body = "<tbody>";
        $totalCount = 0;
        $totalPrice = 0;
        foreach ($data as $zoneName => $zoneData) {
            $zoneRowSpan = 0;
            $zonePlaceholder = '%zone_td%';

            foreach ($zoneData as $price => $computedRows) {
                $priceRowSpan = 0;
                $pricePlaceholder = '%price_td%';

                foreach ($computedRows as $computedPlaces) {
                    foreach ($computedPlaces as $line) {
                        $zoneRowSpan++;
                        $priceRowSpan++;
                        $totalCount += $line->count;
                        $totalPrice += $line->count * $price;

                        $body .= "<tr>";
                        $body .= $zonePlaceholder;
                        $body .= $pricePlaceholder;
                        $body .= "<td>{$this->_composePlacesRange($line)}</td>";
                        $body .= "<td>{$line->count}</td>";
                        $body .= "<td>" . sprintf('%1.2f', $line->count * $price) . ' ' . __(Order::CURRENCY) . "</td>";
                        $body .= "</tr>";

                        $zonePlaceholder = '';
                        $pricePlaceholder = '';
                    }
                }

                $body = str_replace(
                    '%price_td%',
                    "<td rowspan='{$priceRowSpan}'>" . sprintf('%1.2f', $price) .' '. __(Order::CURRENCY) . "</td>",
                    $body
                );
            }

            $body = str_replace(
                '%zone_td%',
                "<td rowspan='{$zoneRowSpan}'>" . $zoneName . "</td>",
                $body
            );
        }
        $body .= "<tr><td colspan='3' align='right'>".__('Total')."</td><td>{$totalCount}</td><td>".sprintf('%1.2f', $totalPrice).' '.__(Order::CURRENCY)."</td></tr>";
        $body .= "</tbody>";


        $html .= $body;
        $html .= "</table>";

        return $html;
    }

    protected function _composePlacesRange($line)
    {
        if (!$line->row && !$line->start) return __('Fan zone');

        if ($line->start == $line->end) return "$line->row:$line->start";

        return "$line->row:$line->start-$line->end";
    }

    public static function getEventNamesOptions()
    {
        return Event::distinct()->pluck('name', 'name');
    }

    public static function getPartnersOptions($includeOwner = false, $idOnly = false)
    {
        $data = [
            __('Partners') => User
                ::whereHas('roles', function ($q) {
                    $q->whereName(Role::PARTNER);
                })
                ->select([
                    'id',
                    'first_name',
                    'last_name',
                ])
                ->get()
                ->pluck('full_name', 'id')
        ];

        if ($includeOwner) {
            $data[__('Organizer')] = User
                ::whereHas('roles', function ($q) {
                    $q->whereName(Role::ADMIN);
                })
                ->select([
                    'id',
                    'first_name',
                    'last_name',
                ])
                ->get()
                ->pluck('full_name', 'id')
            ;
        }

        if ($idOnly) {
            $ids = [];
            foreach ($data as $group) {
                $ids = array_merge($ids, $group->keys()->toArray());
            }

            return $ids;
        }

        return collect($data);
    }

    public static function getBookkeeperOptions()
    {
        $data = User
            ::whereHas('roles', function ($q) {
                $q->whereName(Role::BOOKKEEPER);
            })
            ->select([
                'id',
                'first_name',
                'last_name',
            ])
            ->get()
            ->pluck('full_name', 'id')
        ;

        return $data;
    }

    public static function getOrderStatusesOptions()
    {
        return [
            Order::STATUS_PENDING => __('Pending'),
            Order::STATUS_CONFIRMED => __('Confirmed'),
            Order::STATUS_CANCELED => __('Cancelled'),
            Order::STATUS_RESERVE => __('Reserve'),
            Order::STATUS_REALIZATION => __('Realization'),
        ];
    }

    public static function getTicketStatusesOptions()
    {
        return [
            Ticket::SOLD => __('Paid'),
        ];
    }

    public static function getPaymentMethodOptions()
    {
        return PaymentMethod::pluck('name', 'id');
    }

    public static function getDeliveryMethodOptions()
    {
        return collect([
            'e_ticket' => __('E-ticket'),
            't_office' => __('Evening ticket office'),
        ])->merge(Shipping::pluck('name', 'id'));
    }

    public static function getOverallReportSortOptions()
    {
        return [
            'sold_count' => __('Total of sold tickets'),
            'all_transactions_count' => __('Total of sold tickets and in realization'),
        ];
    }

    public function getPartnerData(PartnerReportRequest $request)
    {
        $eventNames = $request->get('event_name', []);
        $eventPeriodStart = $request->get('event_period_start');
        $eventPeriodEnd = $request->get('event_period_end');
        $eventIds = $request->get('event_ids', []);
        $salePeriodStart = $request->get('sale_period_start');
        $salePeriodEnd = $request->get('sale_period_end');
        $orderStatuses = $request->get('order_statuses', []);

        $query = DB
            ::table('orders')
            ->where('manager_id', Auth::id())
        ;

        if ($eventIds || $eventNames || $eventPeriodStart && $eventPeriodEnd)
            $query->whereExists(function ($q) use ($eventIds, $eventNames, $eventPeriodStart, $eventPeriodEnd) {
                $q
                    ->select(DB::raw(1))
                    ->from('tickets')
                    ->whereRaw('tickets.order_id = orders.id')
                ;

                if ($eventIds) {
                    $q->whereIn('event_id', $eventIds);
                } else if ($eventNames) {
                    $q->where(function ($q) use($eventNames) {
                        foreach ($eventNames as $name) {
                            $q->orWhere('name', 'like', "%$name%");
                        }
                    });
                }

                if ($eventPeriodStart && $eventPeriodEnd) {
                    $q->whereBetween('date', ["$eventPeriodStart 00:00:00", "$eventPeriodEnd 23:59:59"]);
                }
            });

        if ($salePeriodStart && $salePeriodEnd) $query->whereBetween('created_at', [
            "$salePeriodStart 00:00:00",
            "$salePeriodEnd 23:59:59",
        ]);

        if ($orderStatuses) $query->whereIn('status', $orderStatuses);

        $orderIds = $query->pluck('id');

        $dataQuery = DB
            ::table('tickets')
            ->leftJoin('orders', 'orders.id', '=', 'tickets.order_id')
            ->select([
                DB::raw('(select concat(coalesce(u.first_name, ""), " ", coalesce(u.last_name, "")) from users u where u.id = orders.manager_id) as manager_name'),
                DB::raw('count(tickets.id) as count'),
                DB::raw('sum(tickets.discount) as discount'),
                'manager_id',
                'event_id',
                'price_id',
                'price_group_id',
                'orders.status as status',
            ])
            ->whereIn('order_id', $orderIds)
            ->groupBy([
                'manager_name',
                'manager_id',
                'event_id',
                'price_id',
                'price_group_id',
                'status',
            ])
        ;

        if ($eventIds) $dataQuery->whereIn('event_id', $eventIds);

        $data = $dataQuery->get();
        $eventIds = $data->pluck('event_id');

        $eventNames = $this->_getComposedEventNames($eventIds);

        foreach ($data as $row) {
            $row->event_name = $eventNames[$row->event_id];
            $row->price = PriceHelper::getPriceWithGroup($row->price_id, $row->price_group_id);
        }

        $data = $data->groupBy('event_name');

        return $data;
    }

    public function displayPartnerData(PartnerReportRequest $request)
    {
        $data = $this->getPartnerData($request);
        $totals = $this->_getPartnerTotals($data);
        $tickets = $this->_getPartnerTicketsCalculation($data);
        $salePeriodStart = $request->get('sale_period_start');
        $salePeriodEnd = $request->get('sale_period_end');
        $eventPeriodStart = $request->get('event_period_start');
        $eventPeriodEnd = $request->get('event_period_end');

        return view('Admin::report.partner_data', [
            'data' => $data,
            'totals' => $totals,
            'request' => $request,
            'tickets' => $tickets,
            'salePeriodStart' => $salePeriodStart,
            'salePeriodEnd' => $salePeriodEnd,
            'eventPeriodStart' => $eventPeriodStart,
            'eventPeriodEnd' => $eventPeriodEnd,
            'displayStatuses' => self::getOrderStatusesOptions(),
        ]);
    }

    public function exportPartnerData(PartnerReportRequest $request)
    {
        $data = $this->getPartnerData($request);
        $totals = $this->_getPartnerTotals($data);
        $tickets = $this->_getPartnerTicketsCalculation($data);
        $salePeriodStart = $request->get('sale_period_start');
        $salePeriodEnd = $request->get('sale_period_end');
        $eventPeriodStart = $request->get('event_period_start');
        $eventPeriodEnd = $request->get('event_period_end');

        return view('Admin::report.partner_export', [
            'data' => $data,
            'totals' => $totals,
            'request' => $request,
            'tickets' => $tickets,
            'salePeriodStart' => $salePeriodStart,
            'salePeriodEnd' => $salePeriodEnd,
            'eventPeriodStart' => $eventPeriodStart,
            'eventPeriodEnd' => $eventPeriodEnd,
            'displayStatuses' => self::getOrderStatusesOptions(),
        ]);
    }

    protected function _getPartnerTotals($data)
    {
        $totals = [
            'count' => 0,
            'price' => 0,
            'discount' => 0,
        ];
        foreach ($data as $eventName => $eventData)
        {
            if (!isset($totals[$eventName])) {
                $totals[$eventName] = [];
                $totals[$eventName]['count'] = 0;
                $totals[$eventName]['price'] = 0;
                $totals[$eventName]['discount'] = 0;
            }

            $count = $eventData->sum('count');
            $price = $eventData->sum(function ($row) {
                return $row->count * $row->price;
            });
            $discount = $eventData->sum('discount');

            $totals[$eventName]['count'] += $count;
            $totals[$eventName]['price'] += $price;
            $totals[$eventName]['discount'] += $discount;

            $totals['count'] += $count;
            $totals['price'] += $price;
            $totals['discount'] += $discount;
        }

        return $totals;
    }

    protected function _getPartnerTicketsCalculation($data)
    {
        $calc = [];
        foreach ($data as $firstGrouping) {
            foreach ($firstGrouping as $row) {
                if (empty($calc[$row->price])) $calc[$row->price] = [
                    'price' => $row->price,
                    'count' => 0,
                    'sum' => 0,
                ];

                $calc[$row->price]['count'] += $row->count;
                $calc[$row->price]['sum'] += $row->count * $row->price;
            }
        }

        return collect($calc)->sortBy('price');
    }
}