
        <!-- Footer -->
        <footer class="ap_footer">

            <div class="ap_footer__logo">
                <img class="ap-footer__logo-image" src="{{ asset('images/logo-circle.png') }}" alt="Artists Production logo">
                <p class="ap-footer__copy">&copy; artist-production.de, 2019</p>
            </div>

            <div class="ap_footer__block">
                <p class="ap_footer__text"><a href="#">Политика конфиденциальности</a></p>
                <p class="ap_footer__text"><a href="#">Условия возврата</a></p>
            </div>

            <div class="ap_footer__block">
                <p class="ap_footer__text"><a href="#">AGB</a></p>
                <p class="ap_footer__text"><a href="#">Impressum</a></p>
            </div>

            <div class="ap_footer__block">
                <img width="46" src="{{ asset('images/visa.png') }}" alt="" class="ap_footer__image">
                <img width="58" src="{{ asset('images/klarma.png') }}" alt="" class="ap_footer__image">
            </div>

            <div class="ap_footer__block">
                <img width="74" src="{{ asset('images/paypal.png') }}" alt="" class="ap_footer__image">
                <img width="46" src="{{ asset('images/mastercard.png') }}" alt="" class="ap_footer__image">
            </div>

        </footer>

    </div>
    @yield('before_scripts')

    @include('partials.javascripts')

    @yield('after_scripts')
</body>
</html>