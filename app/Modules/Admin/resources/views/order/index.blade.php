@extends('Admin::layouts.master')

@section('page-header')

    @include('Admin::partials.page-header')

@endsection

@section('content')

    @include('Admin::order.partials.modals._modal_confirm_payment')
    @include('Admin::order.partials.modals._modal_change_order_status')
    @include('Admin::order.partials.modals._modal_change_shipping_status')
    @include('Admin::order.partials.modals._modal_comment')

    <div class="box">
        <div class="box-header with-border">
            <a href="{{ route(config('admin.route').'.orders.create') }}" class="btn btn-primary" data-style="zoom-in">
                <span class="ladda-label"><i class="fa fa-plus"></i>
                    {{ trans('Admin::admin.add-new', ['item' => trans('Admin::models.' . $menuRoute->singular_name)]) }}
                </span>
            </a>
        </div>

        <div class="box-body padding-0">
            <div class="col-12 col-sm-12 col-lg-12">
                <div class="table-responsive">
                    <table id="datatable" class="table table-bordered table-hover order-table">
                        <thead>
                        <tr class="text-sm">
                            <th>
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
                            <th>{{ __('Tickets') }}</th>
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

                                <td>
                                    <a href="{{ route(config('admin.route').'.orders.edit', [$order->id]) }}">
                                        {{ $order->id }}
                                    </a>
                                </td>
                                <td>{{ $order->manager->fullname ?? '-' }}</td>
                                <td>{{ $order->created_at->format('d.m.Y H:i') }}</td>
                                <td>{{ $order->expired_at ? $order->expired_at->format('d.m.Y H:i') : '' }}</td>
                                <td>{{ $order->payer->fullname ?? '' }}</td>
                                <td>{{ $order->paid_at ? $order->paid_at->format('d.m.Y H:i') : '' }}</td>
                                <td>{{ $order->user->display_id ?? '' }}</td>
                                <td>{{ $order->user->fullname ?? ''}}</td>
                                <td>{{ $order->user->profile->phone ?? ''}}</td>
                                <td>{{ $order->user->profile->typeLabel ?? __('Anonymous') }}</td>
                                <td>{{ $order->hallName }}</td>
                                <td>{{ $order->eventNames }}</td>
                                <td>{{ $order->ticketsCount }}</td>
                                <td>{{ $order->total }}</td>
                                <td>
                                    @if($order->status == \App\Models\Order::STATUS_CONFIRMED
                                        || $order->status == \App\Models\Order::STATUS_CANCELED)
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
                                               data-target="#confirmPayment">{{ __('Confirm payment') }}</button>
                                        @endif
                                    @else
                                        {{ __('Evening ticket office') }}
                                    @endif
                                </td>
                                <td>
                                    @if(strlen($order->comment) > 20)
                                        <button type="button"
                                                class="btn-link"
                                                data-toggle="modal"
                                                data-target="#modalComment"
                                                data-comment="{{ $order->comment }}">
                                            {{ substr($order->comment, 0, 15) }}...
                                        </button>
                                    @else
                                        {{ $order->comment }}
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group-vertical" role="group">
                                        <a href="{{ route('invoice.modal', ['id' => $order->id]) }}"
                                                class="btn btn-xs btn-default text-left invoice-modal">
                                            <i class="fa fa-file-text-o text-green"></i> {{ __('Download invoice') }}
                                        </a>
                                        <a href="{{ route(config('admin.route').'.orders.edit', [$order->id]) }}"
                                           class="btn btn-xs btn-default text-left">
                                            <i class="fa fa-edit text-primary"></i> {{ trans('Admin::admin.users-index-edit') }}
                                        </a>
                                        <a href="{{ route(config('admin.route').'.orders.destroy', [$order->id]) }}"
                                           class="btn btn-xs btn-default delete-button text-left">
                                            <i class="fa fa-trash text-yellow"></i> {{ trans('Admin::admin.users-index-delete') }}
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="box-footer">
            <button class="btn btn-danger" id="delete">
                <span>
                    <i class="fa fa-trash"></i> {{ trans('Admin::templates.templates-view_index-delete_checked') }}
                </span>
            </button>
            {!! Form::open(['route' => config('admin.route').'.orders.massDelete', 'method' => 'post', 'id' => 'massDelete']) !!}
                <input type="hidden" id="send" name="toDelete">
            {!! Form::close() !!}
        </div>
    </div>

@endsection

@section('after_scripts')
    @include('Admin::partials.datatable-scripts')
    @include('Admin::order.partials._scripts_print')
    <script>
        // Highlight table row
        var activeRow = false;
        var selectedRow;
        $('#datatable tbody tr').click(function () {
            // check if row is selected and set active row
            if (! activeRow) {
                activeRow = true;
                selectedRow = $(this);
                $(this).css('background', '#eaf3ff');
            } else {
                // unselect row after second click
                if ($(selectedRow).is($(this))) {
                    $(this).removeAttr('style');
                    selectedRow = $(this);
                    activeRow = false;
                } else {
                    selectedRow = $(this);
                    $(this).css('background', '#eaf3ff');
                }
            }

            $(this).siblings('tr').removeAttr('style');
        });

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

        // Append full comment to modal
        $('#modalComment').on('show.bs.modal', function (e) {
            var comment = $(e.relatedTarget).data('comment');

            $('.modal-body', this).html(comment);
        });

        // Invoices modal
        $('.invoice-modal').click(function(e) {
            e.preventDefault();

            $.get($(this).attr('href'), function (data) {
                $('body').append(data);
                $('#modalInvoice').modal('show');
            })
        });

        // Remove modal after close
        $('body').on('hidden.bs.modal', '#modalInvoice', function () {
            $(this).remove();
        });
    </script>
@endsection
