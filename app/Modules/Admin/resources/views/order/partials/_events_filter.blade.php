    <div class="box">
        <div class="box-body">

            <div class="row">
                <div class="col-md-5">
                    <label for="period">Выберите период</label>
                    <div id="period" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
                        <i class="fa fa-calendar"></i>&nbsp;
                        <span></span> <i class="fa fa-caret-down"></i>
                    </div>
                    <input type="hidden" name="period" id="period">
                </div>
                <div class="col-md-5">
                    <label for="search">Поиск</label>
                    <input type="text" id="search" name="search" class="form-control">
                </div>
                <div class="col-md-2">
                    <button type="button" id="findEvents" class="btn btn-primary form-control label-top-offset">
                        <i class="fa fa-search"> Искать</i>
                    </button>
                </div>
            </div>

            <br>

            <div class="row">
                <div class="col-md-12">
                    <ul class="list-group">
                        @foreach($events as $event)
                            <li class="list-group-item">
                                <div class="media-left">
                                    <a href="#">
                                        <img class="media-object" src="{{ asset('images/no-image.jpg') }}" alt="{{ $event->name }}" width="80">
                                    </a>
                                </div>
                                <div class="media-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h4 class="media-heading text-light-blue">
                                                <a href="#" class="show-widget" data-event-id="{{ $event->id }}">{{ $event->name }}</a>
                                            </h4>
                                            <p>
                                                <b>{{ $event->date->format('d M Y, l') }}</b>
                                            </p>
                                            <p>{{ $event->hall->building->city->name }}</p>
                                            <p>{{ $event->hall->building->name }}</p>
                                            <p>{{ $event->hall->name }}</p>
                                        </div>
                                        <div class="col-md-6">
                                            <h4 class="media-heading">Все цены</h4>
                                            @foreach($event->prices as $price)
                                                    <p style="width: 50%; float: left;">
                                                        <span class="label" style="background: {{ $price->color }};" >{{ $price->price }} &euro;</span>
                                                        {!! str_repeat('&nbsp;', 4) !!}

                                                        {{-- TODO: change rand to ticket amount --}}
                                                        <span class="text-sm text-muted">{{ rand(0, 100) }} шт.</span>
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
