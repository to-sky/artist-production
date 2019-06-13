@extends('layouts.master')

@section('page-header')
    <section class="content-header">
        <h1>
            <span class="text-capitalize">{{ __('Addresses') }}</span>
        </h1>

        <ol class="breadcrumb">
            <li><a href="{{ route('order.index') }}">{{ __('Addresses') }}</a></li>
        </ol>

    </section>
@endsection

@section('content')
    {!! LinkMenu::make('menus.client', 'addresses') !!}

    <!-- Address list -->
    <section class="ap_section">
        <div class="ap_section__heading">
            <h2 class="ap_section__title">{!! __('Addresses') !!}</h2>
        </div>
        <div class="ap_section__content">
            <table class="ap_table--striped a_table">
                <thead>
                    <th>Name</th>
                    <th>Country</th>
                    <th>City</th>
                    <th>Post code</th>
                    <th>Building</th>
                    <th></th>
                </thead>
                <tbody>
                @foreach($addresses as $address)
                    <tr data-url="{{ route('address.show', ['address' => $address]) }}">
                        <td>{{ $address->full_name }}</td>
                        <td>{{ $address->country->name }}</td>
                        <td>{{ $address->city }}</td>
                        <td>{{ $address->post_code }}</td>
                        <td>{{ $address->building_name }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </section>
@endsection

@section('after_scripts')
    <script>
      $orderRows = $('.a_table tr');
      $orderRows.click(function (e) {
        location.href = $(this).data('url');
      });
    </script>
@endsection