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
            <table class="ap_table--striped o_table">
                <thead>
                    <th>#</th>
                    <th>date</th>
                    <th></th>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                        <tr data-url="{{ route('order.show', ['order' => $order]) }}">
                            <td>{{ $order->id }}</td>
                            <td>{{ $order->created_at }}</td>
                            <td></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>
@endsection

@section('after_scripts')
    <script>
        $orderRows = $('.o_table tr');
        $orderRows.click(function (e) {
          location.href = $(this).data('url');
        });
    </script>
@endsection