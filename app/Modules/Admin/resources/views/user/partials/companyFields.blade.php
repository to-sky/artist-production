{{--<div class="form-group col-md-12">--}}
    {{--{!! Form::label('avatar', __('Avatar')) !!}--}}
    {{--@if (!empty($user->avatar->thumbUrl))--}}
        {{--<div id="user-avatar" style="margin: 0 0 10px 0">--}}
            {{--<img style="margin: 0 10px 0 0" src="{{ $user->avatar->thumbUrl }}" width="150px" alt="User Image">--}}
            {{--<a id="remove-avatar" href="{{ route(config('admin.route') . '.users.removeAvatar', ['id' => $user->id]) }}">{{ __('Remove')  }}</a>--}}
        {{--</div>--}}
    {{--@endif--}}
    {{--{!! Form::file('avatar') !!}--}}
{{--</div>--}}
<div class="form-group col-md-12">
    {!! Form::label('settings[company_name]', __('Company Name')) !!}
    {!! Form::text('settings[company_name]', old('settings.company_name', setting('company_name')), ['class'=>'form-control', 'placeholder'=> __('Company Name')]) !!}
</div>

<div class="form-group col-md-12">
  {!! Form::label('settings[company_address]', __('Address')) !!}
  {!! Form::text('settings[company_address]', old('settings.company_address', setting('company_address')), ['class'=>'form-control', 'placeholder'=> __('Address')]) !!}
</div>

<div class="form-group col-md-12">
  {!! Form::label('settings[company_post_code]', __('Post Code')) !!}
  {!! Form::text('settings[company_post_code]', old('settings.company_post_code', setting('company_post_code')), ['class'=>'form-control', 'placeholder'=> __('Post Code')]) !!}
</div>

<div class="form-group col-md-12">
  {!! Form::label('settings[company_city]', __('City')) !!}
  {!! Form::text('settings[company_city]', old('settings.company_city', setting('company_city')), ['class'=>'form-control', 'placeholder'=> __('City')]) !!}
</div>

<div class="form-group col-md-12">
  {!! Form::label('settings[company_website]', __('Website')) !!}
  {!! Form::text('settings[company_website]', old('settings.company_website', setting('company_website')), ['class'=>'form-control', 'placeholder'=> __('Website')]) !!}
</div>

<div class="form-group col-md-12">
  {!! Form::label('settings[company_mail]', __('E-mail')) !!}
  {!! Form::text('settings[company_mail]', old('settings.company_mail', setting('company_mail')), ['class'=>'form-control', 'placeholder'=> __('E-Mail')]) !!}
</div>

<div class="form-group col-md-12">
  {!! Form::label('settings[company_phone]', __('Phone')) !!}
  {!! Form::text('settings[company_phone]', old('settings.company_phone', setting('company_phone')), ['class'=>'form-control', 'placeholder'=> __('Phone')]) !!}
</div>

<div class="form-group col-md-12">
  {!! Form::label('settings[company_director]', __('Director Name')) !!}
  {!! Form::text('settings[company_director]', old('settings.company_director', setting('company_director')), ['class'=>'form-control', 'placeholder'=> __('Director Name')]) !!}
</div>

<div class="form-group col-md-12">
  {!! Form::label('settings[company_registered_by]', __('Registered by')) !!}
  {!! Form::text('settings[company_registered_by]', old('settings.company_registered_by', setting('company_registered_by')), ['class'=>'form-control', 'placeholder'=> __('Registered by')]) !!}
</div>

<div class="form-group col-md-12">
  {!! Form::label('settings[company_tax_number]', __('Tax Number')) !!}
  {!! Form::text('settings[company_tax_number]', old('settings.company_tax_number', setting('company_tax_number')), ['class'=>'form-control', 'placeholder'=> __('Tax Number')]) !!}
</div>

<div class="form-group col-md-12">
  {!! Form::label('settings[company_tin]', __('TIN')) !!}
  {!! Form::text('settings[company_tin]', old('settings.company_tin', setting('company_tin')), ['class'=>'form-control', 'placeholder'=> __('TIN')]) !!}
</div>


