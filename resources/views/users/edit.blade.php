@extends('layouts.app')

@section('title', 'Settings')
@section('content')
	<h1>Edit profile</h1>
	<form id="edit-form" class="form-horizontal image-form settings" role="form" method="POST" action="{{ route('settings') }}" enctype="multipart/form-data">
		{{ csrf_field() }}
		<input type="hidden" name="_method" value="PATCH">

		<div class="row">
			<div class="col-md-6 pull-right">
				<div class="col-md-12">
					<div class="form-group{{ $errors->has('img') ? ' has-error' : '' }}">
						<div class="col-md-12">
							<label class="control-label">Profile picture</label><br>
							<input id="img" type="hidden" name="img">
							<div class="text-center">
								<img id="edit-img" src="{{ $user->img or 'img/profile.png' }}" alt="{{ $user->username }}">
							</div>
							<div class="text-center">
								<div id="preview-img">
							</div>
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
			</div>
			<div class="col-md-6">
				<div class="col-md-12">
					<div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
						<div class="col-md-12">
							<label for="email" class="control-label">E-Mail Address</label><br>
							<input id="email" type="email" class="form-control" name="email" value="{{ $user->email }}" required>

							@if ($errors->has('email'))
								<span class="help-block">
								<strong>{{ $errors->first('email') }}</strong>
							</span>
							@endif
						</div>
					</div>
				</div>

				<div class="col-md-12">
					<div class="form-group{{ $errors->has('firstname') ? ' has-error' : '' }}">
						<div class="col-md-12">
							<label for="firstname" class="control-label">First name</label><br>
							<input id="firstname" type="text" class="form-control" name="firstname" value="{{ $user->firstname or '' }}" required autofocus>

							@if ($errors->has('firstname'))
								<span class="help-block">
								<strong>{{ $errors->first('firstname') }}</strong>
							</span>
							@endif
						</div>
					</div>
				</div>

				<div class="col-md-12">
					<div class="form-group{{ $errors->has('lastname') ? ' has-error' : '' }}">
						<div class="col-md-12">
							<label for="lastname" class="control-label">Last name</label>
							<input id="lastname" type="text" class="form-control" name="lastname" value="{{ $user->lastname or '' }}" required autofocus>

							@if ($errors->has('lastname'))
								<span class="help-block">
								<strong>{{ $errors->first('lastname') }}</strong>
							</span>
							@endif
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<div class="col-md-6">
					<div class="form-group{{ $errors->has('password_old') ? ' has-error' : '' }}">
						<div class="col-md-12">
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
		</div>
		<div class="row">
			<div class="col-md-12">
				<div class="col-md-6">
					<div class="form-group{{ $errors->has('s_password') ? ' has-error' : '' }}">
						<div class="col-md-12">
							<label for="password" class="control-label">Password</label>
							<input id="password" type="password" class="form-control" name="s_password">

							@if ($errors->has('password'))
								<span class="help-block">
							<strong>{{ $errors->first('s_password') }}</strong>
						</span>
							@endif
						</div>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<div class="col-md-12">
							<label for="s_password-confirm" class="control-label">Confirm Password</label>
							<input id="s_password-confirm" type="password" class="form-control" name="password_confirmation">
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<div class="col-md-12">
					<h3>Address</h3>
					<p><em>This is solely used to filter lobbies by distance to your home.</em></p>
				</div>
				<div class="col-md-6">
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<div class="col-md-9">
									<label for="street" class="control-label">Street</label><br>
									@if ($errors->has('street'))
										<span class="help-block">
											<strong>{{ $errors->first('street') }}</strong>
										</span>
									@endif
									<input id="street" type="text" class="form-control" name="street" value="{{ $user->street }}" autofocus>
								</div>
								<div class="col-md-3">
									<label for="number" class="control-label">Number</label><br>
									@if ($errors->has('number'))
										<span class="help-block">
											<strong>{{ $errors->first('number') }}</strong>
										</span>
									@endif
									<input id="number" type="text" class="form-control" name="number" value="{{ $user->number }}">
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<div class="col-md-5">
									<label for="zip" class="control-label">ZIP-code</label><br>
									@if ($errors->has('zip'))
										<span class="help-block">
											<strong>{{ $errors->first('zip') }}</strong>
										</span>
									@endif
									<input id="zip" type="text" class="form-control" name="zip" value="{{ $user->zip }}" placeholder="1207" autofocus>
								</div>
								<div class="col-md-7">
									<label for="city" class="control-label">City</label><br>
									@if ($errors->has('city'))
										<span class="help-block">
											<strong>{{ $errors->first('city') }}</strong>
										</span>
									@endif
									<input id="city" type="text" class="form-control" name="city" value="{{ $user->city }}" placeholder="Fakopolis">
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-6">
					<input id="coords" type="hidden" name="coords">
					<input id="country" type="hidden" name="country">
					<div id="map"></div>
				</div>

			</div>
		</div>

		<div class="row">
			<div class="col-md-12">
				<div class="col-md-12">
					<label for="range" class="control-label">Range</label>
					<p><em>The radius in which to look for lobbies with Looking For Group</em></p>
					<div class="row">
						<div class="col-md-8 col-md-offset-2">
							<div class="form-group text-center">
								<input id="range" name="range" data-slider-id='range-slider' type="text" data-slider-min="10" data-slider-max="200" data-slider-step="10" data-slider-value="{{ $user->range }}"/>
								<p class="text-center">
									<span class="range">10</span>km
								</p>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<div class="form-group">
					<div class="col-md-6 col-md-offset-4">
						<button type="submit" class="btn btn-primary">
							Save
						</button>
					</div>
				</div>
			</div>
		</div>
	</form>
@stop

@section('js')
	<script src="js/libs/croppie.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-slider/9.7.0/bootstrap-slider.min.js"></script>
	<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDjI_a7-CJA5anDE0q3NSBHoccjlL31Dmk"></script>
	<script src="js/forms.js"></script>
	<script src="js/edit-form-img.js"></script>
	<script>
        $(function() {
            $('#range').slider({
                formatter: function(value) {
                    $('span.range').text(value);
                }
            });
		})
	</script>
@stop
