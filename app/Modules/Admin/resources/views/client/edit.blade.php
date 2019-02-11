@extends('Admin::layouts.master')

@section('page-header')

    @include('Admin::partials.page-header')

@endsection

@section('content')

    <div class="row">

        <div class="col-md-8 col-md-offset-2">

            <a href="{{ route(config('admin.route').'.clients.index') }}"><i class="fa fa-angle-double-left"></i> {{ trans('Admin::admin.back-to-all-entries') }}</a><br><br>

            @include('Admin::partials.errors')

            {!! Form::model($client, array('method' => 'PATCH', 'route' => array(config('admin.route').'.clients.update', $client->id))) !!}

            <div class="box">

                <div class="box-header with-border">
                    <h3 class="box-title">{{ trans('Admin::admin.edit-item', ['item' => trans('Admin::models.' . $menu->singular_name)]) }}</h3>
                </div>

                <div class="box-body row">
                    <div class="form-group col-md-12">
                        {!! Form::label('first_name', 'First name*') !!}
                        {!! Form::text('first_name', old('first_name', $client->first_name), array('class'=>'form-control')) !!}

                    </div>
                    <div class="form-group col-md-12">
                        {!! Form::label('last_name', 'Last name*') !!}
                        {!! Form::text('last_name', old('last_name', $client->last_name), array('class'=>'form-control')) !!}

                    </div>
                    <div class="form-group col-md-12">
                        {!! Form::label('email', 'Email*') !!}
                        {!! Form::text('email', old('email', $client->email), array('class'=>'form-control')) !!}

                    </div>
                    <div class="form-group col-md-12">
                        {!! Form::label('phone', 'Phone') !!}
                        {!! Form::text('phone', old('phone', $client->phone), array('class'=>'form-control')) !!}

                    </div>
                    <div class="form-group col-md-12">
                        {!! Form::label('street', 'Street') !!}
                        {!! Form::text('street', old('street', $client->street), array('class'=>'form-control')) !!}

                    </div>
                    <div class="form-group col-md-12">
                        {!! Form::label('house', 'House') !!}
                        {!! Form::text('house', old('house', $client->house), array('class'=>'form-control')) !!}

                    </div>
                    <div class="form-group col-md-12">
                        {!! Form::label('city', 'City') !!}
                        {!! Form::text('city', old('city', $client->city), array('class'=>'form-control')) !!}

                    </div>
                    <div class="form-group col-md-12">
                        {!! Form::label('post_code', 'Post code') !!}
                        {!! Form::text('post_code', old('post_code', $client->post_code), array('class'=>'form-control')) !!}

                    </div>
                    <div class="form-group col-md-12">
                        {!! Form::label('comission', 'Comission') !!}
                        {!! Form::text('comission', old('comission', $client->comisson), array('class'=>'form-control')) !!}

                    </div>
                    <div class="form-group col-md-12">
                        {!! Form::label('code', 'Code') !!}
                        {!! Form::text('code', old('code', $client->code), array('class'=>'form-control')) !!}

                    </div>
                    <div class="form-group col-md-12">
                        {!! Form::label('comment', 'Comment') !!}
                        {!! Form::textarea('comment', old('comment', $client->comment), array('class'=>'form-control')) !!}

                    </div>
                </div>

                <div class="box-footer">

                    @include('Admin::partials.save-buttons')

                    {!! Form::hidden('id', $client->id) !!}

                </div>

            </div>

            {!! Form::close() !!}

        </div>

    </div>

@endsection

@section('after_scripts')

    @include('Admin::partials.form-scripts')

@endsection