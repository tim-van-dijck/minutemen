<div class="modal fade" id="events" tabindex="-1" role="dialog" aria-labelledby="eventsLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title text-center" id="eventsLabel">Events</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6 col-md-offset-3 events">
                        @foreach($organisation->events() as $event)
                            <div class="row event">
                                <div class="col-md-12">
                                    <div class="blocklink">
                                        <a href="{{ route('events.show', ['id' => $event->id]) }}">
                                            <div class="row">
                                                <div class="col-md-1">
                                                    <p class="month">{{ strtoupper(date('M', strtotime($event->starts_at))) }}</p>
                                                    <p class="day">{{ date('d', strtotime($event->starts_at)) }}</p>
                                                </div>
                                                <div class="col-md-6 banner-wrapper">
                                                    <div class="banner">
                                                        <img src="{{ $event->banner or 'img/event.png' }}" alt="{{ $event->title }}">
                                                    </div>
                                                </div>
                                                <div class="col-md-5 data">
                                                    <div class="row">
                                                        <div class="col-md-10 col-md-offset-2">
                                                            <h4>{{ $event->title }}</h4>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-2"><i class="fa fa-map-marker accent"></i></div>
                                                        <div class="col-md-10">
                                                            <p class="address">
                                                                {{ $event->street }} {{ $event->number }}<br>
                                                                {{ $event->zip }} {{ $event->city }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>