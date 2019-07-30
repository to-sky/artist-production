@extends('Admin::layouts.master')

@section('page-header')

    @include('Admin::partials.page-header')

@endsection

@section('content')

    <div class="row">

        <div class="col-md-12">

            <a href="{{ route(config('admin.route').'.orders.index') }}">
                <i class="fa fa-angle-double-left"></i> {{ trans('Admin::admin.back-to-all-entries') }}</span>
            </a><br><br>

            @include('Admin::partials.errors')

            {!! Form::model($order, array('method' => 'PATCH', 'route' => array(config('admin.route').'.orders.update', $order->id))) !!}
            <input type="hidden" name="user_id" value="{{ $order->user_id }}">

            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">{{ trans('Admin::admin.edit-item', ['item' => trans('Admin::models.' . $menuRoute->singular_name)]) }}</h3>
                </div>

                <div class="box-body">
                    <div class="row">
                        <div class="col-md-3">
                            <h4>{{ __('Main information') }}</h4>
                            <div class="clearfix">
                                <div class="pull-left text-bold">{{ __('Number') }}:</div>
                                <div class="pull-right">{{ $order->id }}</div>
                            </div>
                            <div class="clearfix">
                                <div class="pull-left text-bold">{{ __('Status') }}:</div>
                                <div class="pull-right">{{ $order->displayStatus }}</div>
                            </div>
                            <div class="clearfix">
                                @if($order->status == \App\Models\Order::STATUS_CONFIRMED)
                                    <div class="pull-left text-bold">{{ __('Date of pay') }}:</div>
                                    <div class="pull-right">{{ $order->paid_at ? $order->paid_at->format('d.m.Y H:m') : ''}}</div>
                                @elseif($order->status == \App\Models\Order::STATUS_RESERVE)
                                    <div class="pull-left text-bold">{{ __('Valid until') }}:</div>
                                    <div class="pull-right">{{ $order->expired_at ? $order->expired_at->format('d.m.Y H:m') : ''}}</div>
                                @elseif($order->status == \App\Models\Order::STATUS_CANCELED)
                                    <div class="pull-left text-bold">{{ __('Valid until') }}:</div>
                                    <div class="pull-right">{{ $order->updated_at->format('d.m.Y H:m') }}</div>
                                @endif
                            </div>
                            <div class="clearfix">
                                <div class="pull-left text-bold">{{ __('Tickets price') }}:</div>
                                <div class="pull-right">{{ $order->subtotal }}</div>
                            </div>
                            <div class="clearfix">
                                <div class="pull-left text-bold">{{ __('Shipping price') }}:</div>
                                <div class="pull-right">{{ $order->shipping_price }}</div>
                            </div>
                            <div class="clearfix">
                                <div class="pull-left text-bold">{{ __('Service price') }}:</div>
                                <div class="pull-right">{{ $order->service_price }}</div>
                            </div>
                            <div class="clearfix">
                                <div class="pull-left text-bold">{{ __('Discount') }}:</div>
                                <div class="pull-right">{{ $order->compositeDiscount ?? '0' }}</div>
                            </div>
                            <div class="clearfix">
                                <div class="pull-left text-bold">{{ __('Paid in cash') }}:</div>
                                <div class="pull-right">{{ $order->paid_cash ?? '0'}}</div>
                            </div>
                            <div class="clearfix">
                                <div class="pull-left text-bold">{{ __('Main') }}:</div>
                                <div class="pull-right">{{ $order->total }}</div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <h4>{{ __('Shipping') }}</h4>

                            @if($order->status == \App\Models\Order::STATUS_RESERVE)
                                @include('Admin::order.partials._shipping_type_group')

                                @if($order->shipping_type == \App\Models\Shipping::TYPE_POST && $order->shippingAddress)

                                    <div id="shippingAddresses" class="form-group">
                                        <label>{{ __('Addresses') }}</label>
                                        <div class="radio">
                                            <label>
                                                <input type="radio" name="address_id"
                                                       value="{{ $order->shippingAddress->id }}"
                                                       checked="checked">{{ $order->shippingAddress->full }}
                                            </label>
                                        </div>
                                    </div>
                                @endif
                            @endif

                            <div class="clearfix">
                                <div class="pull-left text-bold">{{ __('Shipping price') }}:</div>
                                <div class="pull-right" id="shippingPrice">
                                    {{ $order->shipping_price ?? '0.00' }} &euro;
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <h4>{{ __('Payment') }}</h4>

                            <div class="clearfix">
                                <div class="pull-left text-bold">{{ __('Payment type') }}:</div>
                                <div class="pull-right">{{ $order->paymentMethod ? $order->paymentMethod->name : __('Evening ticket office')}}</div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <h4>{{ __('Comment') }}</h4>

                            <div>
                                <textarea name="comment" cols="30" rows="10"
                                          class="form-control">{{ $order->comment }}</textarea>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <td>№</td>
                            <td>{{ __('Barcode') }}</td>
                            <td>{{ __('Event') }}</td>
                            <td>{{ __('Hall') }}</td>
                            <td>{{ __('Place') }}</td>
                            <td>{{ __('Price') }}</td>
                            <td>{{ __('Paid') }}</td>
                            <td>{{ __('Discount') }}</td>
                            <td>{{ __('Printed') }}</td>
                            <td>{{ __('Actions') }}</td>
                        </tr>
                        </thead>
                        @forelse($order->tickets as $ticket)
                            <tr>
                                <td>{{ $ticket->id }}</td>
                                <td>{{ $ticket->barcode }}</td>
                                <td>{{ $ticket->event->name }}</td>
                                <td>{{ $ticket->event->hall->name }}</td>
                                <td>{{ $ticket->place->num }}</td>
                                <td>{{ $ticket->price }}</td>
                                <td>{{ $order->paid_at ? $ticket->price : '0.00' }}</td>
                                <td>{{ $ticket->discount }}</td>
                                <td>{{ $ticket->amount_printed }}</td>
                                <td>
                                    <input type="hidden" name="tickets[{{ $ticket->id }}][discount]"
                                           value="{{ $ticket->discount }}">
                                    <button type="button" class="btn btn-warning btn-xs print-ticket"
                                            onclick="print({{ $ticket->id }})">{{ __('Print') }}</button>
                                    <a href="{{ route('order.deleteTicket', ['order' => $order, 'ticket' => $ticket->id]) }}"
                                       type="button" class="btn btn-danger btn-xs delete-ticket">{{ __('Delete') }}</a>
                                </td>
                            </tr>
                        @empty
                            <td colspan="10" class="text-center">
                                <small>{{ __(':items not selected', ['items' => __('Tickets')]) }}</small>
                            </td>
                        @endforelse
                    </table>
                </div>

                <div class="box-footer">
                    @include('Admin::partials.save-buttons')
                    {!! Form::hidden('id', $order->id) !!}
                </div>
            </div>

            {!! Form::close() !!}

        </div>
    </div>

