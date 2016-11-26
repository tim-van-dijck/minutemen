@extends('layouts.app')

@section('content')
	<h1>Edit profile</h1>
	<form id="edit-form" class="form-horizontal" role="form" method="POST" action="{{ route('users.update', ['id' => $user->id]) }}" enctype="multipart/form-data">
		{{ csrf_field() }}
		<input type="hidden" name="_method" value="PATCH">

		<div class="row">
			<div class="col-md-6 pull-right">
				<div class="form-group{{ $errors->has('img') ? ' has-error' : '' }}">
					<div class="col-md-8 col-md-offset-2">
						<label class="control-label">Profile picture</label><br>
						<input id="img" type="hidden" name="img">
						<div id="preview-img">
						</div>
						<label for="full-img" class="form-control img-label">
							<span>Browse</span> <input id="full-img" type="file" class="hidden" name="full-img" value="{{ old('img') }}" accept="image/*">
						</label>

						@if ($errors->has('img'))
							<span class="help-block">
								<strong>{{ $errors->first('img') }}</strong>
							</span>
						@endif
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
					<div class="col-md-8 col-md-offset-2">
						<label for="email" class="control-label">E-Mail Address</label><br>
						<input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required>

						@if ($errors->has('email'))
							<span class="help-block">
								<strong>{{ $errors->first('email') }}</strong>
							</span>
						@endif
					</div>
				</div>

				<div class="form-group{{ $errors->has('firstname') ? ' has-error' : '' }}">
					<div class="col-md-8 col-md-offset-2">
						<label for="firstname" class="control-label">First name</label><br>
						<input id="firstname" type="text" class="form-control" name="firstname" value="{{ old('firstname') }}" required autofocus>

						@if ($errors->has('firstname'))
							<span class="help-block">
								<strong>{{ $errors->first('firstname') }}</strong>
							</span>
						@endif
					</div>
				</div>

				<div class="form-group{{ $errors->has('lastname') ? ' has-error' : '' }}">
					<div class="col-md-8 col-md-offset-2">
						<label for="lastname" class="control-label">Last name</label>
						<input id="lastname" type="text" class="form-control" name="lastname" value="{{ old('lastname') }}" required autofocus>

						@if ($errors->has('lastname'))
							<span class="help-block">
								<strong>{{ $errors->first('lastname') }}</strong>
							</span>
						@endif
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-6">
				<div class="form-group{{ $errors->has('password_old') ? ' has-error' : '' }}">
					<div class="col-md-8 col-md-offset-2">
						<label for="password_old">Old password</label>
						<input id="password_old" type="password" class="form-control" name="password_old">

						@if ($errors->has('password_old'))
							<span class="help-block">
								<strong>{{ $errors->first('password_old') }}</strong>
							</span>
						@endif
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-6">
				<div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
					<div class="col-md-8 col-md-offset-2">
						<label for="password" class="control-label">Password</label>
						<input id="password" type="password" class="form-control" name="password">

						@if ($errors->has('password'))
							<span class="help-block">
								<strong>{{ $errors->first('password') }}</strong>
							</span>
						@endif
					</div>
				</div>
			</div>

			<div class="col-md-6">
				<div class="form-group">
					<div class="col-md-8 col-md-offset-2">
						<label for="password-confirm" class="control-label">Confirm Password</label>
						<input id="password-confirm" type="password" class="form-control" name="password_confirmation">
					</div>
				</div>
			</div>
		</div>

		<div class="form-group">
			<div class="col-md-6 col-md-offset-4">
				<button type="submit" class="btn btn-primary">
					Register
				</button>
			</div>
		</div>
	</form>
@stop

@section('js')
	<script src="js/libs/croppie.min.js"></script>
	<script src="js/forms.js"></script>
@stop
