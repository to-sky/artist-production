<header class="main-header">
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top" role="navigation">
        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
                <li class="dropdown">
                    @if(!empty($languages[$locale]))
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <span class="flag-icon flag-icon-{{ $languages[$locale]['flag'] }}"></span> <span>{{ $languages[$locale]['title']}}</span> <b class="caret"></b>
                        </a>
                    @else
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <span class="flag-icon flag-icon-{{ $languages[Config::get('app.locale')]['flag'] }}"></span> <span>{{ $languages[Config::get('app.locale')]['title']}}</span> <b class="caret"></b>
                        </a>
                    @endif
                    <ul  class="dropdown-menu">
                        @foreach($languages as $locale => $language)
                            <li><a href="{{ route('admin.locale', ['locale' => $locale]) }}"><span class="flag-icon flag-icon-{{ $language['flag'] }}"></span> <span>{{ $language['title'] }}</span></a></li>
                        @endforeach
                    </ul>
                </li>
            @if (Auth::guest())
                <li><a href="{{ route('login') }}">{{ trans('Admin::auth.login-login') }}</a></li>
                @if (config('admin.registrationEnabled'))
                    <li><a href="{{ route('register') }}">{{ trans('Admin::auth.register') }}</a></li>
                @endif
            @else
                <li>
                    <a id="logout-button" href="{{ route('logout') }}"><i class="fa fa-btn fa-sign-out"></i> {{ trans('Admin::auth.logout') }}</a>
                    {!! Form::open(['url' => 'logout', 'id' => 'logout-form']) !!}
                    {!! Form::close() !!}
                </li>
            @endif
            </ul>
        </div>
    </nav>
</header>