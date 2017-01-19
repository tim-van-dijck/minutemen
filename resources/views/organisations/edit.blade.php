@extends('layouts.app')

@section('title', 'Edit '.$organisation->name)
@section('content')
	<h1>Edit {{ $organisation->name }}</h1>
	<form id="organisation-form" class="form-horizontal image-form" role="form" method="POST" action="{{ route('organisations.update', ['id' => $organisation->id]) }}" enctype="multipart/form-data">
		{{ csrf_field() }}
		<input type="hidden" name="_method" value="PATCH">

		<div class="row">
			<div class="col-md-12">
				<div class="col-md-12">
					<div class="row">
						<div class="col-md-12">
							<div class="row">
								<div class="col-md-12">
									<div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
										<div class="col-md-12">
											<label for="name" class="control-label">Organisation name</label><br>
											<input id="name" type="text" class="form-control" name="name" value="{{ $organisation->name }}" placeholder="League of Awesome" required autofocus>

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
									<div class="form-group{{ $errors->has('img') ? ' has-error' : '' }}">
										<div class="col-md-12">
											<label class="control-label">Change image</label><br>
											<input id="img" type="hidden" name="img" value="{{ old('img') }}">
											<div class="text-center">
												<img id="edit-img" src="{{ $organisation->thumb or 'img/organisation.png' }}" alt="{{ $organisation->name }}">
											</div>
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
									<textarea id="description" class="form-control" name="description" required autofocus>{{ $organisation->description }}</textarea>

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
				</div>
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