@extends('Admin::layouts.master')

@section('page-header')

    @include('Admin::partials.page-header')

@endsection

@section('content')

    @include('Admin::order.partials.modals._modal_confirm_payment')
    @include('Admin::order.partials.modals._modal_change_order_status')
    @include('Admin::order.partials.modals._modal_change_shipping_status')

    <div class="box" style="overflow-x: scroll;">
        <div class="box-header with-border">
            <a href="{{ route(config('admin.route').'.orders.create') }}" class="btn btn-primary" data-style="zoom-in">
                <span class="ladda-label"><i class="fa fa-plus"></i>
                    {{ trans('Admin::admin.add-new', ['item' => trans('Admin::models.' . $menuRoute->singular_name)]) }}
                </span>
            </a>
        </div>

        <div class="box-body">
            <table id="datatable" class="table table-bordered table-hover">
                <thead>
                <tr class="text-sm">
                    <th class="no-sort" width="5%" style="text-align: center">
                        {!! Form::checkbox('delete_all',1,false,['class' => 'mass']) !!}
                    </th>
                    <th>№</th>
                    <th>{{ __('Operator') }}</th>
                    <th>{{ __('Order date') }}</th>
                    <th>{{ __('Expires') }}</th>
                    <th>{{ __('Payer') }}</th>
                    <th>{{ __('Date of pay') }}</th>
                    <th>{{ __('Client number') }}</th>
                    <th>{{ __('Client full name') }}</th>
                    <th>{{ __('Phone') }}</th>
                    <th>{{ __('Client type') }}</th>
                    <th>{{ __('Hall') }}</th>
                    <th>{{ __('Event') }}</th>
                    <th>{{ __('Amount of tickets') }}</th>
                    <th>{{ __('Price') }}</th>
                    <th>{{ __('Order status') }}</th>
                    <th>{{ __('Shipping status') }}</th>
                    <th>{{ __('Order type') }}</th>
                    <th>{{ __('Payment type') }}</th>
                    <th>{{ __('Comment') }}</th>
                    <th>{{ __('Actions') }}</th>
                </tr>
                </thead>

                <tbody>
                @foreach ($orders as $order)
                    <tr>
                        <td style="text-align: center">
                            {!! Form::checkbox('del-'.$order->id,1,false,['class' => 'single','data-id'=> $order->id]) !!}
                        </td>

                        <td>{{ $order->id }}</td>
                        <td>{{ $order->manager->fullname }}</td>
                        <td>{{ $order->created_at->format('d.m.Y H:i') }}</td>
                        <td>{{ $order->expired_at ? $order->expired_at->format('d.m.Y H:i') : '' }}</td>
                        <td>{{ $order->payer->fullname }}</td>
                        <td>{{ $order->paid_at ? $order->paid_at->format('d.m.Y H:i') : '' }}</td>
                        <td>{{ $order->user_id }}</td>
                        <td>{{ $order->user ? $order->user->fullname : ''}}</td>
                        <td>{{ $order->user ? $order->user->profile->phone : ''}}</td>
                        <td>{{ $order->user ? $order->user->profile->typeLabel : __('Anonymous') }}</td>
                        <td>{{ $order->tickets->first()->event->hall->name }}</td>
                        <td>{{ $order->tickets->first()->event->name }}</td>
                        <td>{{ $order->ticketsCount }}</td>
                        <td>{{ $order->total }}</td>
                        <td>
                            @if($order->status == \App\Models\Order::STATUS_CONFIRMED || $order->status == \App\Models\Order::STATUS_CANCELED)
                                <span class="text-sm">{{ $order->displayStatus }}</span>
                            @else
                                <button type="button"
                                        class="btn-link text-sm"
                                        data-order-id="{{ $order->id }}"
                                        data-url="{{ route('order.changeOrderStatus', ['id' => $order->id]) }}"
                                        data-toggle="modal"
                                        data-target="#changeOrderStatus">{{ $order->displayStatus }}</button>
                            @endif
                        </td>

                        <td>
                            <button type="button"
                                    class="btn-link text-sm"
                                    data-order-id="{{ $order->id }}"
                                    data-shipping-status="{{ $order->shipping_status }}"
                                    data-url="{{ route('order.changeShippingStatus', ['id' => $order->id]) }}"
                                    data-toggle="modal"
                                    data-target="#changeShippingStatus">{{ $order->displayShippingStatus }}</button>
                        </td>

                        <td>{{ $order->displayShippingType }}</td>
                        <td>
                            @if ($order->paymentMethod)
                                {{ $order->paymentMethod->name }}

                                @if($order->paymentMethod->is_delay() && ! $order->paid_at)
                                    <button type="button"
                                       class="btn-link text-sm"
                                       data-url="{{ route('order.confirmPayment', ['id' => $order->id]) }}"
                                       data-toggle="modal"
                                       data-target="#confirmPayment">Подтвердить оплату</button>
                                @endif
                            @endif
                        </td>
                        <td>{{ $order->comment }}</td>
                        <td>
                            <a href="{{ route(config('admin.route').'.orders.edit', [$order->id]) }}" class="btn btn-xs btn-default"><i class="fa fa-edit"></i> {{ trans('Admin::admin.users-index-edit') }}</a>
                            <a href="{{ route(config('admin.route').'.orders.destroy', [$order->id]) }}" class="btn btn-xs btn-default delete-button"><i class="fa fa-trash"></i> {{ trans('Admin::admin.users-index-delete') }}</a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        <div class="box-footer">
            <button class="btn btn-danger" id="delete">
                <span><i class="fa fa-trash"></i> {{ trans('Admin::templates.templates-view_index-delete_checked') }}</span>
            </button>
            {!! Form::open(['route' => config('admin.route').'.orders.massDelete', 'method' => 'post', 'id' => 'massDelete']) !!}
                <input type="hidden" id="send" name="toDelete">
            {!! Form::close() !!}
        </div>
    </div>

