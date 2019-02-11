<header class="main-header">
    <!-- Logo -->
    <a href="{{ url('') }}" class="logo">
        <!-- mini logo for sidebar mini 50x50 pixels -->
        <span class="logo-mini">
                <img width="25px" height="25px" src="{{ asset('images/logo.png') }}">
        </span>
        <!-- logo for regular state and mobile devices -->
        <span class="logo-lg">
            {{--<img width="25px" height="25px" src="{{ asset('images/logo.png') }}">--}}
            <span>{!! 'Admin panel' !!}</span>
        </span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top" role="navigation">
        <!-- Sidebar toggle button-->
        <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </a>

        <div class="navbar-custom-menu">
                @if (Auth::guest())
                    <ul class="nav navbar-nav">
                        <li><a href="{{ route('login') }}">{{ trans('Admin::auth.login-login') }}</a></li>
                        @if (config('admin.registrationEnabled'))
                            <li><a href="{{ route('register') }}">{{ trans('Admin::auth.register') }}</a></li>
                        @endif
                    </ul>
                @else
                    {!! Form::open(['url' => 'logout']) !!}
                    <ul class="nav navbar-nav">
                        <li>
                            <a id="logout-button" href="{{ route('logout') }}"><i class="fa fa-btn fa-sign-out"></i> {{ trans('Admin::auth.logout') }}</a>
                        </li>
                    </ul>
                    {!! Form::close() !!}
                @endif
        </div>
    </nav>
</header>