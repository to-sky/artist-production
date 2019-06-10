@extends('Admin::layouts.master')

@section('page-header')

    @include('Admin::partials.page-header')

@endsection

@section('content')

    <div class="box">
        <div class="box-header with-border">
            <a href="{{ route(config('admin.route').'.orders.create') }}" class="btn btn-primary" data-style="zoom-in">
                <span class="ladda-label"><i class="fa fa-plus"></i> {{ trans('Admin::admin.add-new', ['item' => trans('Admin::models.' . $menuRoute->singular_name)]) }}</span>
            </a>
        </div>

        <div class="box-body">
            <table id="datatable" class="table table-bordered table-hover">
                <thead>
                <tr>
                    <th class="no-sort" width="5%" style="text-align: center">
                        {!! Form::checkbox('delete_all',1,false,['class' => 'mass']) !!}
                    </th>
                    <th>Status</th>
                    <th>Subtotal</th>
                    <th>Shipping status</th>
                    <th>Shipping type</th>
                    <th>Shipping price</th>
                    <th>Paid at</th>

                    <th>&nbsp;</th>
                </tr>
                </thead>

                <tbody>
                @foreach ($orders as $row)
                    <tr>
                        <td style="text-align: center">
                            {!! Form::checkbox('del-'.$row->id,1,false,['class' => 'single','data-id'=> $row->id]) !!}
                        </td>
                        <td>{{ $row->status }}</td>
                        <td>{{ $row->subtotal }}</td>
                        <td>{{ $row->shipping_status }}</td>
                        <td>{{ $row->shipping_type }}</td>
                        <td>{{ $row->shipping_price }}</td>
                        <td>{{ $row->paid_at }}</td>

                        <td>
                            <a href="{{ route(config('admin.route').'.orders.edit', [$row->id]) }}" class="btn btn-xs btn-default"><i class="fa fa-edit"></i> {{ trans('Admin::admin.users-index-edit') }}</a>
                            <a href="{{ route(config('admin.route').'.orders.destroy', [$row->id]) }}" class="btn btn-xs btn-default delete-button"><i class="fa fa-trash"></i> {{ trans('Admin::admin.users-index-delete') }}</a>
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
    </script>

@endsection
