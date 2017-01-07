<div class="modal fade" id="invite-lfg" tabindex="-1" role="dialog" aria-labelledby="inviteLfgLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="inviteLfgLabel">Looking for Group</h4>
            </div>
            <div class="modal-body">
                <form action="" id="invite-lfg-form" method="POST">
                    {{ csrf_field() }}
                    <div class="lfg">
                        <div class="row blocklink-wrapper">
                        </div>
                    </div>
                </form>
                <a href="#" class="btn load-lfg btn-load">Load more</a>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary" form="invite-lfg-form">Invite</button>
            </div>
        </div>
    </div>
</div>