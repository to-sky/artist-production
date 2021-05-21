<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8" />
    <title>{{ __('Unsold tickets') }}: {{ $fullEventName }}</title>

    <link rel="stylesheet" href="{{ asset('css/reports.css') }}">
  </head>

  <body>
    <section class="unsold-tickets-report">
      <div class="date">
        {{ __('Report composed at') }}
        {{ \Carbon\Carbon::now()->format('d.m.Y H:i') }}
      </div>

      <div class="print-wrap">
        <button id="print">{{ __('Print') }}</button>
      </div>

      <div class="table">
        <div>{{ __('Tickets left') }}</div>
        <div>{{ $fullEventName }}</div>

        {!! $table !!}
      </div>
    </section>
  </body>

  <script>
    var printButton = document.querySelector('#print');

    printButton && printButton.addEventListener('click', function (e) {
      window.print();
    });
  </script>
</html>