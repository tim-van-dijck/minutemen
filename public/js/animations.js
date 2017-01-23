$(function () {
	$('.navbar-nav a.login').click(function(e) {
		e.preventDefault();
		$('.row.login').removeClass('hidden');
	});

	$('.shadow, .login .close').click(function(e) {
		e.preventDefault();
		$('.row.login').addClass('hidden');
	});

	if ($('.banner').length > 0) {
		$('.banner').css('height', $('.banner').width()/2.5);
		$( window ).resize(function() { $('.banner').css('height', $('.banner').width()/12*5); });
	}

	$('.switch').click(function(e) { e.stopPropagation(); });

	$('.scrollDown').click(function(e) {
		e.preventDefault();
        $('html, body').animate({
            scrollTop: $("#app > .container").offset().top
        }, 500);
	});

	$('#login-modal #register-link').click(function (e) {
		e.preventDefault();
		$('#login-modal').modal('hide');
		$('#register-modal').modal('show');
	});
});