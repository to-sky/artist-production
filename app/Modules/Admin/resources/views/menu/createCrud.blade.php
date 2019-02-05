@extends('Admin::layouts.master')

@section('page-header')
    <section class="content-header">
        <h1>
            <span class="text-capitalize">Menu</span>
            <small>Create CRUD</small>
        </h1>

        <ol class="breadcrumb">
            <li><a href="{{ url('dashboard') }}">{{ trans('Admin::admin.partials-header-title') }}</a></li>
            <li><a href="{{ url('menu') }}" class="text-capitalize">{{ 'Menu' }}</a></li>
            <li class="active">{{ trans('Admin::templates.templates-view_create-create') }}</li>
        </ol>

    </section>
@endsection

@section('content')

    <div class="row">

        <div class="col-md-8 col-md-offset-2">

            <a href="{{ route(config('admin.route') . '.menu.index') }}"><i class="fa fa-angle-double-left"></i> Back to all <span>menu</span></a><br><br>

            @include('Admin::partials.errors')

            {!! Form::open() !!}
            <div class="box">

                <div class="box-header with-border">
                    <h3 class="box-title">Add a new CRUD menu</h3>
                </div>

                <div class="box-body row">

                    <div class="form-group col-md-12">
                        {!! Form::label('parent_id', trans('Admin::qa.menus-createCrud-crud_parent')) !!}
                        {!! Form::select('parent_id', $parentsSelect, old('parent_id'), ['class'=>'form-control']) !!}
                    </div>

                    <div class="form-group col-md-12">
                        {!! Form::label('singular_name', trans('Admin::qa.menus-createCrud-crud_singular_name')) !!}
                        {!! Form::text('singular_name', old('singular_name'), ['class'=>'form-control', 'placeholder'=> trans('Admin::qa.menus-createCrud-crud_singular_name_placeholder')]) !!}
                    </div>

                    <div class="form-group col-md-12">
                        {!! Form::label('plural_name', trans('Admin::qa.menus-createCrud-crud_plural_name')) !!}
                        {!! Form::text('plural_name', old('plural_name'), ['class'=>'form-control', 'placeholder'=> trans('Admin::qa.menus-createCrud-crud_plural_name_placeholder')]) !!}
                    </div>

                    <div class="form-group col-md-12">
                        {!! Form::label('title', trans('Admin::qa.menus-createCrud-crud_title')) !!}
                        {!! Form::text('title', old('title'), ['class'=>'form-control', 'placeholder'=> trans('Admin::qa.menus-createCrud-crud_title_placeholder')]) !!}
                    </div>

                    <div class="form-group col-md-12">
                        {!! Form::label('roles', trans('Admin::qa.menus-createCrud-roles')) !!}
                        @foreach($roles as $role)
                            <div class="checkbox">
                                <label>
                                    {!! Form::checkbox('roles['.$role->id.']',$role->id,old('roles.'.$role->id)) !!}
                                    {!! $role->display_name !!}
                                </label>
                            </div>
                        @endforeach
                    </div>


                    <div class="form-group col-md-12">
                        {!! Form::label('soft', trans('Admin::qa.menus-createCrud-soft_delete')) !!}
                        {!! Form::select('soft', [1 => trans('strings.yes'), 0 => trans('strings.no')], old('soft'), ['class' => 'form-control']) !!}
                    </div>

                    <div class="form-group col-md-12">
                        {!! Form::label('icon', trans('Admin::qa.menus-createCrud-icon')) !!}
                        {!! Form::text('icon', old('icon','fa-database'), ['class'=>'form-control', 'placeholder'=> trans('Admin::qa.menus-createCrud-icon_placeholder')]) !!}
                    </div>

                    <hr/>
                    <div class="form-group col-md-12">
                        <h3>{{ trans('Admin::qa.menus-createCrud-add_fields') }}</h3>

                        <table class="table">
                            <tbody id="generator">
                                <tr>
                                    <td>{{ trans('Admin::qa.menus-createCrud-show_in_list') }}</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                @if(old('f_type'))
                                    @foreach(old('f_type') as $index => $fieldName)
                                        @include('Admin::templates.menu_field_line', ['index' => $index])
                                    @endforeach
                                @else
                                    @include('Admin::templates.menu_field_line', ['index' => ''])
                                @endif
                            </tbody>
                        </table>

                        <div class="form-group">
                            <button type="button" id="addField" class="btn btn-success">
                                <i class="fa fa-plus"></i> {{ trans('Admin::qa.menus-createCrud-add_field') }}
                            </button>
                        </div>
                    </div>

                    <hr/>

                    {{--<div class="form-group">--}}
                        {{--<div class="col-md-12">--}}
                            {{--{!! Form::submit(trans('Admin::qa.menus-createCrud-create_crud'), ['class' => 'btn btn-primary']) !!}--}}
                        {{--</div>--}}
                    {{--</div>--}}
                </div>

                <div class="box-footer">

                    @include('Admin::partials.save-buttons')

                </div>

            </div>

            {!! Form::close() !!}


        </div>

    </div>

    <div style="display: none;">
        <table>
            <tbody id="line">
                @include('Admin::templates.menu_field_line', ['index' => ''])
            </tbody>
        </table>

        <!-- Select for relationship column-->
        @foreach($models as $key => $model)
            <select name="f_relationship_field[{{ $key }}]" class="form-control relationship-field rf-{{ $key }}">
                <option value="">{{ trans('Admin::qa.menus-createCrud-select_display_field') }}</option>
                @foreach($model as $key2 => $option)
                    <option value="{{ $option }}" @if($option == old('f_relationship_field.'.$key)) selected @endif>
                        {{ $option }}
                    </option>
                @endforeach
            </select>
        @endforeach
                    <!-- /Select for relationship column-->
    </div>

