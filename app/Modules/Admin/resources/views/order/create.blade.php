@extends('Admin::layouts.master')

@section('page-header')
    @include('Admin::partials.page-header')
@endsection

@section('content')
    <!-- Clients modal -->
    <div class="modal fade" id="clientsModal" tabindex="-1" role="dialog" aria-labelledby="clientsModalLabel">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title" id="clientsModalLabel">{{ __('Admin::admin.Clients') }}</h4>
                </div>
                <div class="modal-body">
                    <table id="datatable" class="table table-bordered table-hover table-selected">
                        <thead>
                            <tr>
                                <th></th>
                                <th>id</th>
                                <th>{{ __('Name') }}</th>
                                <th>{{ __('Commission') }}</th>
                                <th>Email</th>
                                <th>{{ __('Phone') }}</th>
                                <th>{{ __('Type') }}</th>
                            </tr>
                        </thead>

                        <tbody>
                        @foreach($clients as $client)
                            <tr>
                                <td>
                                    <input type="radio" name="client" value="{{ $client->id }}">
                                </td>
                                <td>{{ $client->id }}</td>
                                <td data-type="name">{{ $client->full_name }}</td>
                                <td>{{ $client->commission }}%</td>
                                <td data-type="email">{{ $client->email }}</td>
                                <td  data-type="phone">{{ $client->phone }}</td>
                                <td>{{ $client->getTypeLabel($client->type) }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">{{ __('Close') }}</button>
                    <button type="button" id="getClient" class="btn btn-primary">{{ __('Select') }}</button>
                </div>
            </div>
        </div>
    </div>

    <div id="mainContent" class="row">
            <div class="col-md-6">
                @include('Admin::order.partials._events_filter')
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

                <div class="box">
                    <div>
                        <table class="table table-striped">
                            <tr>
                                <td>{{ __('Ticket') }}</td>
                                <td>{{ __('Price') }}</td>
                                <td>{{ __('Discount') }}</td>
                                <td>{{ __('Bonus') }}</td>
                                <td>{{ __('Cash collection') }}</td>
                                <td>{{ __('Final price') }}</td>
                                <td>{{ __('Admin::admin.delete') }}</td>
                            </tr>
                            <tr>
                                <td colspan="7" class="text-center">
                                    <small>{{ __(':items not selected', ['items' => __('Tickets')]) }}</small>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>

                <ul class="nav nav-tabs" role="tablist">
                    <li role="presentation" class="active">
                        <a href="#sale" aria-controls="sale" role="tab" data-toggle="tab">{{ __('Sale') }}</a>
                    </li>
                    <li role="presentation">
                        <a href="#realization" aria-controls="realization" role="tab" data-toggle="tab">{{ __('Under realization') }}</a>
                    </li>
                    <li role="presentation">
                        <a href="#reserve" aria-controls="reserve" role="tab" data-toggle="tab">{{ __('In reserve') }}</a>
                    </li>
                </ul>

                <div class="tab-content">
                    @include('Admin::partials.errors')

                    <div role="tabpanel" class="tab-pane active" id="sale">
                        @include('Admin::order.partials._sale')
                    </div>
                    <div role="tabpanel" class="tab-pane" id="realization">
                        @include('Admin::order.partials._realization')
                    </div>
                    <div role="tabpanel" class="tab-pane" id="reserve">
                        @include('Admin::order.partials._reserve')
                    </div>
                </div>
            </div>
    </div>
@endsection

@section('after_scripts')
    @include('Admin::partials.form-scripts')
    @include('Admin::partials.datatable-scripts')
    @include('Admin::order.partials._scripts')
@endsection
