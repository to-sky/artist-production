@extends('Admin::layouts.master')

@section('page-header')

  @include('Admin::partials.page-header')

@endsection

@section('content')

  <div class="box">
    <div class="box-header with-border">
      <div class="row">
        <div class="col-lg-3 col-md-6 col-ms-12">
          <div class="form-group">
            <input type="text" class="form-control t_scan_code">
          </div>
        </div>

        <div class="col-lg-2 col-md-3 col-sm-12">
          <div class="form-group">
            <button type="button" class="btn btn-primary btn-block t_scan_trigger" data-style="zoom-in">
              <span class="ladda-label">
                <i class="fa fa-plus"></i>
                {{ __('Add manually') }}
              </span>
            </button>
          </div>
        </div>
      </div>
    </div>
    <div class="box-body">
      <form action="{{ route(config('admin.route') . '.tickets.doReturn') }}" method="POST" class="t_free_tickets">
        <table id="datatable" class="table table-bordered table-hover t_return">
          <thead>
          <tr>
            <th class="no-sort">{{ __('Barcode') }}</th>
            <th class="no-sort">{{ __('Ticket') }}</th>
            <th class="no-sort">{{ __('Event') }}</th>
            <th class="no-sort">{{ __('Hall') }}</th>
            <th class="no-sort">{{ __('Bookkeeper') }}</th>
            <th class="no-sort">{{ __('Sale Date') }}</th>
            <th class="no-sort">{{ __('Ticket price') }}</th>
            <th class="no-sort">{{ __('Discount') }}</th>
            <th class="no-sort">{{ __('Commission') }}</th>
            <th class="no-sort">{{ __('Return amount') }}</th>
            <th class="no-sort"></th>
          </tr>
          </thead>

          <tbody class="template" style="display: none">
          <tr>
            <td>%barcode%</td>
            <td>%ticket_pos%</td>
            <td>%event_name%</td>
            <td>%hall_name%</td>
            <td>%bookkeeper%</td>
            <td>%sale_date%</td>
            <td>%ticket_price%</td>
            <td>%ticket_discount%</td>
            <td>%commission%</td>
            <td>%return_amount%</td>
            <td data-id="%ticket_id%">
              <input type="hidden" name="ticket_id[]" value="%ticket_id%">
              <button class="btn btn-sm btn-icon r_ticket" data-id="%ticket_id%">
                <i class="fa fa-times"></i>
              </button>
            </td>
          </tr>
          </tbody>

          <tbody class="data">

          </tbody>
        </table>

        <button class="btn btn-primary t_refund" disabled>{{ __('Make refund') }}</button>
      </form>
    </div>
  </div>

@endsection

@section('after_scripts')

  @include('Admin::partials.return-scripts')

@endsection