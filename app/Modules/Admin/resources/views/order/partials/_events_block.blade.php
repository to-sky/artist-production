    <div class="box">
        <div class="box-body">
            <div class="row">
                <div class="col-md-5">
                    <label for="period">{{ __('Admin::admin.select-item', ['item' => __('period')]) }}</label>

                    <div id="period" class="period-search">
                        <i class="fa fa-calendar"></i>&nbsp;
                        <span></span> <i class="fa fa-caret-down"></i>
                    </div>
                    <input type="hidden" name="period[]" id="periodFrom">
                    <input type="hidden" name="period[]" id="periodTo">
                </div>
                <div class="col-md-5">
                    <label for="findByName">{{ __('Search') }}</label>
                    <select name="search" id="findByName" class="select2-box form-control">
                        <option value="all">{{ __('All events') }}</option>
                        @foreach($events->unique('name') as $event)
                            <option value="{{ $event->name }}">{{ $event->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="button" id="findEvents" class="btn btn-primary form-control label-top-offset">
                        <i class="fa fa-search"> {{ __('Search') }}</i>
                    </button>
                </div>
            </div>

            <br>

            <div class="row">
                <div class="col-md-12">
                    <ul id="eventList" class="list-group">
                        @foreach($events as $event)
                            <li class="list-group-item event-item">
                                <div class="media-left">
                                    <a href="#">
                                        <img class="media-object event-thumbnail"
                                             src="{{ $event->image_url }}"
                                             alt="{{ $event->name }}"
                                             width="80">
                                    </a>
                                </div>
                                <div class="media-body">
                                    <div class="row">
                                        <div class="col-md-6 event-item--data">
                                            <h4 class="media-heading text-light-blue">
                                                <a href="{{ route('events.widgetContent', [$event->id]) }}"
                                                   class="show-widget"
                                                    data-event-id="{{ $event->id }}">
                                                    {{ $event->name }}
                                                </a>
                                            </h4>
                                            <p class="text-sm text-bold" data-date="{{ $event->date->toDateString() }}">
                                                {{ $event->date->formatLocalized('%d %B %Y %H:%M, %A') }}
                                            </p>
                                            <p>{{ $event->hall->building->city->name }}</p>
                                            <p>{{ $event->hall->building->name }}</p>
                                            <p>{{ $event->hall->name }}</p>
                                        </div>
                                        <div class="col-md-6">
                                            <h4 class="media-heading">{{ __('All prices') }}</h4>
                                            @foreach($event->prices as $price)
                                                <p class="prices-count">
                                                    <span class="label" style="background: {{ $price->color }};" >
                                                        {{ $price->price }} &euro;
                                                    </span>
                                                    <span class="text-sm text-muted">
                                                        &nbsp;{{ $price->ticket()->available()->get()->count() }} {{ __('pc') }}.
                                                    </span>
                                                </p>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
