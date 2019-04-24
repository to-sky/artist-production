@include('Admin::partials.header')
@include('partials.topbar')

<!-- Content Wrapper -->
<div class="content-wrapper">

    <!-- Page Header -->
@yield('page-header')

<!-- Main content -->
    <section class="content">
        @yield('content')
    </section>
    <!-- /.content -->

</div>
<!-- /.content-wrapper -->

@include('Admin::partials.footer')