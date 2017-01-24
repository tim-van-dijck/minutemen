<div class="modal fade" id="enter-event" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title text-center">Pick a team to sign up with</h4>
            </div>
            <div class="modal-body">
                <form id="enter-form" action="{{ route('events.enter', ['id' => $event->id]) }}" method="POST">
                    {{ csrf_field() }}
                    <select name="team" id="team-find"></select>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary" form="enter-form">Save changes</button>
            </div>
        </div>
    </div>
</div>