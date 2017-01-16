<div class="modal fade" id="invite-players" tabindex="-1" role="dialog" aria-labelledby="invitePlayersLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="invitePlayersLabel">Invite to lobby</h4>
            </div>
            <div class="modal-body">
                <form id="invite-players-form" action="{{ route('ajax.lobby.invite', ['id' => $lobby->id]) }}" method="POST">
                    {{  csrf_field() }}
                    <select name="invite[]" id="user-find" multiple="multiple"></select>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary" form="invite-players-form">Confirm</button>
            </div>
        </div>
    </div>
</div>