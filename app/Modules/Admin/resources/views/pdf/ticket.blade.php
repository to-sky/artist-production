<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Ticket №{{ $ticket->id }}</title>
</head>
<body>
<style>
    body {
        font-family: DejaVu Sans;
        font-size: 12px;
        line-height: .8;
    }

    header, .ticket {
        line-height: 1.1;
    }

    p {
        padding: 0;
        margin: 0;
    }

    .list-none {
        padding-left: 0;
        list-style: none;
    }

    .float-left {
        float: left;
    }

    .float-right {
        float: right;
    }

    .clearfix:before,
    .clearfix:after {
        display: table;
        content: " ";
    }

    .clearfix:after {
        clear: both;
    }

    .width-50 {
        width: 50%;
    }

    .width-33 {
        width: 33%;
    }

    .h3 {
        font-size: 18px;
        font-weight: 600;
    }

    .text-italic {
        font-style: italic;
    }

    .text-sm {
        font-size: 8px;
    }

    .text-m {
        font-size: 16px;
    }

    .text-lg {
        font-size: 20px;
    }

    .text-right {
        text-align: right;
    }

    .text-center {
        text-align: center;
    }

    .text-light {
        color: #666;
    }

    header, footer {
        padding: 0 32px;
    }

    .logo {
        width: 70px;
        margin-bottom: 15px;
    }

    .logo-text {
        margin-left: -110px;
        margin-top: -20px;
        font-style: italic;
        font-size: 18px;
        font-weight: bold;
        line-height: .8;
        color: #333;
    }

    .logo-text span {
        color: #DF7701;
    }

    .ticket-logo {
        margin-left: -30px;
        margin-top: 30px;
        width: 120px;
    }

    .free-pass-logo {
        padding-top: 10px;
        width: 100px;
    }

    .lb {
        padding: 10px 0;
    }

    .lb:after {
        content: '';
        display: block;
        border-top: 1px dashed #666;
    }

    .lb p {
        width: 155px;
        margin-top: -5px;
        margin-right: 25px;
        float: right;
        background: white;
        text-align: center;
        font-size: 10px;
        color: #999;
    }

    .ticket {
        padding: 15px;
        background: #f08f48;
    }

    .ticket-container {
        width: 100%;
        padding: 10px;
        background: url("{{ asset('images/cream_pixels.png') }}");
    }

    .event-name {
        padding: 0;
        margin: 0;
        font-size: 20px;
        line-height: 1;
        text-transform: uppercase;
    }

    .rules {
        padding: 0 10px;
    }

    .rules ul {
        padding: 0 12px 0;
        font-size: 11px;
    }

    .qr-block img {
        width: 60px;
    }

    .barcode-container {
        transform: rotate(90deg);
        margin-right: -80px;
        margin-left: -70px;
    }
</style>

