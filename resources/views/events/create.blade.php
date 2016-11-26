@extends('layouts.app')

@section('content')
	<h1>Create an event</h1>
	<form class="form-horizontal" role="form" method="POST" action="{{ route('teams.store') }}" enctype="multipart/form-data">
		{{ csrf_field() }}

		<div class="row">
			<div class="col-md-10 col-md-offset-1">
				<div class="row">
					<div class="col-md-12">
						<div class="form-group banner-upload{{ $errors->has('img') ? ' has-error' : '' }}">
							<div class="col-md-12">
								<label class="control-label">Banner</label><br>
								<label for="banner" class="btn img-label">
									<span>Click to upload an image</span><input id="banner" type="file" class="hidden" name="banner" value="{{ old('banner') }}">
								</label>
								<div class="banner">
									<img id="preview-banner" src="">
								</div>
								<input type="hidden" name="cropx" id="cropx" value="0" />
								<input type="hidden" name="cropy" id="cropy" value="0" />
								<input type="hidden" name="cropw" id="cropw" value="0" />
								<input type="hidden" name="croph" id="croph" value="0" />

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
					<div class="col-md-6">
						<div class="row">
							<div class="col-md-12">
								<div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
									<div class="col-md-12">
										<label for="title" class="control-label">Event title</label><br>
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
							<div class="col-md-12">
								<label for="address" class="control-label">Address</label><br>
								<input id="address" type="text" class="form-control" name="address" value="{{ old('address') }}" required autofocus>

								@if ($errors->has('address'))
									<span class="help-block">
										<strong>{{ $errors->first('address') }}</strong>
									</span>
								@endif
							</div>
						</div>
					</div>
					<div class="col-md-6">
						<input type="hidden" name="coords">
						<div id="map"></div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group{{ $errors->has('starts_at') ? ' has-error' : '' }}">
							<div class="col-md-12">
								<label for="starts_at" class="control-label">Starts at</label><br>
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
								<label for="ends_at" class="control-label">Ends at</label><br>
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
						<label for="description" class="control-label">Description</label><br>
						<textarea id="description" class="form-control" name="description" value="{{ old('description') }}" required autofocus></textarea>

						@if ($errors->has('description'))
							<span class="help-block">
								<strong>{{ $errors->first('description') }}</strong>
							</span>
						@endif
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
	<script src="http://jcrop-cdn.tapmodo.com/v0.9.12/js/jquery.Jcrop.min.js"></script>
	<script src="js/ckeditor/ckeditor.js"></script>
	<script src="js/forms.js"></script>
	<script>
		CKEDITOR.replace('description');
	</script>
@stop