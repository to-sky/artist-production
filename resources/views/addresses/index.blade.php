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
            <div class="ap_table-container">
                @if ($errors->any())
                    <div class="ap_form__error">{{ $errors->first() }}</div>
                @endif

                <table class="ap_table ap_table--striped link_table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Country</th>
                            <th>City</th>
                            <th>Post code</th>
                            <th>Building</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($addresses as $address)
                        <tr class="link_row" data-url="{{ route('address.show', ['address' => $address]) }}">
                            <td>{{ $address->full_name }}</td>
                            <td>{{ $address->country->name }}</td>
                            <td>{{ $address->city }}</td>
                            <td>{{ $address->post_code }}</td>
                            <td>{{ $address->building_name }}</td>
                            <td>
                                {!! Form::open(['method' => 'DELETE','route' => ['address.delete', 'address' => $address]]) !!}
                                    <button class="ap_button--icon">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                {!! Form::close() !!}
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <section class="ap_section">
        <div class="ap_buttons">
            <a href="{{ route('address.create') }}" class="ap_button--submit">{!! __('Add') !!}</a>
        </div>
    </section>
@endsection