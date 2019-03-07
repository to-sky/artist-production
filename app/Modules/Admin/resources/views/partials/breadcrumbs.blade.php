<ol class="breadcrumb">
    <li><a href="{{ url('dashboard') }}">{{ trans('Admin::admin.partials-header-title') }}</a></li>
    <li><a href="{{ url($menu->plural_name) }}" class="text-capitalize">{{ trans('Admin::admin.' . $menu->title) }}</a></li>
    <li class="active">{{ trans('Admin::admin.' . $action) }}</li>
</ol>