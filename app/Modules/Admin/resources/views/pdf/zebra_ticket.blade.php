<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">

  <style>
    body {
      font-family: Verdana, Calibri, Arial, sans-serif;
    }
    h4 {
      margin-bottom: 5px;
    }
    p {
      margin: 5px 0;
      font-size: 13px;
    }

    .barcode-container {
      transform: rotate(90deg);
      margin-right: -80px;
      margin-left: -30px;
    }
    .qr-code {
      width: 50px;
      margin-left: 60px;
      margin-top: 50px;
    }
  </style>
</head>
  <body>
    <br><br>

    <table>
      <tbody>
        <tr>
          <td width="200"></td>
          <td>
            <img src="data:image/png;base64, {{ DNS2D::getBarcodePNG($ticket->barcode, "QRCODE") }}" alt="qr-code" class="qr-code">
          </td>
          <td  style="text-align:right">
            <p><b>{{ $ticket->id }}</b></p>
            <p>{{ $ticket->order->id }}</p>
          </td>
          <td rowspan="4" width="45">
            <div class="barcode-container">
              {!! DNS1D::getBarcodeHTML("$ticket->barcode", "C128C") !!}
            </div>
          </td>
        </tr>
        <tr>
          <td colspan="2">
            <h4>{{ $ticket->event->name }}</h4>
            <span>{{ cyr2lat($ticket->event->name) }}</span>
          </td>
          <td style="text-align:right; margin-left: 50px;">
            <p><b>{{ $ticket->event->date->format('d.m.Y') }}</b></p>
            <p><b>{{ $ticket->event->date->format('H:i') }}</b></p>
          </td>
        </tr>
        <tr>
          <td style="width: 250px;">
            <p><b>{{ $ticket->event->hall->building->name }}</b></p>
            <p>{{ $ticket->event->hall->building->address }}</p>
          </td>
          <td>
            <p><b>Mittelhochparkett Mitte</b></p>
            <p>Reihe: <b>{{ $ticket->place->row }}</b></p>
            <p>Platz:&nbsp; <b>{{ $ticket->place->num }}</b></p>
          </td>
          <td style="text-align:right; margin-left: 50px;">
            <p>
              <small>NormaIprels</small>
            </p>
            <p><b>{{ $ticket->price }}</b></p>
            <p>
              <small>Inklusive Gebühren</small>
            </p>
          </td>
        </tr>
      </tbody>
    </table>
  </body>
</html>
