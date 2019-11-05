@extends('Admin::layouts.master')

@section('page-header')
    <section class="content-header">
        <h1>
            <span class="text-capitalize">{{ trans('Admin::admin.Dashboard') }}</span>
        </h1>

        <ol class="breadcrumb">
            <li><a href="{{ url('dashboard') }}">{{ trans('Admin::admin.partials-header-title') }}</a></li>
        </ol>

    </section>
@endsection

@section('content')

    <div class="box">

        <div class="box-body">

            {{ trans('Admin::admin.dashboard-title') }}

        </div>

    </div>

@endsection