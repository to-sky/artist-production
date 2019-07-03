<!-- Widget modal -->
<div class="widget-wrapper">
    <div class="widget-content">

        <div class="widget-header">
            <div class="widget-header--data">
                <img src="{{ $event->eventImage->file_url }}"
                     id="widgetEventThumbnail"
                     class="widget-header--img"
                     alt="{{ $event->name }}"
                     width="50">

                <div class="widget-header--info">
                    <h4 class="text-primary text-bold">{{ $event->name }}</h4>

                    <div class="widget-hall-location">
                        <i class="fa fa-building"></i>
                        <span>{{ $event->hall->building->name }}</span>
                        <i class="fa fa-map-marker"></i>
                        <span>{{ $event->hall->building->city->name }}</span>
                    </div>

                    <div class="widget-event-date">
                        <i class="fa fa-calendar"></i>
                        <span class="text-bold">{{ $event->date->format('d.m.Y') }}</span>
                        |
                        <span class="text-bold">{{ $event->date->format('H:i') }}</span>
                        <i class="fa fa-clock-o"></i>
                    </div>
                </div>
            </div>
            <div class="widget-header--close">
                <button type="button" class="close widget-close-btn">
                    <i class="fa fa-chevron-right text-info"></i>
                </button>
            </div>
        </div>

        <div class="widget-body">
            <div class="row">
                <div class="col-md-7">
                    <div id="widget" class="widget-block">
                        <iframe class="hall-widget-frame"
                                src="{{ route('hallWidget', [$event->id]) }}#lang:{{ app()->getLocale() }},no_header,no_checkout"
                                frameborder="0"></iframe>
                    </div>
                </div>

                <div class="col-md-5">
                    <div class="widget-tickets">
                        <table class="table table-striped text-center">
                            <tr id="widgetTicketsHeader">
                                <td>{{ __('Row') }}</td>
                                <td>{{ __('Place') }}</td>
                                <td>{{ __('Price') }}</td>
                                <td>{{ __('Admin::admin.delete') }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
