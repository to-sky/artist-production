{{-- Show the errors, if any --}}
@if ($errors->any())
    <div class="callout callout-danger">
        <h4>{{ trans('Admin::admin.please-fix') }}</h4>
        <ul>
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif