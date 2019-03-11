<ol class="breadcrumb">
    <li><a href="{{ url('dashboard') }}">{{ trans('Admin::admin.partials-header-title') }}</a></li>
    <li><a href="{{ url($menuRoute->plural_name) }}" class="text-capitalize">{{ trans('Admin::admin.' . $menuRoute->title) }}</a></li>
    <li class="active">{{ trans('Admin::admin.' . $action) }}</li>
</ol>