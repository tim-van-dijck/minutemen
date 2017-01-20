<div class="modal fade" id="kick" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">Modal title</h4>
            </div>
            <div class="modal-body">
                <p>Please confirm that you want to kick this user by entering your password</p>
                <form id="kick-form" action="{{ route('ajax.team.kick', ['team_id' => $team->id]) }}" method="POST">
                    {{  csrf_field() }}
                    <input type="hidden" name="member_id" id="member_id">
                    <input type="password" name="password" placeholder="********" required>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary" form="kick-form">Confirm</button>
            </div>
        </div>
    </div>
</div>