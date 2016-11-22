$('button.search-icon').click(function(e) {
	e.preventDefault();
	$(this).hide();
	$('#search form')
		.css('width', 0)
		.toggleClass('hidden')
		.animate({width: '100%'});
});

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