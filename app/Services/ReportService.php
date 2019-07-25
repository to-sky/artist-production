<?php

namespace App\Services;

use App\Helpers\PriceHelper;
use App\Models\Event;
use App\Models\Order;
use App\Models\Role;
use App\Models\User;
use App\Modules\Admin\Requests\ByPartnersReportRequest;
use Carbon\Carbon;
use DB;


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

        $query = DB::table('orders')->whereNotNull('manager_id');

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
                $discount = $secondaryData->sum(function ($row) {
                    return $row->count * $row->discount;
                });

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

    protected function _getSecondaryGrouping($primaryGrouping)
    {
        switch ($primaryGrouping) {
            case 'event_name': return 'manager_name';
            case 'manager_name': return 'event_name';
        }

        return null;
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

    public static function getEventNamesOptions()
    {
        return Event::distinct()->pluck('name', 'name');
    }

    public static function getPartnersOptions($includeOwner = false)
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
}