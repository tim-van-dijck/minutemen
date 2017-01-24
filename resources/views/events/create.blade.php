@extends('layouts.app')

@section('title', 'Create event')
@section('content')
	<h1>Create an event</h1>
	<form class="form-horizontal" role="form" method="POST" action="{{ route('events.store', ['id' => $organisation->id]) }}" enctype="multipart/form-data">
		{{ csrf_field() }}

		<div class="row">
			<div class="col-md-12">
				<div class="col-md-12">
					<div class="row">
						<div class="col-md-12">
							<div class="form-group banner-upload{{ $errors->has('img') ? ' has-error' : '' }}">
								<div class="col-md-12">
									<label class="control-label">Banner</label><br>
									<div class="banner">
										<img id="preview-banner" src="img/event.png">
									</div>
									<label for="banner" class="form-control img-label">
										<span>Browse</span><input id="banner" type="file" class="hidden" name="banner" value="{{ old('banner') }}" accept="image/*">
									</label>

									@if ($errors->has('banner'))
										<span class="help-block">
										<strong>{{ $errors->first('banner') }}</strong>
									</span>
									@endif
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
								<div class="col-md-12">
									<label for="title" class="control-label">Event title<i class="fa fa-asterisk"></i></label><br>
									<input id="title" type="text" class="form-control" name="title" value="{{ old('title') }}" placeholder="Awesome Cup" required autofocus>

									@if ($errors->has('title'))
										<span class="help-block">
												<strong>{{ $errors->first('title') }}</strong>
											</span>
									@endif
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="row">
								<div class="col-md-12">
									<h3>Address</h3>
									<div class="row">
										<div class="col-md-9">
											<label for="street" class="control-label">Street<i class="fa fa-asterisk"></i></label><br>
											@if ($errors->has('street'))
												<span class="help-block">
												<strong>{{ $errors->first('street') }}</strong>
											</span>
											@endif
											<input id="street" type="text" class="form-control" name="street" value="{{ old('street') }}" required autofocus>
										</div>
										<div class="col-md-3">
											<label for="number" class="control-label">Number<i class="fa fa-asterisk"></i></label><br>
											@if ($errors->has('number'))
												<span class="help-block">
												<strong>{{ $errors->first('number') }}</strong>
											</span>
											@endif
											<input id="number" type="text" class="form-control" name="number">
										</div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12">
									<div class="row">
										<div class="col-md-5">
											<label for="zip" class="control-label">ZIP-code<i class="fa fa-asterisk"></i></label><br>
											@if ($errors->has('zip'))
												<span class="help-block">
												<strong>{{ $errors->first('zip') }}</strong>
											</span>
											@endif
											<input id="zip" type="text" class="form-control" name="zip" value="{{ old('zip') }}" required autofocus>
										</div>
										<div class="col-md-7">
											<label for="city" class="control-label">City<i class="fa fa-asterisk"></i></label><br>
											@if ($errors->has('city'))
												<span class="help-block">
												<strong>{{ $errors->first('city') }}</strong>
											</span>
											@endif
											<input id="city" type="text" class="form-control" name="city">
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<input id="coords" type="hidden" name="coords">
							<div id="map"></div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group{{ $errors->has('starts_at') ? ' has-error' : '' }}">
								<div class="col-md-12">
									<label for="starts_at" class="control-label">Starts at<i class="fa fa-asterisk"></i></label><br>
									<input id="starts_at" type="datetime-local" class="form-control" name="starts_at" value="{{ old('starts_at') }}" required autofocus>

									@if ($errors->has('starts_at'))
										<span class="help-block">
										<strong>{{ $errors->first('starts_at') }}</strong>
									</span>
									@endif
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group{{ $errors->has('ends_at') ? ' has-error' : '' }}">
								<div class="col-md-12">
									<label for="ends_at" class="control-label">Ends at<i class="fa fa-asterisk"></i></label><br>
									<input id="ends_at" type="datetime-local" class="form-control" name="ends_at" value="{{ old('ends_at') }}" required autofocus>

									@if ($errors->has('ends_at'))
										<span class="help-block">
										<strong>{{ $errors->first('ends_at') }}</strong>
									</span>
									@endif
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<h3>Schedule type</h3>
							<p class="info">This determines whether you can create elimination rounds or a round-robin contest</p>
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<div class="col-md-12">
											<input id="single-elim" type="radio" name="type" value="elimination">
											<label for="single-elim">Single Elimination</label>
										</div>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<div class="col-md-12">
											<input id="round-robin" type="radio" name="type" value="round-robin">
											<label for="round-robin">Round robin</label>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<label for="max-teams" class="control-label">Max. teams</label><br>
							<input id="max-teams" class="form-control" name="max-teams" value="{{ old('max-teams') }}" required autofocus>

							@if ($errors->has('max-teams'))
								<span class="help-block">
								<strong>{{ $errors->first('max-teams') }}</strong>
							</span>
							@endif
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<label for="description" class="control-label">Description</label><br>
							<textarea id="description" class="form-control" name="description" required autofocus>{{ old('description') }}</textarea>

							@if ($errors->has('description'))
								<span class="help-block">
								<strong>{{ $errors->first('description') }}</strong>
							</span>
							@endif
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
	<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDjI_a7-CJA5anDE0q3NSBHoccjlL31Dmk"></script>
	<script src="js/forms.js"></script>
	<script src="js/ckeditor/ckeditor.js"></script>
	<script>
		CKEDITOR.replace('description');
	</script>
@stop