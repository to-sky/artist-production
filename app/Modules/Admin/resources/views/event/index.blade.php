@extends('Admin::layouts.master')

@section('page-header')
    @include('Admin::partials.page-header')
@endsection

@section('content')
    <div class="box">
        <div class="box-header with-border">
            <a href="{{ route(config('admin.route').'.events.create') }}" class="btn btn-primary" data-style="zoom-in">
                <span class="ladda-label">
                    <i class="fa fa-plus"></i>
                    {{ trans('Admin::admin.add-new', ['item' => mb_strtolower(trans('Admin::models.' . ucfirst($menuRoute->singular_name)))]) }}
                </span>
            </a>
        </div>

        <div class="box-body">
            <table id="datatable" class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th class="no-sort text-center" width="5%">
                            {!! Form::checkbox('delete_all',1,false,['class' => 'mass']) !!}
                        </th>
                        <th>{{ __('Thumbnail') }}</th>
                        <th>{{ __('Event') }}</th>
                        <th>{{ __('Date') }}</th>
                        <th>{{ __('Time') }}</th>
                        <th>{{ __('City') }}</th>
                        <th>{{ __('Building') }}</th>
                        <th>{{ __('Hall') }}</th>
                        <th>{{ __('Active') }}</th>
                        <th>{{ __('Actions') }}</th>
                    </tr>
                </thead>

                <tbody>
                @foreach($events as $event)
                    <tr>
                        <td class="text-center">
                            {!! Form::checkbox('del-'.$event->id,1,false,['class' => 'single','data-id'=> $event->id]) !!}
                        </td>
                        <td class="text-center">
                            <img src="{{ $event->image_url }}" alt="{{ $event->id }}" width="40">
                        </td>
                        <td>{{ $event->name }}</td>
                        <td>{{ $event->date->formatLocalized('%b %d, %Y') }}</td>
                        <td>{{ $event->date->format('H:i') }}</td>
                        <td>{{ $event->hall->building->city->name }}</td>
                        <td>{{ $event->hall->building->name }}</td>
                        <td>{{ $event->hall->name }}</td>
                        <td>{{ numberToString($event->is_active) }}</td>
                        <td>
                            @if(empty($event->kartina_id))
                                <a href="{{ route(config('admin.route').'.events.edit', [$event->id]) }}#event" class="btn btn-xs btn-default">
                                    <i class="fa fa-edit"></i> {{ trans('Admin::admin.users-index-edit') }}
                                </a>
                                <a href="{{ route(config('admin.route').'.events.edit', [$event->id]) }}#prices" class="btn btn-xs btn-default">
                                    <i class="fa fa-magic"></i> {{ trans('Admin::admin.events-index-prices') }}
                                </a>
                                <a href="{{ route(config('admin.route').'.events.hallPlaces', [$event->id]) }}" class="btn btn-xs btn-default">
                                    <i class="fa fa-list"></i> {{ trans('Admin::admin.events-index-place-binding') }}
                                </a>
                                <a href="{{ route(config('admin.route').'.events.destroy', [$event->id]) }}" class="btn btn-xs btn-default delete-button">
                                    <i class="fa fa-trash"></i> {{ trans('Admin::admin.users-index-delete') }}
                                </a>
                            @else
                                KaritnaTV
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        <div class="box-footer">
            <button class="btn btn-danger" id="delete">
                <span>
                    <i class="fa fa-trash"></i>
                    {{ trans('Admin::templates.templates-view_index-delete_checked') }}
                </span>
            </button>
            {!! Form::open(['route' => config('admin.route').'.events.massDelete', 'method' => 'post', 'id' => 'massDelete']) !!}
                <input type="hidden" id="send" name="toDelete">
            {!! Form::close() !!}
        </div>
    </div>
@endsection

@section('after_scripts')
    @include('Admin::partials.datatable-scripts')
@endsection
