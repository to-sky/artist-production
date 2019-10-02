@extends('Admin::layouts.master')

@section('page-header')

    @include('Admin::partials.page-header')

@endsection

@section('content')
    <div class="row">

        <div class="col-md-8 col-md-offset-2">

            @include('Admin::partials.errors')

            {!! Form::open(['route' => config('admin.route') . '.settings.mailStore']) !!}

            <div class="box">

                <div class="box-header with-border">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#smtp">SMTP</a></li>
                        <li><a href="#letters-templates">{{ __('Letters templates') }}</a></li>
                    </ul>
                </div>

                <div class="box-body row tab-content">
                    <div class="tab-pane active" id="smtp">
                        <div class="form-group col-md-12">
                            {!! Form::label('smtp_server', __('SMTP server')) !!}
                            {!! Form::text('settings[smtp_server]', old('smtp_server', setting('smtp_server', null)), ['class'=>'form-control', 'id' => 'smtp_server', 'placeholder'=> __('SMTP server')]) !!}
                        </div>
                        <div class="form-group col-md-12">
                            {!! Form::label('smtp_port', __('SMTP port')) !!}
                            {!! Form::text('settings[smtp_port]', old('smtp_port', setting('smtp_port', null)), ['class'=>'form-control', 'id' => 'smtp_port', 'placeholder'=> __('SMTP port')]) !!}
                        </div>
                        <div class="form-group col-md-12">
                            {!! Form::label('smtp_login', __('SMTP login')) !!}
                            {!! Form::text('settings[smtp_login]', old('smtp_login', setting('smtp_login', null)), ['class'=>'form-control', 'id' => 'smtp_login', 'placeholder'=> __('SMTP login')]) !!}
                        </div>
                        <div class="form-group col-md-12">
                            {!! Form::label('smtp_password', __('SMTP password')) !!}
                            {!! Form::input('password', 'settings[smtp_password]', setting('smtp_password', null), ['class'=>'form-control', 'id' => 'smtp_password', 'placeholder'=> __('SMTP password')]) !!}
                        </div>
                        <div class="form-group col-md-12">
                            {!! Form::label('smtp_security', __('Security')) !!}
                            {!! Form::select('settings[smtp_security]', [0 => __('SSL on'), 1 => __('SSL off')], old('smtp_security', setting('smtp_security', 0)), ['class'=>'form-control']) !!}
                        </div>
                        <div class="form-group col-md-12">
                            {!! Form::label('administrator_email', __('Administrator email')) !!}
                            {!! Form::text('settings[mail_administrator_email]', old('mail_administrator_email', setting('mail_administrator_email', null)), ['class'=>'form-control', 'id' => 'administrator_email', 'placeholder'=> __('Administrator email')]) !!}
                        </div>
                    </div>
                    <div class="tab-pane" id="letters-templates">
                        <div class="form-group col-md-12">
                            {!! Form::label('order_payment_copy_email', __('Send copy of notification about successful order payment to email')) !!}
                            {!! Form::text('settings[mail_order_payment_copy_email]', old('mail_order_payment_copy_email', setting('mail_order_payment_copy_email', null)), ['class'=>'form-control', 'id' => 'order_payment_copy_email', 'placeholder'=> 'Email']) !!}
                        </div>
                        <div class="form-group col-md-12">
                            <div class="checkbox">
                                <label>
                                    {!! Form::hidden('settings[mail_notify_order_payment]', 0) !!}
                                    {!! Form::checkbox('settings[mail_notify_order_payment]', 1, old('mail_notify_order_payment', setting('mail_notify_order_payment', null))) !!}
                                    {!! __('Send notification about successful order payment') !!}
                                </label>
                            </div>
                        </div>
                        <div class="form-group col-md-12">
                            {!! Form::label('mail_order_payment_ru', __('Successful order payment')) !!}
                            <ul class="nav nav-pills">
                                <li class="active"><a href="#ru-order-payment">RU</a></li>
                                <li><a href="#de-order-payment">DE</a></li>
                                <li><a href="#en-order-payment">EN</a></li>
                                <li class="pull-right"><a href="#placeholders-order-payment">{{ __('Placeholders') }}</a></li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane active" id="ru-order-payment">{!! Form::textarea('settings[mail_order_payment_ru]', old('mail_order_payment_ru', setting('mail_order_payment_ru', null)), array('class'=>'form-control ckeditor-classic', 'id' => 'mail_order_payment_ru')) !!}</div>
                                <div class="tab-pane" id="de-order-payment">{!! Form::textarea('settings[mail_order_payment_de]', old('mail_order_payment_de', setting('mail_order_payment_de', null)), array('class'=>'form-control ckeditor-classic', 'id' => 'mail_order_payment_de')) !!}</div>
                                <div class="tab-pane" id="en-order-payment">{!! Form::textarea('settings[mail_order_payment_en]', old('mail_order_payment_en', setting('mail_order_payment_en', null)), array('class'=>'form-control ckeditor-classic', 'id' => 'mail_order_payment_en')) !!}</div>
                                <div class="tab-pane" id="placeholders-order-payment">
                                    <hr style="margin: 0 0 20px 0">
                                    <ul style="list-style-type: none">
                                        <li>
                                            <a href="" class="btn btn-default copy-placeholder" data-mail-placeholder="@OrderId" data-toggle="tooltip" title="{{ __('Copy') }}">
                                                <span class="fa fa-copy" role="presentation" aria-hidden="true"></span>
                                            </a>
                                            {{ __(':placeholder - order number', ['placeholder' => '@OrderId']) }}
                                        </li>
                                        <li>
                                            <a href="#" class="btn btn-default copy-placeholder" data-mail-placeholder="@SiteUrl" data-toggle="tooltip" title="{{ __('Copy') }}">
                                                <span class="fa fa-copy" role="presentation" aria-hidden="true"></span>
                                            </a>
                                            {{ __(':placeholder - link to the website', ['placeholder' => '@SiteUrl']) }}
                                        </li>
                                        <li>
                                            <a href="#" class="btn btn-default copy-placeholder" data-mail-placeholder="@TicketsList" data-toggle="tooltip" title="{{ __('Copy') }}">
                                                <span class="fa fa-copy" role="presentation" aria-hidden="true"></span>
                                            </a>
                                            {{ __(':placeholder - list of ordered tickets', ['placeholder' => '@TicketsList']) }}
                                        </li>
                                        <li>
                                            <a href="#" class="btn btn-default copy-placeholder" data-mail-placeholder="@Amount" data-toggle="tooltip" title="{{ __('Copy') }}">
                                                <span class="fa fa-copy" role="presentation" aria-hidden="true"></span>
                                            </a>
                                            {{ __(':placeholder - payment amount', ['placeholder' => '@Amount']) }}
                                        </li>
                                        <li>
                                            <a href="#" class="btn btn-default copy-placeholder" data-mail-placeholder="@Currency" data-toggle="tooltip" title="{{ __('Copy') }}">
                                                <span class="fa fa-copy" role="presentation" aria-hidden="true"></span>
                                            </a>
                                            {{ __(':placeholder - order currency', ['placeholder' => '@Currency']) }}
                                        </li>
                                        <li>
                                            <a href="#" class="btn btn-default copy-placeholder" data-mail-placeholder="@ClientName" data-toggle="tooltip" title="{{ __('Copy') }}">
                                                <span class="fa fa-copy" role="presentation" aria-hidden="true"></span>
                                            </a>
                                            {{ __(':placeholder - client name', ['placeholder' => '@ClientName']) }}
                                        </li>
                                        <li>
                                            <a href="#" class="btn btn-default copy-placeholder" data-mail-placeholder="[OrderInvoice]" data-toggle="tooltip" title="{{ __('Copy') }}">
                                                <span class="fa fa-copy" role="presentation" aria-hidden="true"></span>
                                            </a>
                                            {{ __(':placeholder - Order invoice attachment', ['placeholder' => '[OrderInvoice]']) }}
                                        </li>
                                    </ul>
                                    <hr style="margin: 20px 0 0 0">
                                </div>
                            </div>
                        </div>
                        <div class="form-group col-md-12">
                            <div class="checkbox">
                                <label>
                                    {!! Form::hidden('settings[mail_notify_clearance_reserve]', 0) !!}
                                    {!! Form::checkbox('settings[mail_notify_clearance_reserve]', 1, old('mail_notify_clearance_reserve', setting('mail_notify_clearance_reserve', null))) !!}
                                    {!! __('Send notification about successful clearance reserve') !!}
                                </label>
                            </div>
                        </div>
                        <div class="form-group col-md-12">
                            {!! Form::label('mail_clearance_reserve_ru', __('Successful clearance reserve')) !!}
                            <ul class="nav nav-pills">
                                <li class="active"><a href="#ru-clearance-reserve">RU</a></li>
                                <li><a href="#de-clearance-reserve">DE</a></li>
                                <li><a href="#en-clearance-reserve">EN</a></li>
                                <li class="pull-right"><a href="#placeholders-clearance-reserve">{{ __('Placeholders') }}</a></li>

                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane active" id="ru-clearance-reserve">{!! Form::textarea('settings[mail_clearance_reserve_ru]', old('mail_clearance_reserve_ru', setting('mail_clearance_reserve_ru', null)), array('class'=>'form-control ckeditor-classic', 'id' => 'mail_clearance_reserve_ru')) !!}</div>
                                <div class="tab-pane" id="de-clearance-reserve">{!! Form::textarea('settings[mail_clearance_reserve_de]', old('mail_clearance_reserve_de', setting('mail_clearance_reserve_de', null)), array('class'=>'form-control ckeditor-classic', 'id' => 'mail_clearance_reserve_de')) !!}</div>
                                <div class="tab-pane" id="en-clearance-reserve">{!! Form::textarea('settings[mail_clearance_reserve_en]', old('mail_clearance_reserve_en', setting('mail_clearance_reserve_en', null)), array('class'=>'form-control ckeditor-classic', 'id' => 'mail_clearance_reserve_en')) !!}</div>
                                <div class="tab-pane" id="placeholders-clearance-reserve">
                                    <hr style="margin: 0 0 20px 0">
                                    <ul style="list-style-type: none">
                                        <li>
                                            <a href="#" class="btn btn-default copy-placeholder" data-mail-placeholder="@ReserveExpirationDate" data-toggle="tooltip" title="{{ __('Copy') }}">
                                                <span class="fa fa-copy" role="presentation" aria-hidden="true"></span>
                                            </a>
                                            {{ __(':placeholder - reserve expiration date', ['placeholder' => '@ReserveExpirationDate']) }}
                                        </li>
                                        <li>
                                            <a href="#" class="btn btn-default copy-placeholder" data-mail-placeholder="@OrderId" data-toggle="tooltip" title="{{ __('Copy') }}">
                                                <span class="fa fa-copy" role="presentation" aria-hidden="true"></span>
                                            </a>
                                            {{ __(':placeholder - order number', ['placeholder' => '@OrderId']) }}
                                        </li>
                                        <li>
                                            <a href="#" class="btn btn-default copy-placeholder" data-mail-placeholder="@SiteUrl" data-toggle="tooltip" title="{{ __('Copy') }}">
                                                <span class="fa fa-copy" role="presentation" aria-hidden="true"></span>
                                            </a>
                                            {{ __(':placeholder - link to the website', ['placeholder' => '@SiteUrl']) }}
                                        </li>
                                        <li>
                                            <a href="#" class="btn btn-default copy-placeholder" data-mail-placeholder="@TicketsList" data-toggle="tooltip" title="{{ __('Copy') }}">
                                                <span class="fa fa-copy" role="presentation" aria-hidden="true"></span>
                                            </a>
                                            {{ __(':placeholder - list of ordered tickets', ['placeholder' => '@TicketsList']) }}
                                        </li>
                                        <li>
                                            <a href="#" class="btn btn-default copy-placeholder" data-mail-placeholder="@Amount" data-toggle="tooltip" title="{{ __('Copy') }}">
                                                <span class="fa fa-copy" role="presentation" aria-hidden="true"></span>
                                            </a>
                                            {{ __(':placeholder - payment amount', ['placeholder' => '@Amount']) }}
                                        </li>
                                        <li>
                                            <a href="#" class="btn btn-default copy-placeholder" data-mail-placeholder="@Currency" data-toggle="tooltip" title="{{ __('Copy') }}">
                                                <span class="fa fa-copy" role="presentation" aria-hidden="true"></span>
                                            </a>
                                            {{ __(':placeholder - order currency', ['placeholder' => '@Currency']) }}
                                        </li>
                                        <li>
                                            <a href="#" class="btn btn-default copy-placeholder" data-mail-placeholder="@ClientName" data-toggle="tooltip" title="{{ __('Copy') }}">
                                                <span class="fa fa-copy" role="presentation" aria-hidden="true"></span>
                                            </a>
                                            {{ __(':placeholder - client name', ['placeholder' => '@ClientName']) }}
                                        </li>
                                        <li>
                                            <a href="#" class="btn btn-default copy-placeholder" data-mail-placeholder="@TicketOfficesList" data-toggle="tooltip" title="{{ __('Copy') }}">
                                                <span class="fa fa-copy" role="presentation" aria-hidden="true"></span>
                                            </a>
                                            {{ __(':placeholder - cash list', ['placeholder' => '@TicketOfficesList']) }}
                                        </li>
                                        <li>
                                            <a href="#" class="btn btn-default copy-placeholder" data-mail-placeholder="@WeWillCallYouMessage" data-toggle="tooltip" title="{{ __('Copy') }}">
                                                <span class="fa fa-copy" role="presentation" aria-hidden="true"></span>
                                            </a>
                                            {{ __(':placeholder - message \'we will call you\'', ['placeholder' => '@WeWillCallYouMessage']) }}
                                        </li>
                                        <li>
                                            <a href="#" class="btn btn-default copy-placeholder" data-mail-placeholder="@BankRequisites" data-toggle="tooltip" title="{{ __('Copy') }}">
                                                <span class="fa fa-copy" role="presentation" aria-hidden="true"></span>
                                            </a>
                                            {{ __(':placeholder - bank details', ['placeholder' => '@BankRequisites']) }}
                                        </li>
                                        <li>
                                            <a href="#" class="btn btn-default copy-placeholder" data-mail-placeholder="[OrderInvoice]" data-toggle="tooltip" title="{{ __('Copy') }}">
                                                <span class="fa fa-copy" role="presentation" aria-hidden="true"></span>
                                            </a>
                                            {{ __(':placeholder - Order invoice attachment', ['placeholder' => '[OrderInvoice]']) }}
                                        </li>
                                    </ul>
                                    <hr style="margin: 20px 0 0 0">
                                </div>
                            </div>
                        </div>
                        <div class="form-group col-md-12">
                            <div class="checkbox">
                                <label>
                                    {!! Form::hidden('settings[mail_notify_clearance_reserve_reminder]', 0) !!}
                                    {!! Form::checkbox('settings[mail_notify_clearance_reserve_reminder]', 1, old('mail_notify_clearance_reserve_reminder', setting('mail_notify_clearance_reserve_reminder', null))) !!}
                                    {!! __('Send reserve reminder notification') !!}
                                </label>
                            </div>
                        </div>
                        <div class="form-group col-md-12">
                            {!! Form::label('mail_clearance_reserve_reminder_ru', __('Reserve reminder')) !!}
                            <ul class="nav nav-pills">
                                <li class="active"><a href="#ru-clearance-reserve-reminder">RU</a></li>
                                <li><a href="#de-clearance-reserve-reminder">DE</a></li>
                                <li><a href="#en-clearance-reserve-reminder">EN</a></li>
                                <li class="pull-right"><a href="#placeholders-clearance-reserve-reminder">{{ __('Placeholders') }}</a></li>

                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane active" id="ru-clearance-reserve-reminder">{!! Form::textarea('settings[mail_clearance_reserve_reminder_ru]', old('mail_clearance_reserve_reminder_ru', setting('mail_clearance_reserve_reminder_ru', null)), array('class'=>'form-control ckeditor-classic', 'id' => 'mail_clearance_reserve_reminder_ru')) !!}</div>
                                <div class="tab-pane" id="de-clearance-reserve-reminder">{!! Form::textarea('settings[mail_clearance_reserve_reminder_de]', old('mail_clearance_reserve_reminder_de', setting('mail_clearance_reserve_reminder_de', null)), array('class'=>'form-control ckeditor-classic', 'id' => 'mail_clearance_reserve_reminder_de')) !!}</div>
                                <div class="tab-pane" id="en-clearance-reserve-reminder">{!! Form::textarea('settings[mail_clearance_reserve_reminder_en]', old('mail_clearance_reserve_reminder_en', setting('mail_clearance_reserve_reminder_en', null)), array('class'=>'form-control ckeditor-classic', 'id' => 'mail_clearance_reserve_reminder_en')) !!}</div>
                                <div class="tab-pane" id="placeholders-clearance-reserve-reminder">
                                    <hr style="margin: 0 0 20px 0">
                                    <ul style="list-style-type: none">
                                        <li>
                                            <a href="#" class="btn btn-default copy-placeholder" data-mail-placeholder="@ReserveExpirationDate" data-toggle="tooltip" title="{{ __('Copy') }}">
                                                <span class="fa fa-copy" role="presentation" aria-hidden="true"></span>
                                            </a>
                                            {{ __(':placeholder - reserve expiration date', ['placeholder' => '@ReserveExpirationDate']) }}
                                        </li>
                                        <li>
                                            <a href="#" class="btn btn-default copy-placeholder" data-mail-placeholder="@OrderId" data-toggle="tooltip" title="{{ __('Copy') }}">
                                                <span class="fa fa-copy" role="presentation" aria-hidden="true"></span>
                                            </a>
                                            {{ __(':placeholder - order number', ['placeholder' => '@OrderId']) }}
                                        </li>
                                        <li>
                                            <a href="#" class="btn btn-default copy-placeholder" data-mail-placeholder="@SiteUrl" data-toggle="tooltip" title="{{ __('Copy') }}">
                                                <span class="fa fa-copy" role="presentation" aria-hidden="true"></span>
                                            </a>
                                            {{ __(':placeholder - link to the website', ['placeholder' => '@SiteUrl']) }}
                                        </li>
                                        <li>
                                            <a href="#" class="btn btn-default copy-placeholder" data-mail-placeholder="@TicketsList" data-toggle="tooltip" title="{{ __('Copy') }}">
                                                <span class="fa fa-copy" role="presentation" aria-hidden="true"></span>
                                            </a>
                                            {{ __(':placeholder - list of ordered tickets', ['placeholder' => '@TicketsList']) }}
                                        </li>
                                        <li>
                                            <a href="#" class="btn btn-default copy-placeholder" data-mail-placeholder="@Amount" data-toggle="tooltip" title="{{ __('Copy') }}">
                                                <span class="fa fa-copy" role="presentation" aria-hidden="true"></span>
                                            </a>
                                            {{ __(':placeholder - payment amount', ['placeholder' => '@Amount']) }}
                                        </li>
                                        <li>
                                            <a href="#" class="btn btn-default copy-placeholder" data-mail-placeholder="@Currency" data-toggle="tooltip" title="{{ __('Copy') }}">
                                                <span class="fa fa-copy" role="presentation" aria-hidden="true"></span>
                                            </a>
                                            {{ __(':placeholder - order currency', ['placeholder' => '@Currency']) }}
                                        </li>
                                        <li>
                                            <a href="#" class="btn btn-default copy-placeholder" data-mail-placeholder="@ClientName" data-toggle="tooltip" title="{{ __('Copy') }}">
                                                <span class="fa fa-copy" role="presentation" aria-hidden="true"></span>
                                            </a>
                                            {{ __(':placeholder - client name', ['placeholder' => '@ClientName']) }}
                                        </li>
                                        <li>
                                            <a href="#" class="btn btn-default copy-placeholder" data-mail-placeholder="@TicketOfficesList" data-toggle="tooltip" title="{{ __('Copy') }}">
                                                <span class="fa fa-copy" role="presentation" aria-hidden="true"></span>
                                            </a>
                                            {{ __(':placeholder - cash list', ['placeholder' => '@TicketOfficesList']) }}
                                        </li>
                                        <li>
                                            <a href="#" class="btn btn-default copy-placeholder" data-mail-placeholder="@WeWillCallYouMessage" data-toggle="tooltip" title="{{ __('Copy') }}">
                                                <span class="fa fa-copy" role="presentation" aria-hidden="true"></span>
                                            </a>
                                            {{ __(':placeholder - message \'we will call you\'', ['placeholder' => '@WeWillCallYouMessage']) }}
                                        </li>
                                        <li>
                                            <a href="#" class="btn btn-default copy-placeholder" data-mail-placeholder="@BankRequisites" data-toggle="tooltip" title="{{ __('Copy') }}">
                                                <span class="fa fa-copy" role="presentation" aria-hidden="true"></span>
                                            </a>
                                            {{ __(':placeholder - bank details', ['placeholder' => '@BankRequisites']) }}
                                        </li>
                                        <li>
                                            <a href="#" class="btn btn-default copy-placeholder" data-mail-placeholder="[OrderInvoice]" data-toggle="tooltip" title="{{ __('Copy') }}">
                                                <span class="fa fa-copy" role="presentation" aria-hidden="true"></span>
                                            </a>
                                            {{ __(':placeholder - Order invoice attachment', ['placeholder' => '[OrderInvoice]']) }}
                                        </li>
                                    </ul>
                                    <hr style="margin: 20px 0 0 0">
                                </div>
                            </div>
                        </div>
                        <div class="form-group col-md-12">
                            <div class="checkbox">
                                <label>
                                    {!! Form::hidden('settings[mail_notify_clearance_reserve_reminder_10]', 0) !!}
                                    {!! Form::checkbox('settings[mail_notify_clearance_reserve_reminder_10]', 1, old('mail_notify_clearance_reserve_reminder_10', setting('mail_notify_clearance_reserve_reminder_10', null))) !!}
                                    {!! __('Send second reserve reminder notification') !!}
                                </label>
                            </div>
                        </div>
                        <div class="form-group col-md-12">
                            {!! Form::label('mail_clearance_reserve_reminder_10_ru', __('Reserve second reminder')) !!}
                            <ul class="nav nav-pills">
                                <li class="active"><a href="#ru-clearance-reserve-reminder-10">RU</a></li>
                                <li><a href="#de-clearance-reserve-reminder-10">DE</a></li>
                                <li><a href="#en-clearance-reserve-reminder-10">EN</a></li>
                                <li class="pull-right"><a href="#placeholders-clearance-reserve-reminder-10">{{ __('Placeholders') }}</a></li>

                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane active" id="ru-clearance-reserve-reminder-10">{!! Form::textarea('settings[mail_clearance_reserve_reminder_10_ru]', old('mail_clearance_reserve_reminder_10_ru', setting('mail_clearance_reserve_reminder_10_ru', null)), array('class'=>'form-control ckeditor-classic', 'id' => 'mail_clearance_reserve_reminder_10_ru')) !!}</div>
                                <div class="tab-pane" id="de-clearance-reserve-reminder-10">{!! Form::textarea('settings[mail_clearance_reserve_reminder_10_de]', old('mail_clearance_reserve_reminder_10_de', setting('mail_clearance_reserve_reminder_10_de', null)), array('class'=>'form-control ckeditor-classic', 'id' => 'mail_clearance_reserve_reminder_10_de')) !!}</div>
                                <div class="tab-pane" id="en-clearance-reserve-reminder-10">{!! Form::textarea('settings[mail_clearance_reserve_reminder_10_en]', old('mail_clearance_reserve_reminder_10_en', setting('mail_clearance_reserve_reminder_10_en', null)), array('class'=>'form-control ckeditor-classic', 'id' => 'mail_clearance_reserve_reminder_10_en')) !!}</div>
                                <div class="tab-pane" id="placeholders-clearance-reserve-reminder-10">
                                    <hr style="margin: 0 0 20px 0">
                                    <ul style="list-style-type: none">
                                        <li>
                                            <a href="#" class="btn btn-default copy-placeholder" data-mail-placeholder="@ReserveExpirationDate" data-toggle="tooltip" title="{{ __('Copy') }}">
                                                <span class="fa fa-copy" role="presentation" aria-hidden="true"></span>
                                            </a>
                                            {{ __(':placeholder - reserve expiration date', ['placeholder' => '@ReserveExpirationDate']) }}
                                        </li>
                                        <li>
                                            <a href="#" class="btn btn-default copy-placeholder" data-mail-placeholder="@OrderId" data-toggle="tooltip" title="{{ __('Copy') }}">
                                                <span class="fa fa-copy" role="presentation" aria-hidden="true"></span>
                                            </a>
                                            {{ __(':placeholder - order number', ['placeholder' => '@OrderId']) }}
                                        </li>
                                        <li>
                                            <a href="#" class="btn btn-default copy-placeholder" data-mail-placeholder="@SiteUrl" data-toggle="tooltip" title="{{ __('Copy') }}">
                                                <span class="fa fa-copy" role="presentation" aria-hidden="true"></span>
                                            </a>
                                            {{ __(':placeholder - link to the website', ['placeholder' => '@SiteUrl']) }}
                                        </li>
                                        <li>
                                            <a href="#" class="btn btn-default copy-placeholder" data-mail-placeholder="@TicketsList" data-toggle="tooltip" title="{{ __('Copy') }}">
                                                <span class="fa fa-copy" role="presentation" aria-hidden="true"></span>
                                            </a>
                                            {{ __(':placeholder - list of ordered tickets', ['placeholder' => '@TicketsList']) }}
                                        </li>
                                        <li>
                                            <a href="#" class="btn btn-default copy-placeholder" data-mail-placeholder="@Amount" data-toggle="tooltip" title="{{ __('Copy') }}">
                                                <span class="fa fa-copy" role="presentation" aria-hidden="true"></span>
                                            </a>
                                            {{ __(':placeholder - payment amount', ['placeholder' => '@Amount']) }}
                                        </li>
                                        <li>
                                            <a href="#" class="btn btn-default copy-placeholder" data-mail-placeholder="@Currency" data-toggle="tooltip" title="{{ __('Copy') }}">
                                                <span class="fa fa-copy" role="presentation" aria-hidden="true"></span>
                                            </a>
                                            {{ __(':placeholder - order currency', ['placeholder' => '@Currency']) }}
                                        </li>
                                        <li>
                                            <a href="#" class="btn btn-default copy-placeholder" data-mail-placeholder="@ClientName" data-toggle="tooltip" title="{{ __('Copy') }}">
                                                <span class="fa fa-copy" role="presentation" aria-hidden="true"></span>
                                            </a>
                                            {{ __(':placeholder - client name', ['placeholder' => '@ClientName']) }}
                                        </li>
                                        <li>
                                            <a href="#" class="btn btn-default copy-placeholder" data-mail-placeholder="@TicketOfficesList" data-toggle="tooltip" title="{{ __('Copy') }}">
                                                <span class="fa fa-copy" role="presentation" aria-hidden="true"></span>
                                            </a>
                                            {{ __(':placeholder - cash list', ['placeholder' => '@TicketOfficesList']) }}
                                        </li>
                                        <li>
                                            <a href="#" class="btn btn-default copy-placeholder" data-mail-placeholder="@WeWillCallYouMessage" data-toggle="tooltip" title="{{ __('Copy') }}">
                                                <span class="fa fa-copy" role="presentation" aria-hidden="true"></span>
                                            </a>
                                            {{ __(':placeholder - message \'we will call you\'', ['placeholder' => '@WeWillCallYouMessage']) }}
                                        </li>
                                        <li>
                                            <a href="#" class="btn btn-default copy-placeholder" data-mail-placeholder="@BankRequisites" data-toggle="tooltip" title="{{ __('Copy') }}">
                                                <span class="fa fa-copy" role="presentation" aria-hidden="true"></span>
                                            </a>
                                            {{ __(':placeholder - bank details', ['placeholder' => '@BankRequisites']) }}
                                        </li>
                                        <li>
                                            <a href="#" class="btn btn-default copy-placeholder" data-mail-placeholder="[OrderInvoice]" data-toggle="tooltip" title="{{ __('Copy') }}">
                                                <span class="fa fa-copy" role="presentation" aria-hidden="true"></span>
                                            </a>
                                            {{ __(':placeholder - Order invoice attachment', ['placeholder' => '[OrderInvoice]']) }}
                                        </li>
                                    </ul>
                                    <hr style="margin: 20px 0 0 0">
                                </div>
                            </div>
                        </div>
                        <div class="form-group col-md-12">
                            <div class="checkbox">
                                <label>
                                    {!! Form::hidden('settings[mail_notify_tickets_for_sale]', 0) !!}
                                    {!! Form::checkbox('settings[mail_notify_tickets_for_sale]', 1, old('mail_notify_tickets_for_sale', setting('mail_notify_tickets_for_sale', null))) !!}
                                    {!! __('Send notification about issue tickets for sale') !!}
                                </label>
                            </div>
                        </div>
                        <div class="form-group col-md-12">
                            {!! Form::label('mail_tickets_for_sale_ru', __('Issue of tickets for sale notification')) !!}
                            <ul class="nav nav-pills">
                                <li class="active"><a href="#ru-tickets-for-sale">RU</a></li>
                                <li><a href="#de-tickets-for-sale">DE</a></li>
                                <li><a href="#en-tickets-for-sale">EN</a></li>
                                <li class="pull-right"><a href="#placeholders-tickets-for-sale">{{ __('Placeholders') }}</a></li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane active" id="ru-tickets-for-sale">{!! Form::textarea('settings[mail_tickets_for_sale_ru]', old('mail_tickets_for_sale_ru', setting('mail_tickets_for_sale_ru', null)), array('class'=>'form-control ckeditor-classic', 'id' => 'mail_tickets_for_sale_ru')) !!}</div>
                                <div class="tab-pane" id="de-tickets-for-sale">{!! Form::textarea('settings[mail_tickets_for_sale_de]', old('mail_tickets_for_sale_de', setting('mail_tickets_for_sale_de', null)), array('class'=>'form-control ckeditor-classic', 'id' => 'mail_tickets_for_sale_de')) !!}</div>
                                <div class="tab-pane" id="en-tickets-for-sale">{!! Form::textarea('settings[mail_tickets_for_sale_en]', old('mail_tickets_for_sale_en', setting('mail_tickets_for_sale_en', null)), array('class'=>'form-control ckeditor-classic', 'id' => 'mail_tickets_for_sale_en')) !!}</div>
                                <div class="tab-pane" id="placeholders-tickets-for-sale">
                                    <hr style="margin: 0 0 20px 0">
                                    <ul style="list-style-type: none">
                                        <li>
                                            <a href="#" class="btn btn-default copy-placeholder" data-mail-placeholder="@OrderId" data-toggle="tooltip" title="{{ __('Copy') }}">
                                                <span class="fa fa-copy" role="presentation" aria-hidden="true"></span>
                                            </a>
                                            {{ __(':placeholder - order number', ['placeholder' => '@OrderId']) }}
                                        </li>
                                        <li>
                                            <a href="#" class="btn btn-default copy-placeholder" data-mail-placeholder="@SiteUrl" data-toggle="tooltip" title="{{ __('Copy') }}">
                                                <span class="fa fa-copy" role="presentation" aria-hidden="true"></span>
                                            </a>
                                            {{ __(':placeholder - link to the website', ['placeholder' => '@SiteUrl']) }}
                                        </li>
                                        <li>
                                            <a href="#" class="btn btn-default copy-placeholder" data-mail-placeholder="@TicketsList" data-toggle="tooltip" title="{{ __('Copy') }}">
                                                <span class="fa fa-copy" role="presentation" aria-hidden="true"></span>
                                            </a>
                                            {{ __(':placeholder - list of ordered tickets', ['placeholder' => '@TicketsList']) }}
                                        </li>
                                        <li>
                                            <a href="#" class="btn btn-default copy-placeholder" data-mail-placeholder="@Amount" data-toggle="tooltip" title="{{ __('Copy') }}">
                                                <span class="fa fa-copy" role="presentation" aria-hidden="true"></span>
                                            </a>
                                            {{ __(':placeholder - payment amount', ['placeholder' => '@Amount']) }}
                                        </li>
                                        <li>
                                            <a href="#" class="btn btn-default copy-placeholder" data-mail-placeholder="@Currency" data-toggle="tooltip" title="{{ __('Copy') }}">
                                                <span class="fa fa-copy" role="presentation" aria-hidden="true"></span>
                                            </a>
                                            {{ __(':placeholder - order currency', ['placeholder' => '@Currency']) }}
                                        </li>
                                        <li>
                                            <a href="#" class="btn btn-default copy-placeholder" data-mail-placeholder="@ClientName" data-toggle="tooltip" title="{{ __('Copy') }}">
                                                <span class="fa fa-copy" role="presentation" aria-hidden="true"></span>
                                            </a>
                                            {{ __(':placeholder - client name', ['placeholder' => '@ClientName']) }}
                                        </li>
                                        <li>
                                            <a href="#" class="btn btn-default copy-placeholder" data-mail-placeholder="@TicketOfficesList" data-toggle="tooltip" title="{{ __('Copy') }}">
                                                <span class="fa fa-copy" role="presentation" aria-hidden="true"></span>
                                            </a>
                                            {{ __(':placeholder - cash list', ['placeholder' => '@TicketOfficesList']) }}
                                        </li>
                                        <li>
                                            <a href="#" class="btn btn-default copy-placeholder" data-mail-placeholder="@WeWillCallYouMessage" data-toggle="tooltip" title="{{ __('Copy') }}">
                                                <span class="fa fa-copy" role="presentation" aria-hidden="true"></span>
                                            </a>
                                            {{ __(':placeholder - message \'we will call you\'', ['placeholder' => '@WeWillCallYouMessage']) }}
                                        </li>
                                        <li>
                                            <a href="#" class="btn btn-default copy-placeholder" data-mail-placeholder="@BankRequisites" data-toggle="tooltip" title="{{ __('Copy') }}">
                                                <span class="fa fa-copy" role="presentation" aria-hidden="true"></span>
                                            </a>
                                            {{ __(':placeholder - bank details', ['placeholder' => '@BankRequisites']) }}
                                        </li>
                                    </ul>
                                    <hr style="margin: 20px 0 0 0">
                                </div>
                            </div>
                        </div>
                        <div class="form-group col-md-12">
                            {!! Form::label('mail_courier_delivery_ru', __('Successful processing of the reserve with courier delivery')) !!}
                            <ul class="nav nav-pills">
                                <li class="active"><a href="#ru-courier-delivery">RU</a></li>
                                <li><a href="#de-courier-delivery">DE</a></li>
                                <li><a href="#en-courier-delivery">EN</a></li>
                                <li class="pull-right"><a href="#placeholders-courier-delivery">{{ __('Placeholders') }}</a></li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane active" id="ru-courier-delivery">{!! Form::textarea('settings[mail_courier_delivery_ru]', old('mail_courier_delivery_ru', setting('mail_courier_delivery_ru', null)), array('class'=>'form-control ckeditor-classic', 'id' => 'mail_courier_delivery_ru')) !!}</div>
                                <div class="tab-pane" id="de-courier-delivery">{!! Form::textarea('settings[mail_courier_delivery_de]', old('mail_courier_delivery_de', setting('mail_courier_delivery_de', null)), array('class'=>'form-control ckeditor-classic', 'id' => 'mail_courier_delivery_de')) !!}</div>
                                <div class="tab-pane" id="en-courier-delivery">{!! Form::textarea('settings[mail_courier_delivery_en]', old('mail_courier_delivery_en', setting('mail_courier_delivery_en', null)), array('class'=>'form-control ckeditor-classic', 'id' => 'mail_courier_delivery_en')) !!}</div>
                                <div class="tab-pane" id="placeholders-courier-delivery">
                                    <hr style="margin: 0 0 20px 0">
                                    <ul style="list-style-type: none">
                                        <li>
                                            <a href="#" class="btn btn-default copy-placeholder" data-mail-placeholder="@OrderId" data-toggle="tooltip" title="{{ __('Copy') }}">
                                                <span class="fa fa-copy" role="presentation" aria-hidden="true"></span>
                                            </a>
                                            {{ __(':placeholder - order number', ['placeholder' => '@OrderId']) }}
                                        </li>
                                        <li>
                                            <a href="#" class="btn btn-default copy-placeholder" data-mail-placeholder="@SiteUrl" data-toggle="tooltip" title="{{ __('Copy') }}">
                                                <span class="fa fa-copy" role="presentation" aria-hidden="true"></span>
                                            </a>
                                            {{ __(':placeholder - link to the website', ['placeholder' => '@SiteUrl']) }}
                                        </li>
                                        <li>
                                            <a href="#" class="btn btn-default copy-placeholder" data-mail-placeholder="@TicketsList" data-toggle="tooltip" title="{{ __('Copy') }}">
                                                <span class="fa fa-copy" role="presentation" aria-hidden="true"></span>
                                            </a>
                                            {{ __(':placeholder - list of ordered tickets', ['placeholder' => '@TicketsList']) }}
                                        </li>
                                        <li>
                                            <a href="#" class="btn btn-default copy-placeholder" data-mail-placeholder="@Amount" data-toggle="tooltip" title="{{ __('Copy') }}">
                                                <span class="fa fa-copy" role="presentation" aria-hidden="true"></span>
                                            </a>
                                            {{ __(':placeholder - payment amount', ['placeholder' => '@Amount']) }}
                                        </li>
                                        <li>
                                            <a href="#" class="btn btn-default copy-placeholder" data-mail-placeholder="@Currency" data-toggle="tooltip" title="{{ __('Copy') }}">
                                                <span class="fa fa-copy" role="presentation" aria-hidden="true"></span>
                                            </a>
                                            {{ __(':placeholder - order currency', ['placeholder' => '@Currency']) }}
                                        </li>
                                        <li>
                                            <a href="#" class="btn btn-default copy-placeholder" data-mail-placeholder="@ClientName" data-toggle="tooltip" title="{{ __('Copy') }}">
                                                <span class="fa fa-copy" role="presentation" aria-hidden="true"></span>
                                            </a>
                                            {{ __(':placeholder - client name', ['placeholder' => '@ClientName']) }}
                                        </li>
                                        <li>
                                            <a href="#" class="btn btn-default copy-placeholder" data-mail-placeholder="@TicketOfficesList" data-toggle="tooltip" title="{{ __('Copy') }}">
                                                <span class="fa fa-copy" role="presentation" aria-hidden="true"></span>
                                            </a>
                                            {{ __(':placeholder - cash list', ['placeholder' => '@TicketOfficesList']) }}
                                        </li>
                                    </ul>
                                    <hr style="margin: 20px 0 0 0">
                                </div>
                            </div>
                        </div>
                        <div class="form-group col-md-12">
                            <div class="checkbox">
                                <label>
                                    {!! Form::hidden('settings[mail_notify_e-ticket_list]', 0) !!}
                                    {!! Form::checkbox('settings[mail_notify_e-ticket_list]', 1, old('mail_notify_e-ticket_list', setting('mail_notify_e-ticket_list', null))) !!}
                                    {!! __('Send letter with list of e-tickets') !!}
                                </label>
                            </div>
                        </div>
                        <div class="form-group col-md-12">
                            {!! Form::label('mail_e-ticket_list_ru', __('Letter with list of e-tickets')) !!}
                            <ul class="nav nav-pills">
                                <li class="active"><a href="#ru-e-ticket-list">RU</a></li>
                                <li><a href="#de-e-ticket-list">DE</a></li>
                                <li><a href="#en-e-ticket-list">EN</a></li>
                                <li class="pull-right"><a href="#placeholders-e-ticket-list">{{ __('Placeholders') }}</a></li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane active" id="ru-e-ticket-list">{!! Form::textarea('settings[mail_e-ticket_list_ru]', old('mail_e-ticket_list_ru', setting('mail_e-ticket_list_ru', null)), array('class'=>'form-control ckeditor-classic', 'id' => 'mail_e-ticket_list_ru')) !!}</div>
                                <div class="tab-pane" id="de-e-ticket-list">{!! Form::textarea('settings[mail_e-ticket_list_de]', old('mail_e-ticket_list_de', setting('mail_e-ticket_list_de', null)), array('class'=>'form-control ckeditor-classic', 'id' => 'mail_e-ticket_list_de')) !!}</div>
                                <div class="tab-pane" id="en-e-ticket-list">{!! Form::textarea('settings[mail_e-ticket_list_en]', old('mail_e-ticket_list_en', setting('mail_e-ticket_list_en', null)), array('class'=>'form-control ckeditor-classic', 'id' => 'mail_e-ticket_list_en')) !!}</div>
                                <div class="tab-pane" id="placeholders-e-ticket-list">
                                    <hr style="margin: 0 0 20px 0">
                                    <ul style="list-style-type: none">
                                        <li>
                                            <a href="#" class="btn btn-default copy-placeholder" data-mail-placeholder="@OrderId" data-toggle="tooltip" title="{{ __('Copy') }}">
                                                <span class="fa fa-copy" role="presentation" aria-hidden="true"></span>
                                            </a>
                                            {{ __(':placeholder - order number', ['placeholder' => '@OrderId']) }}
                                        </li>
                                        <li>
                                            <a href="#" class="btn btn-default copy-placeholder" data-mail-placeholder="@SiteUrl" data-toggle="tooltip" title="{{ __('Copy') }}">
                                                <span class="fa fa-copy" role="presentation" aria-hidden="true"></span>
                                            </a>
                                            {{ __(':placeholder - link to the website', ['placeholder' => '@SiteUrl']) }}
                                        </li>
                                        <li>
                                            <a href="#" class="btn btn-default copy-placeholder" data-mail-placeholder="@TicketsList" data-toggle="tooltip" title="{{ __('Copy') }}">
                                                <span class="fa fa-copy" role="presentation" aria-hidden="true"></span>
                                            </a>
                                            {{ __(':placeholder - list of ordered tickets', ['placeholder' => '@TicketsList']) }}
                                        </li>
                                        <li>
                                            <a href="#" class="btn btn-default copy-placeholder" data-mail-placeholder="@Amount" data-toggle="tooltip" title="{{ __('Copy') }}">
                                                <span class="fa fa-copy" role="presentation" aria-hidden="true"></span>
                                            </a>
                                            {{ __(':placeholder - payment amount', ['placeholder' => '@Amount']) }}
                                        </li>
                                        <li>
                                            <a href="#" class="btn btn-default copy-placeholder" data-mail-placeholder="@Currency" data-toggle="tooltip" title="{{ __('Copy') }}">
                                                <span class="fa fa-copy" role="presentation" aria-hidden="true"></span>
                                            </a>
                                            {{ __(':placeholder - order currency', ['placeholder' => '@Currency']) }}
                                        </li>
                                        <li>
                                            <a href="#" class="btn btn-default copy-placeholder" data-mail-placeholder="@ClientName" data-toggle="tooltip" title="{{ __('Copy') }}">
                                                <span class="fa fa-copy" role="presentation" aria-hidden="true"></span>
                                            </a>
                                            {{ __(':placeholder - client name', ['placeholder' => '@ClientName']) }}
                                        </li>
                                        <li>
                                            <a href="#" class="btn btn-default copy-placeholder" data-mail-placeholder="@TicketOfficesList" data-toggle="tooltip" title="{{ __('Copy') }}">
                                                <span class="fa fa-copy" role="presentation" aria-hidden="true"></span>
                                            </a>
                                            {{ __(':placeholder - cash list', ['placeholder' => '@TicketOfficesList']) }}
                                        </li>
                                    </ul>
                                    <hr style="margin: 20px 0 0 0">
                                </div>
                            </div>
                        </div>
                        <div class="form-group col-md-12">
                            <div class="checkbox">
                                <label>
                                    {!! Form::hidden('settings[mail_notify_registration_confirmation]', 0) !!}
                                    {!! Form::checkbox('settings[mail_notify_registration_confirmation]', 1, old('mail_notify_registration_confirmation', setting('mail_notify_registration_confirmation', null))) !!}
                                    {!! __('Send letter with registration confirmation') !!}
                                </label>
                            </div>
                        </div>
                        <div class="form-group col-md-12">
                            {!! Form::label('mail_registration_confirmation_ru', __('Letter with registration confirmation')) !!}
                            <ul class="nav nav-pills">
                                <li class="active"><a href="#ru-registration-confirmation">RU</a></li>
                                <li><a href="#de-registration-confirmation">DE</a></li>
                                <li><a href="#en-registration-confirmation">EN</a></li>
                                <li class="pull-right"><a href="#placeholders-registration-confirmation">{{ __('Placeholders') }}</a></li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane active" id="ru-registration-confirmation">{!! Form::textarea('settings[mail_registration_confirmation_ru]', old('mail_registration_confirmation_ru', setting('mail_registration_confirmation_ru', null)), array('class'=>'form-control ckeditor-classic', 'id' => 'mail_registration_confirmation_ru')) !!}</div>
                                <div class="tab-pane" id="de-registration-confirmation">{!! Form::textarea('settings[mail_registration_confirmation_de]', old('mail_registration_confirmation_de', setting('mail_registration_confirmation_de', null)), array('class'=>'form-control ckeditor-classic', 'id' => 'mail_registration_confirmation_de')) !!}</div>
                                <div class="tab-pane" id="en-registration-confirmation">{!! Form::textarea('settings[mail_registration_confirmation_en]', old('mail_registration_confirmation_en', setting('mail_registration_confirmation_en', null)), array('class'=>'form-control ckeditor-classic', 'id' => 'mail_registration_confirmation_en')) !!}</div>
                                <div class="tab-pane" id="placeholders-registration-confirmation">
                                    <hr style="margin: 0 0 20px 0">
                                    <ul style="list-style-type: none">
                                        <li>
                                            <a href="#" class="btn btn-default copy-placeholder" data-mail-placeholder="@ClientName" data-toggle="tooltip" title="{{ __('Copy') }}">
                                                <span class="fa fa-copy" role="presentation" aria-hidden="true"></span>
                                            </a>
                                            {{ __(':placeholder - client name', ['placeholder' => '@ClientName']) }}
                                        </li>
                                        <li>
                                            <a href="#" class="btn btn-default copy-placeholder" data-mail-placeholder="@SiteUrl" data-toggle="tooltip" title="{{ __('Copy') }}">
                                                <span class="fa fa-copy" role="presentation" aria-hidden="true"></span>
                                            </a>
                                            {{ __(':placeholder - link to the website', ['placeholder' => '@SiteUrl']) }}
                                        </li>
                                        <li>
                                            <a href="#" class="btn btn-default copy-placeholder" data-mail-placeholder="@UserProfileURL" data-toggle="tooltip" title="{{ __('Copy') }}">
                                                <span class="fa fa-copy" role="presentation" aria-hidden="true"></span>
                                            </a>
                                            {{ __(':placeholder - link to enter client profile', ['placeholder' => '@UserProfileURL']) }}
                                        </li>
                                        <li>
                                            <a href="#" class="btn btn-default copy-placeholder" data-mail-placeholder="@Login" data-toggle="tooltip" title="{{ __('Copy') }}">
                                                <span class="fa fa-copy" role="presentation" aria-hidden="true"></span>
                                            </a>
                                            {{ __(':placeholder - client login = Email', ['placeholder' => '@Login']) }}
                                        </li>
                                        <li>
                                            <a href="#" class="btn btn-default copy-placeholder" data-mail-placeholder="@Password" data-toggle="tooltip" title="{{ __('Copy') }}">
                                                <span class="fa fa-copy" role="presentation" aria-hidden="true"></span>
                                            </a>
                                            {{ __(':placeholder - client password', ['placeholder' => '@Password']) }}
                                        </li>
                                    </ul>
                                    <hr style="margin: 20px 0 0 0">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="box-footer">

                    <button type="submit" class="btn btn-success">
                        <span class="fa fa-save" role="presentation" aria-hidden="true"></span> <span>{{ __('Save') }}</span>
                    </button>

                </div>
            </div>

            {!! Form::close() !!}

        </div>
    </div>
@endsection

@section('after_scripts')
    @include('Admin::partials.form-scripts')

    <script>
        $('.nav-tabs a, .nav-pills a').click(function(e) {
            e.preventDefault()
            $(this).tab('show')
        });

        $('.copy-placeholder').click(function(e) {
            e.preventDefault();
            var placeholder = $(this).data('mail-placeholder');
            copyToClipboard(placeholder);
        });
    </script>
@endsection