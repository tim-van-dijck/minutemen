<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<base href="{{ url('/') }}">

	<!-- CSRF Token -->
	<meta name="csrf-token" content="{{ csrf_token() }}">

	<title>{{ config('app.name', 'Laravel') }}</title>

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
	<link href="/css/app.css" rel="stylesheet">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
	<link href="/css/style.css" rel="stylesheet">
	<link href="/css/libs/croppie.css" rel="stylesheet">
	<link href="/css/libs/sweetalert.css" rel="stylesheet">

	<!-- Scripts -->
	<script>
		window.Laravel = <?php echo json_encode([
			'csrfToken' => csrf_token(),
		]); ?>
	</script>
</head>
<body>
	<div id="app">
		<nav class="navbar navbar-default navbar-static-top">
			<div class="container">
				<div class="navbar-header">

					<!-- Collapsed Hamburger -->
					<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
						<span class="sr-only">Toggle Navigation</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>

					<!-- Branding Image -->
					<a id="logo" class="navbar-brand" href="{{ (Auth::check()) ? url('home') : url('/') }}">
						{!! file_get_contents('img/logo.svg') !!}
					</a>
				</div>

				<div class="collapse navbar-collapse" id="app-navbar-collapse">
					<!-- Left Side Of Navbar -->
					<ul class="nav navbar-nav">
						<li id="search">
							<form class="hidden" action="search" method="GET">
								<input class="pull-left" type="text" name="q">
								<button type="submit"><i class="fa fa-search"></i></button>
							</form>
							<button class="search-icon "><i class="fa fa-search"></i></button>
						</li>
					</ul>

					<!-- Right Side Of Navbar -->
					<ul class="nav navbar-nav navbar-right">
						<!-- Authentication Links -->
						@if (Auth::guest())
							<li><a class="login" href="{{ url('/login') }}">Login</a></li>
							<li><a href="{{ url('/register') }}">Register</a></li>
						@else
							<li><a href="{{ route('teams.index') }}">Teams</a></li>
							<li class="dropdown">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
									<div class="abs-wrapper">
										<div class="notification-bubble"></div>
									</div>
									{{ Auth::user()->username }} <span class="caret"></span>
								</a>

								<ul class="dropdown-menu" role="menu">
									<li>
										<a href="{{ route('users.friends') }}">
											<div class="abs-wrapper">
												<div class="notification-bubble"></div>
											</div>
											Friends
										</a>
									</li>
									<li><a href="{{ route('users.profile') }}">Profile</a></li>
									<li>
										<a href="{{ url('/logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
										<form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">{{ csrf_field() }}</form>
									</li>
								</ul>
							</li>
						@endif
					</ul>
				</div>
			</div>
		</nav>
		@include('partial.success')
		@include('partial.error')
		<div class="container">
			<div class="row">
				@if (Auth::check())
					<div class="col-md-2 sidebar">
						<h5>Teams</h5>
						<ul>
							@forelse (App\Team::mine() as $team)
								<li><a href="{{ route('teams.show', ['slug' => $team->slug]) }}">{{ $team->name }}</a></li>
							@empty
								<li>You're not currently part of any team</li>
							@endforelse
						</ul>
						<h5>Your Organisations</h5>
						<ul>
							@forelse (App\Organisation::mine() as $org)
								<li><a href="{{ route('organisations.show', ['slug' => $org->id]) }}">{{ $org->name }}</a></li>
							@empty
								<li>You're not currently part of any organisation</li>
							@endforelse
						</ul>
					</div>
				@endif
				<div class="col-md-8 {{ (Auth::check()) ? '' : 'col-md-offset-2' }}">
					@yield('content')
				</div>
				
				@if (Auth::check())
					<div class="col-md-2 sidebar">
						<h5>Subscriptions</h5>
						<ul>
							@forelse (App\Organisation::subscriptions() as $sub)
								<li><a href="{{ route('subscriptions.show', ['slug' => $sub->id]) }}">{{ $sub->name }}</a></li>
							@empty
								<li>No subscriptions yet.</li>
							@endforelse
						</ul>
					</div>
				@endif
			</div>
		</div>
		
		@if (Auth::guest())
			@include('auth.login')
		@endif
	</div>

	<!-- Scripts -->
	<script src="js/app.js"></script>
	<script src="js/libs/sweetalert.min.js"></script>
	<script src="js/libs/autosize.min.js"></script>
	<script src="js/animations.js"></script>
	@if (Auth::check())
		<script src="js/notifications.js"></script>
	@endif
	@yield('js')
</body>
</html>
