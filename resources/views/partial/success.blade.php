@if(Session::has('success'))
	<div class="row success">
		<div class="col-md-6 col-md-offset-3">
			<div class="alert alert-success">
				<p>{{ Session::get('success') }}</p>
			</div>
		</div>
	</div>
@endif