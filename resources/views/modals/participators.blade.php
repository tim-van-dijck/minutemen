<div class="modal fade" id="participators" tabindex="-1" role="dialog" aria-labelledby="participatorsLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title text-center" id="participatorsLabel">Participators</h4>
            </div>
            <div class="modal-body">
                <div class="row persist-cols">
                    <div class="col-md-6 col-md-offset-3 friends">
                        @foreach($event->participators() as $index => $team)
                            <div class="row blocklink-wrapper">
                                <div class="col-md-12 blocklink user">
                                    <a href="{{ route('teams.show', ['slug' => $team->slug]) }}">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="profile-img">
                                                    <img src="{{ $team->emblem or 'img/emblem.png' }}" alt="{{ $team->name }}" title="{{ $team->name }}">
                                                </div>
                                            </div>
                                            <div class="col-md-9">
                                                <p>{{ $team->name }}</p>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>