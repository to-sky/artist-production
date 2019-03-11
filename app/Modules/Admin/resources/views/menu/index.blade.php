@extends('Admin::layouts.master')

@section('page-header')

    @include('Admin::partials.page-header')

@endsection

@section('content')

    <div class="box">

        <div class="box-header with-border">
            <a href="{{ route(config('admin.route') . '.menu.create') }}" class="btn btn-primary"><i class="fa fa-plus"></i> {{ trans('Admin::admin.add-new', ['item' => trans('Admin::models.' . $menuRoute->singular_name)]) }}</a>
            <a href="{{ route(config('admin.route') . '.menu.crud') }}" class="btn btn-primary"><i class="fa fa-plus"></i> {{ __('Add menu with CRUD') }}</a>
        </div>
        <div class="box-body">


            @if($menusList->count() == 0)
                <div class="row">
                    <div class="col-xs-6 col-md-4">
                        <div class="alert alert-info">
                            {{ trans('Admin::qa.menus-index-no_menu_items_found') }}
                        </div>
                    </div>
                </div>
            @endif

            {!! Form::open(['id' => 'order-form', 'route' => config('admin.route') . '.menu.rearrange']) !!}

            @if($menusList->count() != 0)
                <div class="row">
                    <div class="col-xs-6 col-md-4">
                        <div class="alert alert-info">
                            {{ trans('Admin::qa.menus-index-positions_drag_drop') }}
                        </div>
                    </div>
                </div>
            @endif

            <div class="row">
                <div class="col-xs-6 col-md-4">
                    <ul id="sortable" class="list-unstyled">
                        @foreach($menusList as $menu)
                            @if($menu->children()->first() == null)
                                <li data-menu-id="{{ $menu->id }}">
                                    <span>
                                        {{ $menu->title }} {{ $menu->parent_id }}
                                        <a href="{{ route(config('admin.route') . '.menu.edit',[$menu->id]) }}"
                                           class="btn btn-xs btn-default pull-right">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                    </span>
                                    <input type="hidden" class="menu-no" value="{{ $menu->position }}"
                                           name="menu-{{ $menu->id }}">
                                    @if($menu->menu_type == 2)
                                        <ul class="childs" style="min-height: 10px;"></ul>
                                    @endif
                                </li>
                            @else
                                <li data-menu-id="{{ $menu->id }}">
                                    <span>
                                        {{ $menu->title }}
                                        <a href="{{ route(config('admin.route') . '.menu.edit',[$menu->id]) }}"
                                           class="btn btn-xs btn-default pull-right">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                    </span>
                                    <input type="hidden" class="menu-no" value="{{ $menu->position }}"
                                           name="menu-{{ $menu->id }}">
                                    <ul class="childs list-unstyled" style="min-height: 10px;">
                                        @foreach($menu->children as $child)
                                            <li>
                                                <span>
                                                    {{ $child->title }}
                                                    <a href="{{ route(config('admin.route') . '.menu.edit',[$child->id]) }}"
                                                       class="btn btn-xs btn-default pull-right">
                                                        <i class="fa fa-edit"></i>
                                                    </a>
                                                </span>
                                                <input type="hidden" class="child-no" value="{{ $child->position }}"
                                                       name="child-{{ $child->id }}">
                                                <input type="hidden" class="menu-id" value="{{ $menu->id }}"
                                                       name="child-parent-{{ $child->id }}">
                                            </li>
                                        @endforeach
                                    </ul>
                                </li>
                            @endif
                        @endforeach
                    </ul>
                </div>
            </div>

            {!! Form::close() !!}

        </div>

        <div class="box-footer">
            @if($menusList->count() != 0)

                {{--<div class="row" id="dragMessage" style="display: none;">--}}
                    {{--<div class="col-xs-6 col-md-4">--}}
                        {{--<div class="alert alert-danger">--}}
                            {{--{{ trans('Admin::qa.menus-index-click_save_positions') }}--}}
                        {{--</div>--}}
                    {{--</div>--}}
                {{--</div>--}}
                {{--<div class="row">--}}
                    {{--<div class="col-xs-12">--}}
                    {{--{!! Form::submit(trans('Admin::qa.menus-index-save_positions'),['class' => 'btn btn-danger']) !!}--}}
                    {{--</div>--}}
                {{--</div>--}}
                {{--<a href="javascript:void(0)" class="btn btn-success"><i class="fa fa-save"></i> {{ trans('Admin::qa.menus-index-save_positions') }}</a>--}}
                <button class="btn btn-success" id="save-positions">
                    <span><i class="fa fa-save"></i> {{ trans('Admin::qa.menus-index-save_positions') }}</span>
                </button>
            @endif
        </div>

    </div>

@stop

@section('after_scripts')
    <script>
        $(function () {
            $("#sortable").sortable({
                placeholder: "ui-state-highlight",
                update: function () {
                    $('#dragMessage').show();
                    var i = 1;
                    $('#sortable').find('> li').each(function () {
                        $(this).attr('data-menu-no', i);
                        var no = $(this).attr('data-menu-no');
                        $(this).find('.menu-no').val(no);
                        i++;
                    });
                }

            });
            $("#sortable").disableSelection();
            $(".childs").sortable({
                placeholder: "ui-state-highlight",
                connectWith: ".childs",
                dropOnEmpty: true,
                update: function () {
                    $('#dragMessage').show();
                    $('#sortable').find('> li').each(function () {
                        var i = 1;
                        $('> ul > li', this).each(function () {
                            var no = $(this).parent().parent().attr('data-menu-id');
                            $(this).find('.menu-id').val(no);
                            $(this).find('.child-no').val(i);
                            i++;
                            console.log('ok');
                        });
                    });
                }
            });
            
            $('#save-positions').click(function () {
                $('#order-form').submit();
            })
        });
    </script>
@stop