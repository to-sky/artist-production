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
      <a href="#" class="ap_backlink">Назад</a>
      <p class="ap_headline-text"><span class="ap_success">Поздравляем!</span> Заказ успешно оформлен</p>
    </div>

    <div class="ap_order__info">
      <div class="ap_order__col1">
        <p>Номер вашего заказа</p>
        @if(empty($order->paid_at))<p>Бронь действительна до</p>@endif
      </div>
      <div class="ap_order__col2">
        <p>{{ $order->id }}</p>
        @if(empty($order->paid_at))<p>14 мая 2019 12:42</p>@endif
      </div>
      <div class="ap_order__col3">
        <p>В течении пары минут вам придет уведомление на E-mail с подтверждением вашего заказа и номером резерва </p>
        <div class="ap_order__actions">
          <button class="ap_order__action btn-print">Распечатать счет</button>
          <button class="ap_order__action">Скачать счет</button>
        </div>
      </div>
    </div>

    <div class="ap_booked">
      <h3 class="ap_booked__title">Забронированные билеты</h3>
      <table class="ap_booked__table">
        <tr>
          <th>Мероприятие</th>
          <th>Тип билета</th>
          <th>Место и ряд</th>
          <th>Цена</th>
          <th>Сервисный сбор</th>
          <th>К оплате </th>
          <th>Дата</th>
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

    <div class="ap_summary">
      <div class="ap_booked__col1">
        <p>Сервисный сбор:</p>
        <p>Стоимость доставки:</p>
      </div>
      <div class="ap_booked__col2">
        <p>0.00 EUR</p>
        <p>{{ sprintf('%1.2f', $order->shipping_price) }} EUR</p>
      </div>
      <div class="ap_booked__col3">
        <p>ОБЩАЯ СУММА ЗАКАЗА</p>
        <a href="{{ route('hallWidget', ['id' => $order->event->id]) }}#lang:{{ app()->getLocale() }}" class="ap_booked__more">
          Заказать еще
        </a>
      </div>
      <div class="ap_booked__col4">
        <p>{{ sprintf('%1.2f', $order->final_price) }} EUR</p>
      </div>
    </div>

  </main>
@endsection