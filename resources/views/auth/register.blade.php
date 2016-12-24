@extends('layouts.app')

@section('title', 'Register')
@section('content')
	<h1>Register</h1>
	<p>We just need to get some basic info to get you started</p>
	<form id="register-form" class="form-horizontal image-form" role="form" method="POST" action="{{ url('/register') }}" enctype="multipart/form-data">
		{{ csrf_field() }}

		<div class="row">
			<div class="col-md-8 col-md-offset-2">
				<div class="form-group{{ $errors->has('username') ? ' has-error' : '' }}">
					<div class="col-md-12">
						<label for="username" class="control-label">Username</label><br>
						<input id="username" type="text" class="form-control" name="username" value="{{ old('username') }}" required autofocus>

						@if ($errors->has('username'))
							<span class="help-block">
								<strong>{{ $errors->first('username') }}</strong>
							</span>
						@endif
					</div>
				</div>

				<div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
					<div class="col-md-12">
						<label for="email" class="control-label">E-Mail Address</label><br>
						<i>We won't stalk you, we promise!</i>
						<input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required>

						@if ($errors->has('email'))
							<span class="help-block">
								<strong>{{ $errors->first('email') }}</strong>
							</span>
						@endif
					</div>
				</div>
				<div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
					<div class="col-md-12">
						<label for="password" class="control-label">Password</label>
						<input id="password" type="password" class="form-control" name="password" required>

						@if ($errors->has('password'))
							<span class="help-block">
								<strong>{{ $errors->first('password') }}</strong>
							</span>
						@endif
					</div>
				</div>
				<div class="form-group">
					<div class="col-md-12">
						<label for="password-confirm" class="control-label">Confirm Password</label>
						<input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
					</div>
				</div>
			</div>
		</div>


		<div class="form-group">
			<div class="col-md-2 col-md-offset-5">
				<button type="submit" class="btn btn-primary">Register</button>
			</div>
		</div>
	</form>
@stop

@section('js')
	<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDjI_a7-CJA5anDE0q3NSBHoccjlL31Dmk"></script>
	<script src="js/libs/croppie.min.js"></script>
	<script src="js/forms.js"></script>
@stop
