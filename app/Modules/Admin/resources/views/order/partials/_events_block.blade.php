    <div class="box">
        <div class="box-body">
            <div class="row">
                <div class="col-md-12 col-lg-6">
                    <label for="period">{{ __('Admin::admin.select-item', ['item' => __('period')]) }}</label>

                    <div id="period" class="period-search">
                        <i class="fa fa-calendar"></i>&nbsp;
                        <span></span> <i class="fa fa-caret-down"></i>
                    </div>
                    <input type="hidden" name="period[]" id="periodFrom">
                    <input type="hidden" name="period[]" id="periodTo">
                </div>
                <div class="col-md-12 col-lg-6 order-search-name">
                    <label for="findByName">{{ __('Search') }}</label>
                    <select name="search" id="findByName" class="select2-box form-control">
                        <option value="all">{{ __('All events') }}</option>
                        @foreach($eventNames as $name)
                            <option value="{{ $name }}">{{ $name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-12">
                    <button type="button" id="findEvents" class="btn btn-primary form-control label-top-offset">
                        <i class="fa fa-search"> {{ __('Search') }}</i>
                    </button>
                </div>
            </div>

            <br>

            <div class="row">
                <div class="col-md-12">
                    <ul id="eventList" class="list-group">

                    </ul>
                </div>
            </div>
        </div>
    </div>
