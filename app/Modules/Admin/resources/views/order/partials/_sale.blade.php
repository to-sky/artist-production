{!! Form::open(array('route' => config('admin.route').'.orders.store')) !!}
<div class="box">
    <div class="box-body">
        <small>Выберите билеты</small>
    </div>

    <div class="box-footer">
        @include('Admin::partials.save-buttons')
    </div>
</div>
{!! Form::close() !!}
