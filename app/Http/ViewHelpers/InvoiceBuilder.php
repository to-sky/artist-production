<?php
namespace App\Http\ViewHelpers;

use App\Models\Event;
use App\Models\Order;
use App\Models\Ticket;

class InvoiceBuilder
{
    /**
     * Builds tickets part of invoice's table
     *
     * @param Order $order
     * @return string
     */
    static public function ticketsTableRows(Order $order)
    {
        $ticketsByEvent = $order->tickets()->get()->sortBy('num')->sortBy('row')->groupBy('event_id');

        $html = '';
        foreach ($ticketsByEvent as $eventId => $tickets) {
            $event = Event::with('hall.building.city')->whereId($eventId)->first();
            $ticketRows = self::buildTicketRows($tickets);
            $eventRowSpan = count($ticketRows);
            $date = $event->date->format('d.m.Y <b\r> H:i');
            $building = "{$event->hall->name}, {$event->hall->building->name}, {$event->hall->building->city->name}";

            for($i = 0; $i < $eventRowSpan; $i++) {
                $html .= "<tr valign=\"top\" align=\"center\">";

                if ($i == 0) {
                    $html .= "<td rowspan=\"{$eventRowSpan}\">{$event->name}</td>";
                    $html .= "<td rowspan=\"{$eventRowSpan}\">{$building}</td>";
                    $html .= "<td rowspan=\"{$eventRowSpan}\">{$date}</td>";
                }

                $t = $ticketRows[$i];

                $html .= "<td>{$t['places']}</td><td>{$t['count']}</td><td>{$t['price']}</td><td>{$t['total']}</td>";

                $html .= "</tr>";
            }
        }

        return $html;
    }

    /**
     * Makes data for tickets row's part
     *
     * @param $tickets
     * @return array
     */
    static protected function buildTicketRows($tickets)
    {
        $listParts = [];
        $lastPlace = null;
        $lastPrice = null;
        $lastRow = null;
        $lastNum = null;
        /** @var Ticket $ticket */
        foreach ($tickets as $ticket) {
            if (
                $ticket->place_id == $lastPlace &&
                $ticket->getBuyablePrice() == $lastPrice &&
                $ticket->place->row == $lastRow &&
                $ticket->place->num == $lastNum || $ticket->place->num == $lastNum + 1
            ) {
                $listParts[count($listParts) - 1]['end_num'] = $ticket->place->num;
                $listParts[count($listParts) - 1]['count'] += 1;
            } else {
                $listParts[] = [
                    'zone' => $ticket->place->zone()->value('name'),
                    'text' => $ticket->place->text,
                    'row' => $ticket->place->row,
                    'start_num' => $ticket->place->num,
                    'end_num' => $ticket->place->num,
                    'count' => 1,
                    'price' => $ticket->getBuyablePrice(),
                ];
            }

            $lastPlace = $ticket->place_id;
            $lastPrice = $ticket->getBuyablePrice();
            $lastRow = $ticket->place->row;
            $lastNum = $ticket->place->num;
        }

        $list = [];
        foreach ($listParts as $part) {
            $zone = $part['zone'] ?: '-';
            $row = $part['row'] ?: '-';
            $range = self::buildPlaceRange($part);

            $list[] = [
                'places' => "Block {$zone}, R. {$row}, Pl. {$range}",
                'count' => $part['count'],
                'price' => sprintf('%1.2f', $part['price']),
                'total' => sprintf('%1.2f', $part['price'] * $part['count']),
            ];
        }

        return $list;
    }

    /**
     * Build place range text
     *
     * @param $part
     * @return string
     */
    static protected function buildPlaceRange($part)
    {
        if (empty($part['start_num'])) return '-';
        if ($part['start_num'] == $part['end_num']) return $part['start_num'];

        return $part['start_num'] . '-' . $part['end_num'];
    }

    /**
     * Build total part of invoice's table
     *
     * @param Order $order
     * @return string
     */
    static public function totalTableRow(Order $order)
    {
        $html = '';

        $html .= '<tr>';

        $html .= '<td colspan="6" align="left" valign="bottom">';
        $html .= self::taxesTable($order);
        $html .= '</td>';

        $html .= '<td>';
        $html .= self::totalTable($order);
        $html .= '</td>';

        $html .= '</tr>';

        return $html;
    }

    /**
     * Taxes calculation table
     *
     * @param Order $order
     * @return string
     */
    static protected function taxesTable(Order $order)
    {
        $html = '<table width="100%" cellspacing="0" cellpadding="0" style="font-size: 12px;">';

        $html .= '<tr><td align="left" valign="bottom">';
        $html .= self::taxesTableCalculationPart($order);
        $html .= '</td>';

        $html .= '<td align="right">';
        $html .= self::taxesTableLabelsPart($order);
        $html .= '</td></tr>';

        $html .= '</table>';

        return $html;
    }

