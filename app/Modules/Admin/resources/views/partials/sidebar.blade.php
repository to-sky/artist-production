@if (Auth::check())
    <!-- Left side column. contains the sidebar -->
    <aside class="main-sidebar">
        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">
            <div class="user-panel">
                <div class="pull-left image">
                    @if (!empty(Auth::user()->avatar->fileUrl))
                        <img src="{{ Auth::user()->avatar->fileUrl }}" class="img-circle" alt="User Image">
                    @else
                        <img src="{{ asset('images/no-avatar.png') }}" class="img-circle" alt="User Image">
                    @endif
                </div>
                <div class="pull-left info">
                    <p>{{ Auth::user()->fullname }}</p>
                    <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
                </div>
            </div>

            @include('Admin::partials.menu')

        </section>
        <!-- /.sidebar -->
    </aside>
@endif
