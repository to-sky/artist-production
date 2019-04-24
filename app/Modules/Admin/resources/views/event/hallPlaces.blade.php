@extends('Admin::layouts.master')

@section('page-header')
  @include('Admin::partials.page-header')
@stop

@section('content')
    <div class="box">
        <div class="box-body">
            <iframe class="hall-widget-frame" src="{{ route('hallWidget', [$event->id, 'setup']) }}" frameborder="0"></iframe>
        </div>

        <div class="box-footer">

        </div>
    </div>
@stop