    /**
     * part of taxes calculation table
     *
     * @param Order $order
     * @return string
     */
    static protected function taxesTableCalculationPart(Order $order)
    {
        $html = 'Der Gesamtbetrag setzt sich wie nachfolgend zusammen:';

        $html .= '<table style="border-spacing: 10px 0; font-size: 12px;">';

        $shippingFull = $order->shipping_price + $order->service_price;
        $shippingTax = .19 * $shippingFull;

        $ticketsFull = $order->subtotal;
        $ticketsTax = .07 * $ticketsFull;

        $html .=
            '<tr>' .
                '<td align="right">19% USt. aus</td>' .
                '<td align="right">'.sprintf('%1.2f', $shippingFull).'</td>' .
                '<td align="right">'.sprintf('%1.2f', $shippingTax).'</td>' .
                '<td align="right">Netto</td>' .
                '<td align="right">'. sprintf('%1.2f', $shippingFull - $shippingTax) .'</td>' .
            '</tr>'
        ;

        $html .=
            '<tr>' .
                '<td align="right">7% USt. aus</td>' .
                '<td align="right">'.sprintf('%1.2f', $ticketsFull).'</td>' .
                '<td align="right">'.sprintf('%1.2f', $ticketsTax).'</td>' .
                '<td align="right">Netto</td>' .
                '<td align="right">'. sprintf('%1.2f', $ticketsFull - $ticketsTax) .'</td>' .
            '</tr>'
        ;

        $html .= '</table>';

        return $html;
    }


    /**
     * Labels for total calculation
     *
     * @param Order $order
     * @return string
     */
    static protected function taxesTableLabelsPart(Order $order)
    {
        $html = '<table width="100%" style="font-size: 12px;">';
        $html .= '<tr><td align="right">Menge:</td></tr>';
        $html .= '<tr><td align="right">Summe:</td></tr>';
        if ($order->shipping_price) $html .= '<tr><td align="right">Versand:</td></tr>';
        if ($order->service_price) $html .= '<tr><td align="right">Servicegebühr:</td></tr>';
        if ($order->discount) $html .= '<tr><td align="right">Rabatt:</td></tr>';
        $html .= '<tr><td align="right">Gesamtbetrag:</td></tr>';
        $html .= '</table>';

        return $html;
    }

    /**
     * Total calculation
     *
     * @param Order $order
     * @return string
     */
    static protected function totalTable(Order $order)
    {
        $html = '<table width="100%" cellspacing="0" cellpadding="0" style="font-size: 12px;">';

        $html .= '<tr><td>';
        $html .= '<table width="100%" style="font-size: 12px;">';
        $html .= '<tr><td align="center">'.count($order->tickets).'</td></tr>';
        $html .= '<tr><td align="center">'.sprintf('%1.2f', $order->subtotal).'</td></tr>';
        if ($order->shipping_price) $html .= '<tr><td align="center">'.sprintf('%1.2f', $order->shipping_price).'</td></tr>';
        if ($order->service_price) $html .= '<tr><td align="center">'.sprintf('%1.2f', $order->service_price).'</td></tr>';
        if ($order->discount) $html .= '<tr><td align="center">'.sprintf('-%1.2f', $order->discount).'</td></tr>';
        $html .= '<tr><td align="center">'.sprintf('%1.2f', $order->total).'</td></tr>';
        $html .= '</table>';
        $html .= '</td></tr>';

        $html .= '</table>';

        return $html;
    }

    static public function bankDetailsTable(Order $order, $data)
    {
        $html = '<table width="100%" cellpadding="0" cellspacing="0" style="font-size: 12px;">';

        $html .=
            '<tr><td width="20%">Empfänger:</td><td>'. $data['company_name'] . ' Eventagentur UG'.'</td></tr>' .
            '<tr><td>Konto Nr.:</td><td>'.$data['company_bank_account'].'</td></tr>' .
            '<tr><td>SWIFT-BIC:</td><td>'.$data['company_bank_bic'].'</td></tr>' .
            '<tr><td>IBAN:</td><td>'.$data['company_bank_iban'].'</td></tr>' .
            '<tr><td>Bank:</td><td>'.$data['company_bank_name'].'</td></tr>' .
            '<tr><td>Verwendungszweck:</td><td>'. $order->id .'-p</td></tr>'
        ;

        $html .= '</table>';

        return $html;
    }
}