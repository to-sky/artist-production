<html>
<head>
    <meta charset="utf-8">
    <style>
        @page {
            margin: 20px 20px 1px 40px;
        }

        @font-face {
            font-family: ProximaNova;
            src:
                url('{{ asset('fonts/ProximaNova-Regular.eot') }}') format('eot'),
                url('{{ asset('fonts/ProximaNova-Regular.ttf') }}') format('truetype'),
                url('{{ asset('fonts/ProximaNova-Regular.woff') }}') format('woff');
        }

        @font-face {
            font-family: ProximaNovaSB;
            src:
                url('{{ asset('fonts/ProximaNova-Semibold.eot') }}') format('eot'),
                url('{{ asset('fonts/ProximaNova-Semibold.ttf') }}') format('truetype'),
                url('{{ asset('fonts/ProximaNova-Semibold.woff') }}') format('woff');
            font-weight: 700;
        }

        body {
            font-family: ProximaNova;
            line-height: 1.7em;
            font-size: 12px;
            margin: 20px 20px 1px 40px;
            max-width: 708px;
        }
    </style>
</head>

<body>
<table width="100%">
    <tr>
        <td align="right">
            <img width="50" height="50" src="{{ asset('/images/logo.png') }}" alt="{{ config('app.name') }}">
        </td>
    </tr>
</table>

<br><br><br>

<table width="100%">
    <tr>
        <td style="font-size: 10px; width: 65%">
            Wenn unzustellbar, zurück!<br>
            {{ $data['company_name'] }} Eventagentur UG, {{ $data['company_address'] }}, {{ $data['company_post_code'] }} {{ $data['company_city'] }}<br>
            &nbsp;
        </td>
        <td style="font-size: 12px;" rowspan="2" valign="top">
            Bei Rückfragen zur Rechnung:<br>
            Tel.: {{ $data['company_phone'] }}<br>
            eMail: {{ $data['company_mail'] }}<br>
            Web: http://{{ str_replace(['http://', 'https://'], '', $data['company_website']) }}<br>
        </td>
    </tr>

    <tr>
        <td style="font-size: 13px;">
            @if($order->user)
                {{ $order->billingAddress->last_name }} {{ $order->billingAddress->first_name }}<br>
                {{ $order->billingAddress->street }} {{ $order->billingAddress->house }} {{ $order->billingAddress->apartment }}<br>
                {{ $order->billingAddress->post_code }} {{ $order->billingAddress->city }}<br>
                {{ $order->billingAddress->country }}<br>
            @else
                {{ __('Anonymous') }}
            @endif
                &nbsp;<br>
                &nbsp;
        </td>
    </tr>

    <tr valign="bottom">
        <td style="font-size: 12px;">
            Kundennummer: {{ $order->user_id }}<br>
            Rechnungsnummer: {{ $order->id }}{{ $tag == 'provisional' ? '-p' : '' }}
        </td>
        <td style="font-size: 12px;">
            {{ \Carbon\Carbon::now()->format('d.m.Y') }}
        </td>
    </tr>
</table>

<br><br>

<span style="font-size: 26px; font-family: ProximaNovaSB; line-height: 1em">Rechnung</span>
<table border="1" bordercolor="black" bordercolorlight="white" bordercolordark="white" cellspacing="0" cellpadding="2" style="width: 100%; font-size: 12px; font-family: ProximaNovaSB; line-height: 1.1">
    <tr align="center">
        <td width="12%">Veranstaltung</td>
        <td width="12%">Ort</td>
        <td width="15%">Datum</td>
        <td width="28%">Plätze</td>
        <td width="10%">Anzahl</td>
        <td width="10%">Preis, EUR</td>
        <td width="13%">Summe, EUR</td>
    </tr>

    {!! InvoiceBuilder::ticketsTableRows($order) !!}

    {!! InvoiceBuilder::totalTableRow($order) !!}
</table>

<br><br>

@if ($tag == 'provisional')

    Sofern Sie die Zahlungsmodalitat "Vorkasse" gewahlt haben, uberweisen Sie bitte den Betrag innerhalb von 7 Tagen, mit
    Angabe Ihrer Rechnungsnummer, auf folgendes Konto:

    <br><br>

    {!! InvoiceBuilder::bankDetailsTable($order, $data) !!}

@else

    Der Rechnungsbetrag wurde bereits beglichen.

@endif

<br>

Die Ruckgabe von Eintrittskarten ist nur bei Absage oder Terminverlegung moglich. In diesen Fallen muss die Eintrittskarte
unverzuglich, spatestens jedoch bis 14 Tage nach dem ursprunglichen Veranstaltungstermin an {{ $data['company_name'] }}
Eventagentur UG zuruckgesendet werden. Die Erstattung des vollen Ticketpreises erfolgt, sofern die Original-Eintrittskarten
(keine Kopie) und die Originalrechnung der {{ $data['company_name'] }} Eventagentur UG vorliegen.

<br><br>

Vielen Dank für Ihre Bestellung <br>
Ihre {{ $data['company_name'] }} Eventagentur UG <br>
(wenn Sie noch Fragen haben, können Sie sich immer an uns wenden) <br>

<br>

Es gelten unsere allgemeinen Geschäftsbedingungen.

<br><br>

<table width="100%" style="font-size: 14px; color:#9f191f; font-family: ProximaNovaSB">
    <tr><td align="center">!ТВОЙ КОНЦЕРТ - ТВОЙ ВЫБОР!</td></tr>
</table>

<br>
<hr>

<table width="100%" cellspacing="0" cellpadding="0" style="color: #888888;">
    <tr>
        <td width="24%" style="font-family: ProximaNovaSB">{{ $data['company_name'] }} UG</td>
        <td width="35%" style="font-family: ProximaNovaSB">Geschäftsführer: {{ $data['company_director'] }}</td>
        <td style="font-family: ProximaNovaSB">{{ $data['company_bank_name'] }}</td>
    </tr>
    <tr>
        <td>{{ $data['company_address'] }}</td>
        <td>{{ $data['company_registered_by'] }}</td>
        <td>Kontonummer: {{ $data['company_bank_account'] }}</td>
    </tr>
    <tr>
        <td>{{ $data['company_post_code'] }} {{ $data['company_city'] }}</td>
        <td>St.-Nr. {{ $data['company_tax_number'] }}</td>
        <td>BLZ: {{ $data['company_bank_blz'] }}</td>
    </tr>
    <tr>
        <td>{{ $data['company_phone'] }}</td>
        <td>Ust-ID: {{ $data['company_tin'] }}</td>
        <td>IBAN: {{ $data['company_bank_iban'] }}</td>
    </tr>
    <tr>
        <td><a href="mailto:{{ $data['company_mail'] }}">{{ $data['company_mail'] }}</a></td>
        <td><a href="http://{{ str_replace(['http://', 'https://'], '', $data['company_website']) }}">{{ str_replace(['http://', 'https://'], '', $data['company_website']) }}</a></td>
        <td>BIC (SWIFT-code): {{ $data['company_bank_bic'] }}</td>
    </tr>
</table>
</body>

<script>
    window.addEventListener('DOMContentLoaded', function (e) {
      window.print();
    });
</script>

</html>
