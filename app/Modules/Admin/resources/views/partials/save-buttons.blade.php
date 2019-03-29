<div id="save-buttons" class="form-group">

    <input type="hidden" name="save_redirect" value="{{ $saveButtons['active']['redirect'] }}">

    <div class="btn-group">

        <button type="submit" class="btn btn-success">
            <span class="fa fa-save" role="presentation" aria-hidden="true"></span> <span data-redirect="{{ $saveButtons['active']['redirect'] }}">{{ $saveButtons['active']['text'] }}</span>
        </button>

        <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aira-expanded="false">
            <span class="caret"></span>
            <span class="sr-only">&#x25BC;</span>
        </button>

        <ul class="dropdown-menu">
            @foreach( $saveButtons['list'] as $redirect => $text)
            <li><a href="javascript:void(0);" data-redirect="{{ $redirect }}">{{ $text }}</a></li>
            @endforeach
        </ul>

    </div>

    <a href="{{ route(config('admin.route') . '.' . $menuRoute->plural_name . '.index') }}" class="btn btn-default"><span class="fa fa-ban"></span>&nbsp;{{ trans('Admin::admin.cancel') }}</a>
</div>