<h4 class="media-heading">{{ __('All prices') }}</h4>
@foreach($prices as $statistic)
  <p class="prices-count">
    <span class="label" style="background: {{ $statistic['color'] }};" >
      {{ $statistic['price'] }} &euro;
    </span>
    <span class="text-sm text-muted">
      &nbsp;{{ $statistic['available'] }} {{ __('pc') }}.
    </span>
  </p>
@endforeach