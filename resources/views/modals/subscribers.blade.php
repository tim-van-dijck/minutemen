<div class="modal fade" id="subscribers" tabindex="-1" role="dialog" aria-labelledby="subscribersLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title text-center" id="subscribersLabel">Subscribers</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6 col-md-offset-3 subscribers">
                        @foreach($organisation->subscribers() as $index => $sub)
                            <div class="row blocklink-wrapper">
                                <div class="col-md-12 blocklink user">
                                    <a href="{{ route('users.show', ['slug' => $sub->slug]) }}">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="profile-img">
                                                    <img src="{{ $sub->img or 'img/profile.png' }}" alt="{{ $sub->username }}" title="{{ $sub->username }}">
                                                </div>
                                            </div>
                                            <div class="col-md-9">
                                                <p>{{ $sub->username }}</p>
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