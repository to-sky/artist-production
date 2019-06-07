@extends('Admin::layouts.master')

@section('page-header')

    @include('Admin::partials.page-header')

@endsection

@section('content')

    <div class="row">

        <div class="col-md-8 col-md-offset-2">

            <a href="{{ route(config('admin.route').'.orders.index') }}"><i class="fa fa-angle-double-left"></i> {{ trans('Admin::admin.back-to-all-entries') }}</span></a><br><br>

            @include('Admin::partials.errors')

            {!! Form::open(array('route' => config('admin.route').'.orders.store')) !!}
            <div class="box">

                <div class="box-header with-border">
                    <h3 class="box-title">{{ trans('Admin::admin.add-new', ['item' => trans('Admin::models.' . $menuRoute->singular_name)]) }}</h3>
                </div>

                <div class="box-body row">
                    <div class="form-group col-md-12">
    {!! Form::label('status', 'Status') !!}
    @foreach (array_combine(explode(',', trim('0,1,2')), explode(',', trim('Pending,Confirmed,Canceled'))) as $value => $title)
        @php $checked = false @endphp
        @if ($value == $order->status)
            @php $checked = true @endphp
        @endif
        <div class="radio">
            <label>
                {!! Form::radio('status', $value, $checked) !!}
                {{ $title }}
            </label>
        </div>
    @endforeach
    
</div><div class="form-group col-md-12">
    {!! Form::label('subtotal', 'Subtotal') !!}
    {!! Form::text('subtotal', old('subtotal'), array('class'=>'form-control')) !!}
    
</div><div class="form-group col-md-12">
    {!! Form::label('$comment', 'Comment') !!}
    {!! Form::textarea('comment', old('comment'), array('class'=>'form-control ckeditor')) !!}
    
</div><div class="form-group col-md-12">
    {!! Form::label('shipping_status', 'Shipping status') !!}
    @foreach (array_combine(explode(',', trim('0,1,2,3')), explode(',', trim('In processing,Dispatched,Delivered,Returned'))) as $value => $title)
        @php $checked = false @endphp
        @if ($value == $order->shipping_status)
            @php $checked = true @endphp
        @endif
        <div class="radio">
            <label>
                {!! Form::radio('shipping_status', $value, $checked) !!}
                {{ $title }}
            </label>
        </div>
    @endforeach
    
</div><div class="form-group col-md-12">
    {!! Form::label('shipping_type', 'Shipping type') !!}
    @foreach (array_combine(explode(',', trim('0,1')), explode(',', trim('Shipping,Email'))) as $value => $title)
        @php $checked = false @endphp
        @if ($value == $order->shipping_type)
            @php $checked = true @endphp
        @endif
        <div class="radio">
            <label>
                {!! Form::radio('shipping_type', $value, $checked) !!}
                {{ $title }}
            </label>
        </div>
    @endforeach
    
</div><div class="form-group col-md-12">
    {!! Form::label('$shipping_comment', 'Shipping comment') !!}
    {!! Form::textarea('shipping_comment', old('shipping_comment'), array('class'=>'form-control ckeditor')) !!}
    
</div><div class="form-group col-md-12">
    {!! Form::label('shipping_price', 'Shipping price') !!}
    {!! Form::text('shipping_price', old('shipping_price'), array('class'=>'form-control')) !!}
    
</div><div class="form-group col-md-12">
    {!! Form::label('paid_at', 'Paid at') !!}
    {!! Form::text('$paid_at', old('paid_at'), array('class'=>'form-control datetimepicker')) !!}
    
</div>
                </div>

                <div class="box-footer">

                    @include('Admin::partials.save-buttons')

                </div>

            </div>

            {!! Form::close() !!}

        </div>

    </div>

@endsection

@section('after_scripts')

    @include('Admin::partials.form-scripts')

@endsection