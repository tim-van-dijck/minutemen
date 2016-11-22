<div class="row login {{ ($errors->has('username') || $errors->has('password')) ? '' : 'hidden' }}">
	<div class="col-md-4 col-md-offset-4">
		<div class="panel panel-default">
			<div class="panel-heading">
				<div class="close"><a href=""><i class="fa fa-remove"></i></a></div>
				Login
			</div>
			<div class="panel-body">
				<div class="row">
					<div class="col-md-7">
						<form class="form-horizontal" role="form" method="POST" action="{{ url('/login') }}">
							{{ csrf_field() }}

							<div class="form-group{{ $errors->has('username') ? ' has-error' : '' }}">
								<label for="username" class="col-md-2 control-label"><i class="fa fa-user"></i></label>
								<div class="col-md-9">
									<input id="username" type="text" class="form-control" name="username" value="{{ old('username') }}" placeholder="Username or e-mail address" required autofocus>

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
										<label>
											<input type="checkbox" name="remember"> Remember Me
										</label>
									</div>
								</div>
							</div>

							<div class="form-group">
								<div class="col-md-9 col-md-offset-2">
									<button type="submit" class="btn btn-primary">
										Login
									</button>

									<a class="btn btn-link" href="{{ url('/password/reset') }}">
										Forgot Your Password?
									</a>
								</div>
							</div>
						</form>
					</div>
					<div class="col-md-5">
						<p>Or sign up <a href="{{ url('/register') }}">here</a></p>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="shadow"></div>
</div>
