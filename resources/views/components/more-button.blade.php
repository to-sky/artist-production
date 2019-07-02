@php

  // Get only future events
  $events = $order->events->where('can_order', 1);
  $multiple = $events->count() > 1;
  $firstEvent = $events->first();

@endphp
@if($multiple <= 0)
  <section class="more-button-wrap link_dropdown_wrap">
    <a
        href="{{ route('hallWidget', ['id' => $firstEvent->id]) }}#lang:{{ app()->getLocale() }}"
        class="ap_booked__more {{ $multiple ? 'link_dropdown_trigger' : '' }}"
    >
      Заказать еще
    </a>

    <ul class="link-dropdown link_dropdown">
      @foreach($events as $event)
        @if($event->can_order)
          <li>
            <a href="{{ route('hallWidget', ['id' => $event->id]) }}">{{ $event->name }}</a>
          </li>
        @endif
      @endforeach
    </ul>
  </section>
@endif