<div class="modal fade" id="settle-game" tabindex="-1" role="dialog" aria-labelledby="Settle game" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">Who won?</h4>
            </div>
            <div class="modal-body">
                <form id="settle-game-form" action="" method="POST">
                    {{  csrf_field() }}
                    <div class="team_1">
                        <input id="team_1" type="radio" name="winner" value="1">
                        <label for="team_1"></label>
                    </div>
                    <div class="draw">
                        <input id="draw" type="radio" name="winner" value="0">
                        <label for="draw">Draw</label>
                    </div>
                    <div class="team_2">
                        <input id="team_2" type="radio" name="winner" value="2">
                        <label for="team_2"></label>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary" form="settle-game-form">Confirm</button>
            </div>
        </div>
    </div>
</div>