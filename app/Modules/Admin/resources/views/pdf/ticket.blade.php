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

            .barcode {
                -webkit-transform: rotateX(45deg);
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
                width: 80px;
            }
        </style>

        <header>
            <div>
                <img src="{{ asset('images/logo.png') }}" alt="{{ config('app.name', 'Artist Production') }}" class="logo">
            </div>

            <div class="client-block clearfix">
                <ul class="list-none float-left width-33">
                    <li>
                        <b>Kutuzova Inessa</b>
                    </li>
                    <li>Hartungstrasse 13</li>
                    <li>30419 Hannover</li>
                    <li>Germany</li>
                    <li>491638727790</li>
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
                                <td>601162374</td>
                            </tr>
                            <tr>
                                <td class="text-light text-right">Buchung:</td>
                                <td>01.04.2019</td>
                            </tr>
                        </table>
                    </div>
                </div>

                <div class="qr-block float-right" style="margin-top: -50px">
                    <img src="data:image/png;base64, {!! base64_encode(QrCode::format('png')->size(500)->generate($ticket->id))!!} ">
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
                                <img src="{{ asset('images/logo.png') }}" alt="{{ config('app.name', 'Artist Production') }}" class="logo">
                            </div>
                        </td>
                        <td>
                            <div class="text-m logo-text">
                                <!--TODO: add free pass logo image-->
                            </div>
                        </td>
                        <td class="text-right">
                            <p style="margin-top: -50px;">
                                <b>601162374</b>
                                <span>34556567</span>
                            </p>
                        </td>
                        <td width="55" class="text-center">
                            <div class="qr-block text-right">
                                <img src="{{ asset('images/barcode.jpg') }}" class="barcode">
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" style="padding-bottom: 10px">
                            <h1 class="event-name">Фестиваль Авторадио в Германии4 nen nene</h1>
                            <p class="text-italic">{{ cyr2lat('Фестиваль Авторадио в Германии') }}</p>
                        </td>
                        <td class="text-right text-m">
                            <p><b>26.05.2019</b></p>
                            <p><b>19:00</b></p>
                        </td>
                    </tr>


                    <tr>
                        <td>
                            <p><b>Stadthalle Braunschweig</b></p>
                            <p>Leonhardplatz 1, 38102 Braunschweig</p>
                        </td>

                        <td>
                            <p><b>Mittelhochparkett Mitte</b></p>
                            <p>Reihe: <b>1</b></p>
                            <p>Platz: <b>1</b></p>
                        </td>

                        <td class="text-right" width="80">
                            <p class="text-sm text-light">Normalpreis</p>
                            <p class="text-lg"><b>&euro; 129,00</b></p>
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
                            коммерческие условия (https://artist-production.de/datenschutz).
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
                        <b>Artist-production.de</b>
                    </p>
                </div>
                <div class="width-50 float-left">
                    <p>
                        <b>ANWENDUNGSHINWEISE:</b>
                    </p>
                    <ul>
                        <li>Mit dem Erwerb dieses Tickets akzeptieren Sie unsere AGB,
                            die Sie unter (https://artist-production.de/datenschutz) finden.
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
                        <b>Artist-production.de</b>
                    </p>
                </div>
            </div>
        </main>

        <div class="lb"></div>
        <br><br><br>

        <footer class="clearfix">
            <div class="qr-block width-50 float-left">
                <img src="data:image/png;base64, {!! base64_encode(QrCode::format('png')->size(500)->generate($ticket->id))!!} ">
            </div>
            <div class="float-left">
                <p class="h3">&euro; 129,00</p>
                <p class="text-sm">Inklusive Gebühren</p>
            </div>
            <div>
                <p class="h3 text-right">601162464</p>
                <p class="text-right">601162464</p>
            </div>
        </footer>
    </body>
</html>

