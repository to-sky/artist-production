<ol class="breadcrumb">
    <li><a href="{{ url('dashboard') }}">{{ trans('Admin::admin.partials-header-title') }}</a></li>
    <li><a href="{{ url($menu->plural_name) }}" class="text-capitalize">{{ $menu->title }}</a></li>
    <li class="active">{{ ucfirst($action) }}</li>
</ol>