<div class="col-md-6 col-md-offset-3">
	@if(Session::has('flash_message'))
		<div class="alert alert-success">
			<p>{{ Session::get('flash_message') }}</p>
		</div>
	@endif
</div>