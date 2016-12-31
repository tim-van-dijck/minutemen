<div class="modal fade {{ ($errors->has('username') || $errors->has('password')) ? 'in' : '' }}" id="login-modal" tabindex="-1" role="dialog" aria-labelledby="loginLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				<h4 class="modal-title" id="loginLabel">Login</h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-7">
						<form id="login-form" class="form-horizontal" role="form" method="POST" action="{{ url('/login') }}">
							{{ csrf_field() }}

							<div class="form-group{{ $errors->has('username') ? ' has-error' : '' }}">
								<label for="username" class="col-md-2 control-label"><i class="fa fa-user"></i></label>
								<div class="col-md-9">
									<input id="username" type="text" class="form-control" name="username" value="{{ old('username') }}" placeholder="Username" required autofocus>

									@if ($errors->has('username'))
										<span class="help-block">
											<strong>{{ $errors->first('username') }}</strong>
										</span>
									@endif
								</div>
							</div>

							<div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
								<label for="password" class="col-md-2 control-label"><i class="fa fa-lock"></i></label>
								<div class="col-md-9">
									<input id="password" type="password" class="form-control" name="password" placeholder="Password" required>

									@if ($errors->has('password'))
										<span class="help-block">
											<strong>{{ $errors->first('password') }}</strong>
										</span>
									@endif
								</div>
							</div>
							<div class="form-group">
								<div class="col-md-9 col-md-offset-2">
									<div class="checkbox">
										<input id="remember" type="checkbox" name="remember">
										<label for="remember"> Remember Me</label>
									</div>
								</div>
							</div>

							<div class="form-group">
								<div class="col-md-9 col-md-offset-2">
									<button type="submit" class="btn btn-primary">
										Login
									</button>

									<a class="btn btn-link accent" href="{{ url('/password/reset') }}">
										Forgot Your Password?
									</a>
								</div>
							</div>
						</form>
					</div>
					<div class="col-md-5">
						<p>Or sign up <a class="accent" href="{{ url('/register') }}">here</a></p>
					</div>
				</div>
			</div>
			</div>
			</div>
		</div>
	</div>
</div>