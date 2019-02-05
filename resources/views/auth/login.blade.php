@extends('Admin::layouts.master')

@section('content')
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="box box-default">

                <div class="box-header with-border">
                    <div class="box-title">{{ trans('Admin::auth.login-login') }}</div>
                </div>

                <div class="box-body">
                    <form class="form-horizontal" role="form" method="POST" action="{{ route('login') }}">
                        {!! csrf_field() !!}

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label">{{ trans('Admin::auth.login-email') }}</label>

                            <div class="col-md-6">
                                <input type="email" class="form-control" name="email" value="{{ old('email') }}">

                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label">{{ trans('Admin::auth.login-password') }}</label>

                            <div class="col-md-6">
                                <input type="password" class="form-control" name="password">

                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="remember"> {{ trans('Admin::auth.login-remember_me') }}
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ trans('Admin::auth.login-btnlogin') }}
                                </button>

                                <a class="btn btn-link" href="{{ route('password.request') }}">{{ trans('Admin::auth.login-forgot_your_password') }}</a>
                            </div>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
@endsection
