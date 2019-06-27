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
                <table class="ap_table ap_table--striped link_table">
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
                            <td>{{ $order->id }}</td>
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
@endsection