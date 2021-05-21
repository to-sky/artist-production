<!-- Header -->
<header class="ap_header">
    <a href="#" class="ap_header__logo">
        <img src="{{ asset('images/logo-circle.png') }}" alt="Artists Production logo" class="ap_header__logo-img">
        <p class="ap_header__logo-text">Artist Production</p>
    </a>
    <div class="ap_header__info">
        <a href="tel:+4961316272444" title="phone number" class="ap_header__phone">+49 6131 62 72 444</a>
        <p class="ap_header__info-text">Пн-Пт с 10:00 до 17:00</p>
    </div>
    @if(auth()->check())
        {!! Form::open(['url' => 'logout', 'class' => 'ap_header__profile-button']) !!}
            <button class="ap_header__button">{!! __('Exit from personal account') !!}</button>
        {!! Form::close() !!}
    @else
        <a href="{{ route('profile.show') }}" class="ap_header__button ap_header__profile-button">{!! __('Go to personal account') !!}</a>
    @endif
    <div class="ap_header__mobile">
        <div class="ap_header__burgermenu">
          <button class="trigger"></button>

          <ul class="ap_header__burgermenu-list">
            @if(auth()->check())
              {!! LinkMenu::make('menus.client')->setView('components.links-block-mobile') !!}

              <li>
                {!! Form::open(['url' => 'logout']) !!}
                <button class="ap_header__inline">{!! __('Logout') !!}</button>
                {!! Form::close() !!}
              </li>
            @else
              <li>
                <a href="{{ route('profile.show') }}" class="ap_header__inline">{!! __('Login') !!}</a>
              </li>
            @endif

            <li class="info">
              <a href="tel:+4961316272444" title="phone number" class="ap_header__phone">+49 6131 62 72 444</a>
              <p class="ap_header__info-text">Пн-Пт с 10:00 до 17:00</p>
            </li>
          </ul>
        </div>
    </div>
</header>
