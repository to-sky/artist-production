@extends('layouts.master')

@section('page-header')
  <section class="content-header">
    <h1>
      <span class="text-capitalize">{{ __('Profile') }}</span>
    </h1>

    <ol class="breadcrumb">
      <li><a href="{{ route('profile.show') }}">{{ __('Profile') }}</a></li>
    </ol>

  </section>
@endsection

@section('content')
  <main class="ap_order">

    <div class="ap_headline">
      <a href="{{ config('redirects.checkout_back') }}" class="ap_backlink">{{ __('Back') }}</a>
      <p class="ap_headline-text"><span class="ap_success">{{ __("Congratulations") }}!</span> {{ __('Your order successfully placed') }}</p>
    </div>

    <div class="ap_order__info">
      <div class="ap_order__col1">
        <p>{{ __('Order number') }}</p>
        @if(empty($order->paid_at))<p>{{ __('Reserved until') }}</p>@endif
      </div>
      <div class="ap_order__col2">
        <p>{{ $order->id }}</p>
        @if(empty($order->paid_at))<p>{{ $order->getReservationDate() }}</p>@endif
      </div>
      <div class="ap_order__col3">
        <p>{{ __('Within a couple of minutes you will receive a notification by e-mail with the confirmation of your order and the reservation number') }}</p>
        <div class="ap_order__actions">
          @component('components.invoice-print-button')
            @slot('order', $order)
          @endcomponent
          @component('components.invoice-download-button')
            @slot('order', $order)
          @endcomponent
        </div>
      </div>
    </div>

    <div class="ap_booked">
      <h3 class="ap_booked__title">{{ _('Reserved tickets') }}</h3>
      <div class="ap_booked__table-container">
        <table class="ap_booked__table">
          <tr>
            <th>{{ __('Event') }}</th>
            <th>{{ __('Ticket type') }}</th>
            <th>{{ __('Row and place') }}</th>
            <th>{{ __('Price') }}</th>
            <th>{{ __('Service fee') }}</th>
            <th>{{ __('To pay') }}</th>
            <th>{{ __('Date') }}</th>
          </tr>
        @foreach($order->tickets as $ticket)
            <tr>
              <td>{{ $ticket->event->name }}</td>
              <td>{{ $ticket->is_sitting_place ? __('Sitting place') : __('Standing place') }}</td>
              <td>
                @if($ticket->is_sitting_place)
                  <span class="ap_text--alpha-gray">{!! __('Seat') !!}</span> {{ $ticket->place->num }}
                  <br>
                  <span class="ap_text--alpha-gray">{!! __('Row') !!}</span> {{ $ticket->place->row }}
                @else
                  —
                @endif
              </td>
              <td>{{ sprintf('%1.2f', $ticket->getBuyablePrice()) }} EUR</td>
              <td>0.00 EUR</td>
              <td>{{ sprintf('%1.2f', $ticket->getBuyablePrice()) }} EUR</td>
              <td>{{ $ticket->event->date->format('d.m.Y') }}<br> {{ $ticket->event->date->format('H:i') }}</td>
            </tr>
          @endforeach
        </table>
      </div>
    </div>

    <div class="ap_summary">
      <div class="ap_booked__col1">
        <p>{{ __('Service fee') }}:</p>
        <p>{{ __('Delivery price') }}:</p>
      </div>
      <div class="ap_booked__col2">
        <p>{{ sprintf('%1.2f', $order->service_price) }} EUR</p>
        <p>{{ sprintf('%1.2f', $order->shipping_price) }} EUR</p>
      </div>
      <div class="ap_booked__col3">
        <p>{{ __('ORDER TOTAL') }}</p>
        @component('components.more-button')
          @slot('order', $order)
        @endcomponent
      </div>
      <div class="ap_booked__col4">
        <p>{{ sprintf('%1.2f', $order->total) }} EUR</p>
      </div>
    </div>

  </main>
@endsection