@extends('Admin::layouts.master')

@section('page-header')
    @include('Admin::partials.page-header')
@endsection

@section('content')
    @include('Admin::order.partials.modals._modal_clients')
    @include('Admin::order.partials.modals._modal_sale')
    @include('Admin::order.partials.modals._modal_realization')
    @include('Admin::order.partials.modals._modal_reserve')
    @include('Admin::order.partials.modals._modal_discount')

    <div id="mainContent" class="row">
            <div class="col-md-6">
                @include('Admin::order.partials._events_block')
            </div>

            <div class="col-md-6">
                <div class="box">
                    <div id="clientData" class="box-header with-border">
                        <small>{{ __(':item not selected', ['item' => __('Admin::models.Client')]) }}</small>
                    </div>

                    <div class="box-body">
                        <button type="button"
                                class="btn btn-xs btn-default"
                                data-toggle="modal"
                                data-target="#clientsModal">
                            {{ __('Select :item', ['item' => __('Admin::models.client')]) }}
                        </button>
                    </div>
                </div>

                @include('Admin::order.partials._tickets_table')

                <div class="btn-group">
                    <button type="button" class="btn btn-success">{{ __('Create order') }}</button>
                    <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu">
                        <li><a href="#" data-toggle="modal" data-target="#saleModal">{{ __('Sale') }}</a></li>
                        <li><a href="#" data-toggle="modal" data-target="#realizationModal">{{ __('Under realization') }}</a></li>
                        <li><a href="#" data-toggle="modal" data-target="#reserveModal">{{ __('In reserve') }}</a></li>
                    </ul>
                </div>
            </div>
    </div>
@endsection

@section('after_scripts')
    @include('Admin::partials.form-scripts')
    @include('Admin::partials.datatable-scripts')
    @include('Admin::order.partials._scripts')
@endsection
