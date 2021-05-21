            <footer class="main-footer">
                <div class="pull-right hidden-xs">
                    Developed by <a target="_blank" href="https://rhinoda.com">Rhinoda</a>
                </div>
                {{  '© ' . date('Y') . ' ' . config('app.name') . '.'}}
            </footer>
        </div>
        <!-- ./wrapper -->


        @yield('before_scripts')

        @include('Admin::partials.javascripts')
        @include('Admin::partials.alerts')

        @yield('after_scripts')

    </body>
</html>