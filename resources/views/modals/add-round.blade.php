<div class="modal fade" id="add-round" tabindex="-1" role="dialog" aria-labelledby="addRoundLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="addRoundLabel">Add round</h4>
            </div>
            <div class="modal-body">
                <form id="add-round-form" action="{{ route('events.add-round', ['event_id' => $event->id]) }}" method="POST">
                    {{  csrf_field() }}
                    <input class="form-control" type="text" name="name" placeholder="Round N">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary" form="add-round-form">Confirm</button>
            </div>
        </div>
    </div>
</div>