@extends('Admin::layouts.master')

@section('page-header')

    @include('Admin::partials.page-header')

@endsection

@section('content')

    <div class="row">

        <div class="col-md-8 col-md-offset-2">

            <a href="{{ route(config('admin.route').'.events.index') }}"><i class="fa fa-angle-double-left"></i> Back to all <span>{{ $menu->plural_name }}</span></a><br><br>

            @include('Admin::partials.errors')

            {!! Form::model($event, array('method' => 'PATCH', 'route' => array(config('admin.route').'.events.update', $event->id))) !!}

            <div class="box">

                <div class="box-header with-border">
                    <h3 class="box-title">Edit {{ $menu->singular_name }}</h3>
                </div>

                <div class="box-body row">
                    <div class="form-group col-md-12">
    {!! Form::label('name', 'Name*') !!}
    {!! Form::text('name', old('name', $event->name), array('class'=>'form-control')) !!}

</div><div class="form-group col-md-12">
    {!! Form::label('date', 'Date*') !!}
    {!! Form::text('$date', old('date',$event->date), array('class'=>'form-control datetimepicker')) !!}
    <p class="help-block">Select event date and time</p>
</div><div class="form-group col-md-12">
    {!! Form::label('users_id', 'City*') !!}
    {!! Form::select('users_id', $users, old('users_id',$event->users_id), array('class'=>'form-control')) !!}

</div><div class="form-group col-md-12">
    {!! Form::label('users_id', 'Building*') !!}
    {!! Form::select('users_id', $users, old('users_id',$event->users_id), array('class'=>'form-control')) !!}

</div><div class="form-group col-md-12">
    {!! Form::label('users_id', 'Hall*') !!}
    {!! Form::select('users_id', $users, old('users_id',$event->users_id), array('class'=>'form-control')) !!}

</div><div class="form-group col-md-12">
    {!! Form::label('ticket_refund_period', 'Ticket refund period') !!}
    {!! Form::text('ticket_refund_period', old('ticket_refund_period', $ticket_refund_period), array('class'=>'form-control')) !!}

</div>
                </div>

                <div class="box-footer">

                    @include('Admin::partials.save-buttons')

                    {!! Form::hidden('id', $event->id) !!}

                </div>

            </div>

            {!! Form::close() !!}

        </div>

    </div>

@endsection

@section('after_scripts')

    @include('Admin::partials.form-scripts')

@endsection
