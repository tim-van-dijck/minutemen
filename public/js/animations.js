$(function () {
	if ($('.login').length > 0 && !$('.row.login').hasClass('hidden')) { $('.alert-danger').closest('.row').hide(); }

	$('.navbar-nav a.login').click(function(e) {
		e.preventDefault();
		$('.row.login').removeClass('hidden');
	});

	$('.shadow, .login .close').click(function(e) {
		e.preventDefault();
		$('.row.login').addClass('hidden');
	});

	$('body').keyup(function(e){
		if(e.keyCode == 27){
			$('.row.login').addClass('hidden');
		}
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
});