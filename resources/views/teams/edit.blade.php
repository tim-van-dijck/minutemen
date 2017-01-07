@extends('layouts.app')

@section('title', 'Edit '.$team->name)
@section('content')
	<h1>Edit {{  $team->name }}</h1>
	<form id="edit-form" class="form-horizontal image-form" action="teams/{{ $team->id }}" method="POST">
		{{ csrf_field() }}
		<input type="hidden" name="_method" value="PATCH">
		<div class="row">
			<div class="col-md-10 col-md-offset-1">
				<div class="row">
					<div class="col-md-6">
						<div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
							<div class="col-md-12">
								<label for="name" class="control-label">Team name</label><br>
								<input id="name" type="text" class="form-control" name="name" value="{{ $team->name }}" placeholder="Team Awesome" required autofocus>

								@if ($errors->has('name'))
									<span class="help-block">
								<strong>{{ $errors->first('name') }}</strong>
							</span>
								@endif
							</div>
						</div>
						<div class="form-group{{ $errors->has('tag') ? ' has-error' : '' }}">
							<div class="col-md-6">
								<label for="tag" class="control-label">Team Tag</label><br>
								<input id="tag" type="text" class="form-control" name="tag" value="{{ $team->tag }}" placeholder="TAWE" required>

								@if ($errors->has('tag'))
									<span class="help-block">
								<strong>{{ $errors->first('tag') }}</strong>
							</span>
								@endif
							</div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group{{ $errors->has('emblem') ? ' has-error' : '' }}">
							<div class="col-md-8 col-md-offset-2">
								<label class="control-label">Team emblem</label><br>
								<input id="emblem" type="hidden" name="emblem">
								<img id="edit-img" src="{{ $team->emblem or 'img/emblem.png' }}" alt="{{ $team->name }}">
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
						<textarea id="description" class="form-control" name="description" required autofocus>
							{{ $team->description }}
						</textarea>

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
			<div class="col-md-2 col-md-offset-5">
				<button class="btn btn-primary" type="submit">Save</button>
			</div>
		</div>
	</form>
@stop

@section('js')
	<script src="js/ckeditor/ckeditor.js"></script>
	<script src="js/libs/croppie.min.js"></script>
	<script src="js/forms.js"></script>
	<script src="js/edit-form-img.js"></script>
	<script>
		CKEDITOR.replace('description');
	</script>
@stop