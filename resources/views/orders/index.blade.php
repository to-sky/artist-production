@extends('layouts.master')

@section('page-header')
    <section class="content-header">
        <h1>
            <span class="text-capitalize">{{ __('My Orders') }}</span>
        </h1>

        <ol class="breadcrumb">
            <li><a href="{{ route('order.index') }}">{{ __('My Orders') }}</a></li>
        </ol>

    </section>
@endsection

@section('content')
    {!! LinkMenu::make('menus.client', 'orders') !!}

    <!-- Order list -->
    <section class="ap_section">
        <div class="ap_section__heading">
            <h2 class="ap_section__title">{!! __('My Orders') !!}</h2>
        </div>
        <div class="ap_section__content">
            <div class="ap_table-container">
                <table class="ap_table ap_mobile ap_table--striped link_table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ __('Event name') }}</th>
                            <th>{{ __('Tickets num.') }}</th>
                            <th>{{ __('Order date') }}</th>
                            <th>{{ __('Payment date') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($orders as $order)
                        <tr class="link_row" data-url="{{ route('order.show', ['order' => $order]) }}">
                            <td>
                                <div class="ap_desktop_data">{{ $order->id }}</div>
                                <div class="ap_mobile_data">{{ $order->id }} ({{ $order->event_names }})</div>

                                <div class="ap_mobile_data">
                                    {{ __('Tickets num.') }}: {{ $order->tickets_count }}
                                </div>

                                <div class="ap_mobile_data">
                                    {{ $order->created_at }} / {{ $order->paid_at ?: '-' }}
                                </div>
                            </td>
                            <td>{{ $order->event_names }}</td>
                            <td>{{ $order->tickets_count }}</td>
                            <td>{{ $order->created_at }}</td>
                            <td>{{ $order->paid_at ?: '-' }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    @if($orders->hasPages())
        <section class="ap_section">
            <div class="ap_section__content">
                <div class="ap_pagination-container">
                    {{ $orders }}
                </div>
            </div>
        </section>
    @endif
@endsection