@endsection

@section('after_scripts')
    @include('Admin::partials.datatable-scripts')

    <script src="{{ asset('js/rsvp-3.1.0.min.js') }}"></script>
    <script src="{{ asset('js/sha-256.min.js') }}"></script>
    <script src="{{ asset('js/qz-tray.min.js') }}"></script>

    {{-- For Zebra printer --}}
    <script>
        var printerName = "ZTC-GK420d";

        {{-- TODO: need to add certificate --}}
        // qz.websocket.connect().then(function() {
        //     qz.printers.find(printerName).then(function(found) {
        //         console.log("Printer: " + found);
        //     });
        // });

        function print(){
            $.get({
                url: '{{ route('tickets.zebraPrint') }}',
                success: function(html) {
                    var config = qz.configs.create(printerName, {
                        orientation: 'landscape',
                        rotation: 180,
                        density: 600,
                        margins: {
                            top: 1.3,
                            left: 1
                        }
                    });

                    var data = [{
                        type: 'html',
                        format: 'plain',
                        data: html
                    }];

                    qz.print(config, data).catch(function(e) { console.error(e); });
                }
            });
        }

        // Change order status
        $('#changeOrderStatus').on('show.bs.modal', function (e) {
            var button = $(e.relatedTarget);
            var changeStatusUrl = button.data('url');

            $('#changeOrderStatusOrderId').text(button.data('order-id'));

            var status;
            var changeOrderStatusBtn = $('#changeOrderStatusBtn');
            var paymentMethodSelect = $('#paymentMethod');
            $('#orderStatus').change(function () {
                 status = $('option:selected', this).val();

                if (status === '{{ \App\Models\Order::STATUS_CONFIRMED }}') {
                    paymentMethodSelect.prop('disabled', false);
                } else {
                    paymentMethodSelect.prop('disabled', true).prop("selectedIndex", 0);
                }

                changeOrderStatusBtn.prop('disabled', false);
            });

            var paymentMethod;
            paymentMethodSelect.change(function () {
                paymentMethod = $('option:selected', this).val();
            });

            changeOrderStatusBtn.click(function () {
                var data = {
                    'status': status,
                    'payment_method_id': paymentMethod
                };

                $.post(changeStatusUrl, data, function () {
                    location.reload();
                });
            })
        }).on('hide.bs.modal', function () {
            $('#orderStatus').prop('selectedIndex', 0);
            $('#paymentMethod').prop('selectedIndex', 0).prop('disabled', true);
            $('#changeOrderStatusBtn').prop('disabled', true);
        });

        // Change shipping status
        $('#changeShippingStatus').on('show.bs.modal', function (e) {
            var button = $(e.relatedTarget);
            var shippingStatusSelect = $('#shippingStatus');
            var shippingStatus = button.data('shipping-status');
                shippingStatusSelect.val(shippingStatus);

            shippingStatusSelect.change(function() {
                shippingStatus = $(this).val();
            });

            var orderId = button.data('order-id');
            $('#changeShippingStatusOrderId').text(orderId);

            $('#shippingStatusBtn').click(function () {
                $.post(button.data('url'), {shipping_status: shippingStatus}, function () {
                    location.reload();
                });
            });
        });

        // Confirm payment
        $('#confirmPayment').on('show.bs.modal', function (e) {
            $('#confirmPaymentBtn').click(function () {
                $.post($(e.relatedTarget).data('url'), function () {
                    location.reload();
                });
            })
        });

    </script>
@endsection
