<div class="form-group col-md-12">
    {!! Form::label('avatar', __('Avatar')) !!}
    @if (!empty($user->avatar->thumbUrl))
        <div id="user-avatar" style="margin: 0 0 10px 0">
            <img style="margin: 0 10px 0 0" src="{{ $user->avatar->thumbUrl }}" width="150px" alt="User Image">
            <a id="remove-avatar" href="{{ route(config('admin.route') . '.users.removeAvatar', ['id' => $user->id]) }}">{{ __('Remove')  }}</a>
        </div>
    @endif
    {!! Form::file('avatar', [
      'accept' => FileHelper::mimesImage(),
    ]) !!}

    <input type="hidden" name="MAX_FILE_SIZE" value="{{ FileHelper::maxUploadSize() }}">
</div>
<div class="form-group col-md-12">
    {!! Form::label('first_name', __('First name')) !!}
    {!! Form::text('first_name', old('first_name', $user->first_name), ['class'=>'form-control', 'placeholder'=> __('First name')]) !!}
</div>

<div class="form-group col-md-12">
    {!! Form::label('last_name', __('Last name')) !!}
    {!! Form::text('last_name', old('last_name', $user->last_name), ['class'=>'form-control', 'placeholder'=> __('Last name')]) !!}
</div>

<div class="form-group col-md-12">
    {!! Form::label('email', __('Email')) !!}*
    {!! Form::email('email', old('email', $user->email), ['class'=>'form-control', 'placeholder'=> trans('Admin::admin.users-edit-email_placeholder')]) !!}
</div>

<div class="form-group col-md-12">
    {!! Form::label('password', __('Password')) !!}
    {!! Form::password('password', ['class'=>'form-control', 'placeholder'=> __('Password')]) !!}
</div>
<div class="form-group col-md-12">
    {!! Form::label('password_confirmation', __('Confirm Password')) !!}
    {!! Form::password('password_confirmation', ['class'=>'form-control', 'placeholder'=> __('Confirm Password')]) !!}
</div>

<div class="form-group col-md-12">
    {!! Form::label('role_id', trans('Admin::admin.users-edit-role')) !!}
    {!! Form::select('role_id', $roles, old('role_id', $user->roles()->pluck('id')->first()), ['class'=>'form-control']) !!}
</div>