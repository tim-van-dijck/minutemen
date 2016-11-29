@extends('layouts.app')

@section('content')
	<h1>Create an organisation</h1>
	<form id="organisation-form" class="form-horizontal image-form" role="form" method="POST" action="{{ route('organisations.store') }}" enctype="multipart/form-data">
		{{ csrf_field() }}

		<div class="row">
			<div class="col-md-10 col-md-offset-1">
				<div class="row">
					<div class="col-md-9">
						<div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
							<div class="col-md-12">
								<label for="name" class="control-label">Organisation name</label><br>
								<input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" placeholder="League of Awesome" required autofocus>

								@if ($errors->has('name'))
									<span class="help-block">
										<strong>{{ $errors->first('name') }}</strong>
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
								<label class="control-label">Organisation thumbnail</label><br>
								<input id="img" type="hidden" name="thumb">
								<div id="preview-img">
								</div>
								<label for="full-img" class="form-control img-label">
									<span>Browse</span> <input id="full-img" type="file" class="hidden" name="full-img" value="{{ old('full-img') }}" accept="image/*">
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
							{{ old('description') }}
						</textarea>

						@if ($errors->has('description'))
							<span class="help-block"><strong>{{ $errors->first('description') }}</strong></span>
						@endif
					</div>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-md-12">
				<div class="form-group">
					<div class="col-md-6 col-md-offset-4">
						<button type="submit" class="btn btn-primary">Save</button>
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