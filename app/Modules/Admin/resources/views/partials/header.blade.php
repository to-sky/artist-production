<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- Encrypted CSRF token for Laravel, in order for Ajax requests to work --}}
    <meta name="csrf-token" content="{{ csrf_token() }}"/>

    <title>
        {{ isset($title) ? $title : config('app.name').' Admin' }}
    </title>

    @yield('before_styles')

    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.5 -->
    <link rel="stylesheet" href="{{ asset('/bower_components/bootstrap/dist/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker3.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/bower_components/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/bower_components/font-awesome/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/bower_components/Ionicons/css/ionicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/bower_components/jquery-ui/themes/base/jquery-ui.min.css') }}">

    <link rel="stylesheet" href="{{ asset('/bower_components/admin-lte/dist/css/AdminLTE.min.css') }}">
    <!-- AdminLTE Skins. Choose a skin from the css/skins folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="{{ asset('/bower_components/admin-lte/dist/css/skins/_all-skins.min.css') }}">

    <link rel="stylesheet" href="{{ asset('/bower_components/admin-lte/plugins/pace/pace.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/pnotify/pnotify.custom.min.css') }}">

    <link rel="stylesheet" href="{{ asset('admin/css/admin.css') }}?v=2">
    <link rel="stylesheet" href="{{ asset('admin/css/admin.bold.css') }}">
    <link rel="stylesheet" href="{{ asset('/bower_components/select2/dist/css/select2.css') }}">

    @yield('after_styles')

<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body class="hold-transition {{ config('admin.skin') }} sidebar-mini">
    <script type="text/javascript">
        /* Recover sidebar state */
        (function () {
            if (Boolean(sessionStorage.getItem('sidebar-toggle-collapsed'))) {
                var body = document.getElementsByTagName('body')[0];
                body.className = body.className + ' sidebar-collapse';
            }
        })();
    </script>
    <!-- Site wrapper -->
    <div class="wrapper">
