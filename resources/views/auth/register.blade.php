<div class="modal fade" id="register-modal" tabindex="-1" role="dialog" aria-labelledby="registerLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				<h4 class="modal-title" id="registerLabel">Register</h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-10 col-md-offset-1">
						<form id="register-form" class="form-horizontal image-form" role="form" method="POST" action="{{ url('/register') }}" enctype="multipart/form-data">
							{{ csrf_field() }}

							<div class="row">
								<div class="col-md-8 col-md-offset-2">
									<div class="form-group{{ $errors->has('r_username') ? ' has-error' : '' }}">
										<div class="col-md-12">
											<label for="username" class="control-label">Username<i class="fa fa-asterisk"></i></label><br>
											<input id="username" type="text" class="form-control" name="r_username" value="{{ old('r_username') }}" required autofocus>

											@if ($errors->has('r_username'))
												<span class="help-block">
													<strong>{{ $errors->first('r_username') }}</strong>
												</span>
											@endif
										</div>
									</div>

									<div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
										<div class="col-md-12">
											<label for="email" class="control-label">E-Mail Address<i class="fa fa-asterisk"></i></label><br>
											<i>We won't stalk you, we promise!</i>
											<input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required>

											@if ($errors->has('email'))
												<span class="help-block">
													<strong>{{ $errors->first('email') }}</strong>
												</span>
											@endif
										</div>
									</div>
									<div class="form-group{{ $errors->has('r_password') ? ' has-error' : '' }}">
										<div class="col-md-12">
											<label for="password" class="control-label">Password<i class="fa fa-asterisk"></i></label>
											<input id="password" type="password" class="form-control" name="r_password" required>

											@if ($errors->has('r_password'))
												<span class="help-block">
													<strong>{{ $errors->first('r_password') }}</strong>
												</span>
											@endif
										</div>
									</div>
									<div class="form-group">
										<div class="col-md-12">
											<label for="password-confirm" class="control-label">Confirm Password<i class="fa fa-asterisk"></i></label>
											<input id="password-confirm" type="password" class="form-control" name="r_password_confirmation" required>
										</div>
									</div>
								</div>
							</div>


							<div class="form-group">
								<div class="col-md-2 col-md-offset-5">
									<button type="submit" class="btn btn-primary">Register</button>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