@endsection

@section('after_scripts')

    @include('Admin::partials.form-scripts')

    <script>
        function typeChange(e) {
            var val = $(e).val();
            // Hide all possible outputs
            $(e).parent().parent().find('.value').hide();
            $(e).parent().parent().find('.default_c').hide();
            $(e).parent().parent().find('.relationship').hide();
            $(e).parent().parent().find('.title').show().val('');
            $(e).parent().parent().find('.texteditor').hide();
            $(e).parent().parent().find('.size').hide();
            $(e).parent().parent().find('.dimensions').hide();
            $(e).parent().parent().find('.enum').hide();

            // Show a checbox which enables/disables showing in list
            $(e).parent().parent().parent().find('.show2').show();
            $(e).parent().parent().parent().find('.show_hid').val(1);
            switch (val) {
                case 'radio':
                    $(e).parent().parent().find('.value').show();
                    break;
                case 'checkbox':
                    $(e).parent().parent().find('.default_c').show();
                    break;
                case 'relationship':
                    $(e).parent().parent().find('.relationship').show();
                    $(e).parent().parent().find('.title').hide().val('-');
                    break;
                case 'textarea':
                    $(e).parent().parent().find('.show2').hide();
                    $(e).parent().parent().find('.show_hid').val(0);
                    $(e).parent().parent().find('.texteditor').show();
                    break;
                case 'file':
                    $(e).parent().parent().find('.size').show();
                    break;
                case 'enum':
                    $(e).parent().parent().find('.enum').show();
                    break;
                case 'photo':
                    $(e).parent().parent().find('.size').show();
                    $(e).parent().parent().find('.dimensions').show();
                    break;
            }
        }

        function relationshipChange(e) {
            var val = $(e).val();
            $(e).parent().parent().find('.relationship-field').remove();
            var select = $('.rf-' + val).clone();
            $(e).parent().parent().find('.relationship-holder').html(select);
        }

        $(document).ready(function () {
            $('.type').each(function () {
                typeChange($(this))
            });
            $('.relationship').each(function () {
                relationshipChange($(this))
            });

            $('.show2').change(function () {
                var checked = $(this).is(":checked");
                if (checked) {
                    $(this).parent().find('.show_hid').val(1);
                } else {
                    $(this).parent().find('.show_hid').val(0);
                }
            });

            // Add new row to the table of fields
            $('#addField').click(function () {
                var line = $('#line').html();
                var table = $('#generator');
                table.append(line);
            });

            // Remove row from the table of fields
            $(document).on('click', '.rem', function () {
                $(this).parent().parent().remove();
            });

            $(document).on('change', '.type', function () {
                typeChange($(this))
            });
            $(document).on('change', '.relationship', function () {
                relationshipChange($(this))
            });
        });

    </script>
@stop