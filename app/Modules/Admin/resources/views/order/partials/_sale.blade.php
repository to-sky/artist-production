{!! Form::open(array('route' => config('admin.route').'.orders.store')) !!}
<div class="box">
    <div class="box-body">
        <small>{{ __('Select :items', ['items' => __('tickets')]) }}</small>
    </div>

    <div class="box-footer">
        @include('Admin::partials.save-buttons')
    </div>
</div>
{!! Form::close() !!}
