@extends('layouts.app')

@section('title', 'Create organisation')
@section('content')
	<h1>Create an organisation</h1>
	<form id="organisation-form" class="form-horizontal image-form" role="form" method="POST" action="{{ route('organisations.store') }}" enctype="multipart/form-data">
		{{ csrf_field() }}

		<div class="row">
			<div class="col-md-12">
				<div class="col-md-12">
					<div class="row">
						<div class="col-md-12">
							<div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
								<div class="col-md-12">
									<label for="name" class="control-label">Organisation name<i class="fa fa-asterisk"></i></label><br>
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
						<div class="col-md-6 col-md-offset-3">
							<div class="form-group{{ $errors->has('thumb') ? ' has-error' : '' }}">
								<div class="col-md-12">
									<label class="control-label">Organisation image</label><br>
									<input id="img" type="hidden" name="img">
									<div id="preview-img">
									</div>
									<label for="full-img" class="form-control img-label">
										<span>Browse</span> <input id="full-img" type="file" class="hidden" name="full-img" value="{{ old('full-img') }}" accept="image/*">
									</label>

									@if ($errors->has('thumb'))
										<span class="help-block">
										<strong>{{ $errors->first('thumb') }}</strong>
									</span>
									@endif
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<div class="form-group{{ $errors->has('website') ? ' has-error' : '' }}">
								<div class="col-md-12">
									<label for="website" class="control-label">Organisation website</label><br>
									<input id="website" type="text" class="form-control" name="website" value="{{ old('website') }}" placeholder="http://league-of-awesome.com/" autofocus>

									@if ($errors->has('website'))
										<span class="help-block">
										<strong>{{ $errors->first('website') }}</strong>
									</span>
									@endif
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<label for="description" class="control-label">Description<i class="fa fa-asterisk"></i></label><br>
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