@php

  $provisionalLink = route('invoice.print', ['order' => $order->id, 'tag' => 'provisional']);
  $finalLink = route('invoice.print', ['order' => $order->id, 'tag' => 'final']);

  $hasFinal = !!$order->paid_at;

@endphp

<section class="link-dropdown-wrap link_dropdown_wrap">
  <a href="{{ $provisionalLink }}" class="ap_order__action btn-print {{ $hasFinal ? 'link_dropdown_trigger' : 'print_link' }}">
    Распечатать счет
  </a>

  @if($hasFinal)
    <ul class="link-dropdown link_dropdown">
      <li><a href="{{ $provisionalLink }}" class="print_link">{{ __('Provisional') }}</a></li>
      <li><a href="{{ $finalLink }}" class="print_link">{{ __('Final') }}</a></li>
    </ul>
  @endif
</section>