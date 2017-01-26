<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<base href="{{ url('/') }}">

	<!-- CSRF Token -->
	<meta name="csrf-token" content="{{ csrf_token() }}">

	<title>@yield('title') | {{ config('app.name', 'Laravel') }}</title>

	<link rel="apple-touch-icon" sizes="57x57" href="favicon/apple-icon-57x57.png">
	<link rel="apple-touch-icon" sizes="60x60" href="favicon/apple-icon-60x60.png">
	<link rel="apple-touch-icon" sizes="72x72" href="favicon/apple-icon-72x72.png">
	<link rel="apple-touch-icon" sizes="76x76" href="favicon/apple-icon-76x76.png">
	<link rel="apple-touch-icon" sizes="114x114" href="favicon/apple-icon-114x114.png">
	<link rel="apple-touch-icon" sizes="120x120" href="favicon/apple-icon-120x120.png">
	<link rel="apple-touch-icon" sizes="144x144" href="favicon/apple-icon-144x144.png">
	<link rel="apple-touch-icon" sizes="152x152" href="favicon/apple-icon-152x152.png">
	<link rel="apple-touch-icon" sizes="180x180" href="favicon/apple-icon-180x180.png">
	<link rel="icon" type="image/png" sizes="192x192"  href="favicon/android-icon-192x192.png">
	<link rel="icon" type="image/png" sizes="32x32" href="favicon/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="96x96" href="favicon/favicon-96x96.png">
	<link rel="icon" type="image/png" sizes="16x16" href="favicon/favicon-16x16.png">
	<meta name="msapplication-TileImage" content="favicon/ms-icon-144x144.png">

	<!-- Styles -->
	<link href="https://fonts.googleapis.com/css?family=Oswald" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Raleway:400,700" rel="stylesheet">
	<link href="https://cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.min.css" rel="stylesheet">
	<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
	<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-slider/9.7.0/css/bootstrap-slider.min.css" rel="stylesheet">

	<link href="/css/app.css" rel="stylesheet">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
	<link href="/css/libs/croppie.css" rel="stylesheet">
	<link href="/css/libs/sweetalert.css" rel="stylesheet">
	<link href="/css/libs/menu_sideslide.css" rel="stylesheet">
	<link href="/css/style.css" rel="stylesheet">

	<!-- Scripts -->
	<script>
		window.Laravel = <?php echo json_encode([
			'csrfToken' => csrf_token(),
		]); ?>
	</script>
