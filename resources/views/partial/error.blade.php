@if(isset($errors) && $errors->any() && Auth::check())
	<div class="row errors">
		<div class="col-md-6 col-md-offset-3">
			<div class="alert alert-danger">
				@foreach($errors->all() as $error)
					<p>{{ $error }}</p>
				@endforeach
			</div>
		</div>
	</div>
@endif