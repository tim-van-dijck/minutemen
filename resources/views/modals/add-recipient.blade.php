<div class="modal fade" id="add-recipient" tabindex="-1" role="dialog" aria-labelledby="addRecipientLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="addRecipientLabel">Add recipients</h4>
            </div>
            <div class="modal-body">
                <form id="add-recipients-form" action="{{ route('ajax.conversation.add-recipients', ['conversation_id' => $conversation->id]) }}" method="POST">
                    {{  csrf_field() }}
                    <p class="info">Choose from friends & team mates</p>
                    <select name="invite[]" id="user-find" multiple="multiple"></select>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary" form="add-recipients-form">Confirm</button>
            </div>
        </div>
    </div>
</div>