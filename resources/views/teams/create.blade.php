@extends('layouts.app')

@section('content')
	<h1>Create a team</h1>
	<form id="team-form" class="form-horizontal" role="form" method="POST" action="{{ route('teams.store') }}" enctype="multipart/form-data">
		{{ csrf_field() }}

		<div class="row">
			<div class="col-md-10 col-md-offset-1">
				<div class="row">
					<div class="col-md-9">
						<div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
							<div class="col-md-12">
								<label for="name" class="control-label">Team name</label><br>
								<input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" placeholder="Team Awesome" required autofocus>

								@if ($errors->has('name'))
									<span class="help-block">
										<strong>{{ $errors->first('name') }}</strong>
									</span>
								@endif
							</div>
						</div>
					</div>

					<div class="col-md-3">
						<div class="form-group{{ $errors->has('tag') ? ' has-error' : '' }}">
							<div class="col-md-12">
								<label for="tag" class="control-label">Team Tag</label><br>
								<input id="tag" type="text" class="form-control" name="tag" value="{{ old('tag') }}" placeholder="TAWE" required>

								@if ($errors->has('tag'))
									<span class="help-block">
										<strong>{{ $errors->first('tag') }}</strong>
									</span>
								@endif
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<div class="form-group{{ $errors->has('emblem') ? ' has-error' : '' }}">
							<div class="col-md-6">
								<label class="control-label">Team emblem</label><br>
								<input id="emblem" type="hidden" name="emblem">
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
	<script src="js/ckeditor/ckeditor.js"></script>
	<script src="js/libs/croppie.min.js"></script>
	<script src="js/forms.js"></script>
	<script>
		CKEDITOR.replace('description');
	</script>
@stop
