$(function() {
    findLobby();
});

function findLobby() {
    $.getJSON('ajax/lfg/find-lobby', function(data) {
        if (data.success != null) {
            swal(
                {
                    title: "We found you a lobby!",
                    text: 'If you click "ok" we\'ll whisk you away to your new playmates',
                    type: "success"
                }, function() {
                    window.location.replace(data.link);
                }
            );
        } else {
            swal(
                {
                    title: "Whoopsie daisy!",
                    text: data.error+'\nDo you want to make a new lobby?',
                    type: "error",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Yes, let's go for it",
                    closeOnConfirm: true,
                    closeOnCancel: false
                },
                function(isConfirmed) {
                    if (isConfirmed) {
                        $.get('ajax/lfg', function() {
                            window.location.replace(base_url+'lobbies/create');
                        });
                    } else {
                        swal(
                            {
                                title: "Continue search?",
                                text: 'Do you want to keep looking for lobbies?',
                                type: "warning",
                                showCancelButton: true,
                                confirmButtonColor: "#DD6B55",
                                confirmButtonText: "Yes, please",
                                cancelButtonText: "No, I'm fine!",
                                closeOnConfirm: true,
                                closeOnCancel: true
                            },
                            function(alsoConfirmed) {
                                console.log(alsoConfirmed);
                                if (alsoConfirmed) {
                                    setTimeout(findLobby, 10000);
                                }
                                else {
                                    toggleLfg();
                                }
                            }
                        );

                        console.log(isConfirmed);
                    }
                }
            )
        }
    });
}