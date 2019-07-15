@php

  $provisionalLink = route('invoice.download', ['order' => $order->id, 'tag' => 'provisional']);
  $finalLink = route('invoice.download', ['order' => $order->id, 'tag' => 'final']);

  $hasFinal = !!$order->paid_at;

@endphp

<section class="link-dropdown-wrap link_dropdown_wrap">
  <a href="{{ $provisionalLink }}" class="ap_order__action {{ $hasFinal ? 'link_dropdown_trigger' : '' }}">
    Скачать счет
  </a>

  @if($hasFinal)
    <ul class="link-dropdown link_dropdown">
      <li><a href="{{ $provisionalLink }}">{{ __('Provisional') }}</a></li>
      <li><a href="{{ $finalLink }}">{{ __('Final') }}</a></li>
    </ul>
  @endif
</section>