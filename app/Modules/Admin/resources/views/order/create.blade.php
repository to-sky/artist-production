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

            <div class="box" id="ticketsTableWrapper">
                @include('Admin::order.partials._tickets_table')
            </div>

            <div class="alert alert-warning a_realization" style="display: none;">
                {{ __('Imported event\'s tickets can\'t be send to realization') }}
            </div>

            <button type="button" class="btn btn-google" data-toggle="modal"
                    data-target="#saleModal">{{ __('Sale') }}</button>

            <button type="button" class="btn btn-dropbox" data-toggle="modal"
                    data-target="#realizationModal">{{ __('Under realization') }}</button>

            <button type="button" class="btn btn-success" data-toggle="modal"
                    data-target="#reserveModal">{{ __('In reserve') }}</button>
        </div>
    </div>
@endsection

@section('after_scripts')
    @include('Admin::partials.form-scripts')
    @include('Admin::partials.datatable-scripts')
    @include('Admin::order.partials._scripts')
@endsection