<header>
    <div style="margin-top: -10px;">
        <img src="{{ asset('images/logo.png') }}" alt="{{ config('app.name', 'Artist Production') }}" class="logo">
    </div>

    <div class="client-block clearfix">
        <ul class="list-none float-left width-33">
            @if($ticket->user)
                <li>
                    <b>{{ $ticket->user->full_name }}</b>
                </li>
                {{-- TODO: add user(client) data--}}
                <li>Hartungstrasse 13</li>
                <li>30419 Hannover</li>
                <li>Germany</li>
                <li>491638727790</li>
            @endif
        </ul>

        <div class="client-details float-left width-50" style="margin-top: -30px;">
            <div class="clearfix">
                <table class="float-right">
                    <tr>
                        <td></td>
                        <td class="text-m">
                            <b>BUCHUNGSDATEN</b>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-light">Auftragsnummer:</td>
                        <td>{{ $ticket->order->id }}</td>
                    </tr>
                    <tr>
                        <td class="text-light text-right">Buchung:</td>
                        <td>{{ $ticket->order->date->format('d.m.Y') }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="qr-block float-right" style="margin-top: -50px">
            <img src="data:image/png;base64, {{ DNS2D::getBarcodePNG($ticket->barcode, "QRCODE") }}" alt="qr-code">
        </div>
    </div>
</header>

<div class="lb">
    <p>Линия сгиба 2 / Faltmarke 2</p>
</div>
<br><br>

<main>
    <div class="ticket">
        <table class="ticket-container" style="width: 100%">
            <tr>
                <td>
                    <div>
                        <img src="{{ asset('images/logo.png') }}" alt="Artist Production" class="logo">
                    </div>
                </td>
                <td>
                    <div class="text-m logo-text">
                        <img src="{{ asset('images/ticket_logo.png') }}" alt="ticket_logo" class="logo ticket-logo">
                    </div>
                </td>
                <td class="text-right">
                    <div style="margin-top: -5px;">
                        <p>
                            <b>{{ $ticket->id }}</b>
                        </p>
                        <span>{{ $ticket->order->id }}</span>
                    </div>
                    <p>
                        <img src="{{ isset($ticket->event->freePassLogo) ? $ticket->event->freePassLogo->file_url : '' }}"
                             alt="free_pass_logo" class="free-pass-logo">
                    </p>
                </td>
                <td rowspan="4" width="45">
                    <div class="barcode-container">
                        {!! DNS1D::getBarcodeHTML("$ticket->barcode", "C128C") !!}
                    </div>
                </td>
            </tr>
            <tr>
                <td colspan="2" style="padding-bottom: 10px">
                    <h1 class="event-name">{{ $ticket->event->name }}</h1>
                    <p class="text-italic">{{ cyr2lat($ticket->event->name) }}</p>
                </td>
                <td class="text-right text-m">
                    <p><b>{{ $ticket->event->date->format('d.m.Y') }}</b></p>
                    <p><b>{{ $ticket->event->date->format('H:i') }}</b></p>
                </td>
            </tr>

            <tr>
                <td>
                    <p><b>{{ $ticket->event->hall->building->name }}</b></p>
                    <p>{{ $ticket->event->hall->building->address }}</p>
                </td>

                <td>
                    <p><b>Mittelhochparkett Mitte</b></p>
                    <p>Reihe: <b>{{ $ticket->place->row }}</b></p>
                    <p>Platz: <b>{{ $ticket->place->num }}</b></p>
                </td>

                <td class="text-right" width="80">
                    <p class="text-sm text-light">Normalpreis</p>
                    <p class="text-lg"><b>&euro; {{ $ticket->price }}</b></p>
                    <p class="text-sm">Inklusive Gebühren</p>
                </td>
            </tr>
        </table>
    </div>

    <div class="lb">
        <p>Линия сгиба 1 / Faltmarke 1</p>
    </div>
    <br><br>

    <div class="rules clearfix">
        <div class="width-50 float-left">
            <p>
                <b>ПРАВИЛА ИСПОЛЬЗОВАНИЯ:</b>
            </p>
            <ul>
                <li>С покупкой данного билета Вы принимаете наши общие
                    коммерческие условия ({{ config('app.url') }}/datenschutz).
                </li>
                <li>Пожалуйста, проверьте правильность всех данных на
                    билете, во избежание ошибок.
                </li>
                <li>Обмен и возврат билетов по желанию клиента не
                    производится! Возврат билетов осуществляется только в
                    случае отмены, замены и/или переноса мероприятия в
                    соответствии с правилами, установленными
                    организаторами.
                </li>
                <li>Билет действителен в распечатанном виде только в
                    формате А4.
                </li>
                <li>Только билеты, распечатанные в хорошем качестве
                    могут быть распознаны сканером во время контроля.
                </li>
                <li>Пожалуйста, защищайте билет от влажности и
                    загрязнений.
                </li>
                <li>Билет дает право только одного прохода на
                    мероприятие и обесценивается при первом контроле.
                </li>
                <li>Не допускайте повторной печати и копирования билета
                    посторонними лицами, так как они могут воспользоваться
                    им раньше Вас!
                </li>
                <li>При необходимости контроллер может попросить
                    предоставить удостоверение личности.</li>
            </ul>

            <p>
                <b>Желаем Вам приятно провести время!</b>
            </p>
            <p>
                <b>{{ ucfirst(config('app.domain')) }}</b>
            </p>
        </div>
        <div class="width-50 float-left">
            <p>
                <b>ANWENDUNGSHINWEISE:</b>
            </p>
            <ul>
                <li>Mit dem Erwerb dieses Tickets akzeptieren Sie unsere AGB,
                    die Sie unter ({{ config('app.url') }}/datenschutz) finden.
                </li>
                <li>Bitte überprüfen Sie alle Angaben auf Ihren Tickets, um
                    Fehler zu vermeiden.
                </li>
                <li>Die Tickets sind vom Umtausch oder Rückgabe
                    ausgeschlossen. Ausnahme sind die Absage und/oder
                    Verlegung einer Veranstaltung im Rahmen vom Veranstalter
                    festgelegten Regeln.
                </li>
                <li>Das Ticket ist nur als kompletter DIN A4-Ausdruck gültig.</li>
                <li>Nur Tickets in guter Druckqualität können vom Scanner am
                    Einlass gelesen werden.
                </li>
                <li>Bitte schützen Sie das Ticket vor Nässe und Beschmutzung.
                </li>
                <li>Das Ticket ist nur für einen einmaligen Einlass gültig und
                    wird bei der ersten Kontrolle entwertet.
                </li>
                <li>Lassen Sie nicht zu, dass das Ticket umkopiert wird, um zu
                    vermeiden, dass die Duplikate auftauchen und andere diese
                    früher als Sie nützen.
                </li>
                <li>Falls notwendig, werden Sie durch System aufgefordert, Ihre
                    Identität nachzuweisen.</li>
                <li>Das Mitbringen von Taschen und Rücksäcken größer DIN A4
                    (20x30cm) ist nicht gestattet</li>
            </ul>
            <p>
                <b>Wir wünschen Ihnen viel Spaß bei Ihrem Event!</b>
            </p>
            <p>
                <b>{{ ucfirst(config('app.domain')) }}</b>
            </p>
        </div>
    </div>
</main>

<div class="lb"></div>
<br><br><br>

<footer class="clearfix">
    <div class="qr-block width-50 float-left">
        <img src="data:image/png;base64, {{ DNS2D::getBarcodePNG($ticket->barcode, "QRCODE") }}" alt="qr-code">
    </div>
    <div class="float-left">
        <p class="h3">&euro; {{ $ticket->price }}</p>
        <p class="text-sm">Inklusive Gebühren</p>
    </div>
    <div>
        <p class="h3 text-right">{{ $ticket->id }}</p>
        <p class="text-right">{{ $ticket->order->id }}</p>
    </div>
</footer>
</body>
</html>