@endsection

@section('after_scripts')
    @include('Admin::partials.form-scripts')
    @include('Admin::order.partials._scripts_print')

    <script>
        $('#shippingType').change(function () {
            // Append/remove address block
            if ($('option:selected', this).val() === '{{ \App\Models\Shipping::TYPE_POST }}') {
                var userId = $('input[name="user_id"]').val();
                $('#shippingType').parent('div').after(createAddressBlock(userId));
            } else {
                $('#shippingAddresses').remove();
                $('#shippingPrice').html(0 + '&nbsp;&euro;')
            }
        });

        $('body').on('change', 'input[name="address_id"]', function () {
            var price = $(this).data('price');

            $('#shippingPrice').html(price + '&nbsp;&euro;')
        });

        // Create address block
        function createAddressBlock(user_id) {
            var addresses = $('<label>', {
                text: '{{ __('Addresses') }}'
            });
            $.get('{{ route('order.getAddresses') }}', {user_id: user_id}, function (data) {
                $.each(data, function (i, el) {
                    addresses.parent().append(
                        $('<div>', {class: 'radio'})
                            .append(
                                $('<label>')
                                    .append($('<input>', {
                                        type: 'radio',
                                        name: 'address_id',
                                        'data-price': el.shippingPrice,
                                        value: el.id,
                                        checked: i === 0
                                    })).append(el.address)
                            )
                    );
                    var shippingPrice = $('input[name="address_id"]:checked').data('price');
                    $('#shippingPrice').html(shippingPrice + '&nbsp;&euro;')
                });

            });

            return $('<div>', {
                id: 'shippingAddresses',
                class: 'form-group'
            }).append(addresses);
        }

        // Delete ticket
        $('.delete-ticket').click(function (e) {
            e.preventDefault();

            $.post($(this).attr('href'), function () {
                location.reload();
            });
        });
    </script>

@endsection