</head>
<body>
	<div id="app">
		<div class="menu-shadow {{ (Auth::guest()) ? 'menu-hidden' : '' }}"></div>
		<div class="menu-wrap {{ (Auth::guest()) ? 'menu-hidden' : '' }}">
			<nav class="menu">
				<div class="icon-list">
					<form id="search" action="search" method="GET">
						<button type="submit" class="pull-right btn btn-primary"><i class="fa fa-search"></i></button>
						<input class="pull-right" type="text" name="q" placeholder="Search Minutemen">
					</form>
					@if (Auth::guest())
						<a data-toggle="modal" data-target="#login-modal">Login</a>
						<a data-toggle="modal" data-target="#register-modal">Register</a>
					@else
						<div class="block">
							<label class="switch pull-right">
								<input id="lfg" type="checkbox" name="lfg" data-toggle="toggle" {{ (Auth::user()->lfg) ? 'checked' : '' }}>
								<div class="slider"></div>
							</label>
							<span>Looking For Group</span>
						</div>
						<a {{ (Request::is('dashboard') ? 'class=active ' : '') }}href="/dashboard"><i class="fa fa-fw fa-dashboard"></i>Dashboard</a>
						<a {{ (Request::is('profile') ? 'class=active ' : '') }}href="{{ route('users.profile') }}"><i class="fa fa-fw fa-user"></i>Profile</a>
						<a {{ (Request::is('friends') ? 'class=active ' : '') }}href="{{ route('users.friends') }}">
							<div class="abs-wrapper">
								<div class="friend-bubble"></div>
								<i class="fa fa-fw fa-users"></i>Friends
							</div>
						</a>
						<a {{ (Request::is('conversations')) ? 'class=active' : '' }} href="{{ route('conversations.index') }}">
							<div class="abs-wrapper">
								<div class="message-bubble"></div>
								<i class="fa fa-fw fa-comments"></i>Conversations
							</div>
						</a>
						<a {{ (Request::is('notifications')) ? 'class=active' : '' }} href="{{ route('users.notifications') }}">
							<div class="abs-wrapper">
								<div class="notification-bubble"></div>
								<i class="fa fa-fw fa-bell"></i>Notifications
							</div>
						</a>
						<a href="{{ route('settings') }}"><i class="fa fa-fw fa-cogs"></i>Settings</a>
						@if (Auth::user()->admin == 1)
							<a href="{{ route('admin') }}"></a>
						@else
							<div class="lists menu-hidden">
							@if (!Auth::user()->subscriptions()->isEmpty())
								<div class="item">
									<h5>Subscriptions</h5>
									@foreach(Auth::user()->subscriptions() as $org)
										<a href="{{ route('organisations.show', ['id' => $org->id]) }}">
											<img src="{{ $org->thumb or 'img/organisation.png' }}" alt="{{ $org->name }}" class="tiny-thumb">{{ $org->name }}
										</a>
									@endforeach
									<a href="/my-teams">See more...</a>
								</div>
							@endif
							@if (!Auth::user()->teams()->isEmpty())
								<div class="item">
									<h5>Teams</h5>
									@foreach(Auth::user()->teams() as $team)
										<a href="{{ route('teams.show', ['slug' => $team->slug]) }}">
											<img src="{{ $team->emblem or 'img/emblem.png' }}" alt="{{ $team->name }}" class="tiny-thumb">{{ $team->name }}
										</a>
									@endforeach
									<a href="/my-teams">See more...</a>
								</div>
							@endif
							@if (!Auth::user()->organisations()->isEmpty())
								<div class="item">
									<h5>Organisations</h5>
									@foreach(Auth::user()->organisations() as $org)
										<a href="{{ route('organisations.show', ['id' => $org->id]) }}">
											<img src="{{ $org->thumb or 'img/organisation.png' }}" alt="{{ $org->name }}" class="tiny-thumb">{{ $org->name }}
										</a>
									@endforeach
									<a href="/my-organisations">More...</a>
								</div>
							@endif
						</div>
						@endif
						<a href="{{ url('/logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="fa fa-fw fa-power-off"></i>Logout</a>
						<form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">{{ csrf_field() }}</form>
					@endif
				</div>
			</nav>
			<button class="close-button" id="close-button">Close Menu</button>
		</div>
		<nav class="navbar navbar-default navbar-static-top">

			<div class="container">
				<button class="menu-button {{ (Auth::guest()) ? 'menu-hidden' : '' }}" id="open-button">
					<span class="abs-wrapper">
						<span class="excl-bubble">!</span>
						<i class="fa fa-bars"></i>
					</span>
				</button>
				<div class="navbar-header">

					<!-- Branding Image -->
					<a id="logo" class="navbar-brand" href="{{ (Auth::check()) ? route('dashboard') : url('/') }}">
						{!! file_get_contents('img/logo.svg') !!}
					</a>
				</div>
				@if (Auth::guest())
					<ul class="nav navbar-nav navbar-left">
						<li><a data-toggle="modal" data-target="#login-modal">Login</a></li>
						<li><a data-toggle="modal" data-target="#register-modal">Register</a></li>
					</ul>
				@endif
			</div>
		</nav>

		@include('partial.success')
		@include('partial.error')

		@if (Request::is('/'))
			<div class="container-fluid" class="embed-responsive embed-responsive-16by9" id="video-container">
				<div class="video-overlay"></div>
				<a href="#" class="scrollDown"><i class="fa fa-2x fa-chevron-down"></i></a>
				<div class="hero">
					<h1 class="title">Minutemen</h1>
					<p>Laser tag at a minute's notice</p>
					<p class="accent"><b>CONNECT. COMPETE. ENJOY.</b></p>
				</div>
				<div class="row">
					<video autoplay muted loop class="embed-responsive-item" id="autovid">
						<source src="{{ 'video/action-supercut.mp4' }}" type="video/mp4">
						<source src="{{ 'video/action-supercut.webm' }}" type="video/webm">
						Your browser does not support the video tag.
					</video>
				</div>
			</div>
		@endif
		<div class="container">
			<div class="row">
				@if (Auth::check())
					<div class="col-md-2 sidebar">
						<h5>
							<a class="pull-right accent sidebar-add" href="{{ route('teams.create') }}"><i class="fa fa-plus"></i></a>
							Your Teams
						</h5>
						<ul>
							@forelse (Auth::user()->teams() as $team)
								<li><a href="{{ route('teams.show', ['slug' => $team->slug]) }}">{{ $team->name }}</a></li>
							@empty
								<li>No teams yet</li>
							@endforelse
						</ul>
						<h5>
							<a class="pull-right accent sidebar-add" href="{{ route('organisations.create') }}"><i class="fa fa-plus"></i></a>
							Your Organisations
						</h5>
						<ul>
							@forelse (Auth::user()->organisations() as $org)
								<li><a href="{{ route('organisations.show', ['id' => $org->id]) }}">{{ $org->name }}</a></li>
							@empty
								<li>No organisations yet</li>
							@endforelse
						</ul>
					</div>
				@endif
				<div class="col-md-8 {{ (Auth::check()) ? '' : 'col-md-offset-2' }}">
					<main>
						@yield('content')
					</main>
				</div>
				
				@if (Auth::check())
					<div class="col-md-2 sidebar">
						<h5>Your Subscriptions</h5>
						<ul>
							@forelse (Auth::user()->subscriptions() as $sub)
								<li><a href="{{ route('organisations.show', ['id' => $sub->id]) }}">{{ $sub->name }}</a></li>
							@empty
								<li class="empty">No subscriptions yet.</li>
							@endforelse
						</ul>
					</div>
				@endif
			</div>
		</div>

		<footer>
			<div class="container">
				<div class="row">
					<div class="col-md-12">
						<div class="row">
							<div class="col-md-9">
								<div class="row">
									<div class="col-md-12">
										<span class="title">Laser Tag enthusiasts</span>
										<p>
											Minutemen is a community of Laser Tag enthusiasts coming together to make friends and compete.
										</p>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12">
										<span class="title">Contact us</span>
										<p><a class="contact" href="mailto:info@minutemen.be">info@minutemen.be</a></p>
									</div>
								</div>
							</div>
							<div class="col-md-3 text-right">
								<div class="row">
									<div class="col-md-12">
										<a href="{{ route('home') }}#about" class="">About us</a>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12">
										<a href="sitemap" class="sitemap">Sitemap</a>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12">
										<p class="copy">&copy; All rights reserved</p>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</footer>
		@if (Auth::guest() && isset($errors))
			@include('auth.login')
			@include('auth.register')
		@endif
	</div>

	<!-- Scripts -->
	<script src="js/app.js"></script>
	<script src="js/libs/autosize.min.js"></script>
	<script src="js/libs/classie.js"></script>
	<script src="js/libs/sweetalert.min.js"></script>
	<script src="js/libs/main.js"></script>
	<script src="js/animations.js"></script>
	<script>
        var base_url = '{{ asset('/') }}';
        var addressSet = {{ (isset(Auth::user()->lat) && isset(Auth::user()->long)) }}
	</script>
	@if (Auth::check())
		<script src="js/notifications.js"></script>
		<script src="js/interactions.js"></script>
	@elseif(isset($errors) && $errors->any())
		<script>
			$(function() {
				var loginErrors = '{{ ($errors->has('username') || $errors->has('password')) ? 'true' : 'false' }}';
				registerErrors = (loginErrors === 'true');
				var registerErrors = '{{ ($errors->has('r_username') || $errors->has('r_password') || $errors->has('email')) ? 'true' : 'false' }}';
				registerErrors = (registerErrors === 'true');
				if (loginErrors) { $('#login-modal').modal('show'); }
				if (registerErrors) { $('#register-modal').modal('show'); }
			});
		</script>
	@endif
	<script src="js/lobby-find.js"></script>
	@yield('js')
</body>
</html>
