@foreach($events as $event)
  <li class="list-group-item event-item">
    <div class="media-left">
      <a href="{{ route('events.widgetContent', [$event->id]) }}" class="show-widget" data-event-id="{{ $event->id }}">
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
        <div class="col-md-6 event-statistic" data-url="{{ route('order.eventStatistic', ['event' => $event->id]) }}">

        </div>
      </div>
    </div>
  </li>
@endforeach