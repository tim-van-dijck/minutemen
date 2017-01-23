@extends('layouts.app')

@section('title', 'Home')
@section('content')
	<div id="about" class="row">
		<div class="col-md-12">
			<div class="col-md-12 about">
				<h1>About Minutemen</h1>
				<div class="row persist-cols">
					<div class="col-md-4">
						<div class="image-wrapper">
							<img src="img/laser-gun.png" alt="Nerf Phoenix LTX Tagger" class="">
						</div>
					</div>
					<div class="col-md-7 col-md-offset-1">
						<p>Minutemen is an online platform for laser tag enthusiasts whereon they can connect and compete with one another.</p>
						<p>Our goal is to make sure you can always find someone to play with and always be ready for battle.</p>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<h2>Why "Minutemen"?</h2>
					</div>
				</div>
				<div class="row persist-cols">
					<div class="col-md-4 col-md-offset-1 pull-right">
						<div class="image-wrapper"><img src="img/emblem.png" alt="Emblem"></div>
					</div>
					<div class="col-md-7">
						<p>
							Historically, minutemen were a kind of militia that would be ready to defend their homes "at a minute's notice".
						<blockquote cite="https://en.wikipedia.org/wiki/Minutemen">
							<i>They were civilian colonists who independently organized to form well-prepared militia companies self-trained
								in weaponry, tactics, and military strategies from the American colonial partisan militia during
								the American Revolutionary War.</i>
							<footer><cite title="Source Title">Wikipedia</cite></footer>
						</blockquote>
						</p>
						<p>We found this to be a fitting comparison with our own eagerness to do battle whenever, with whomever dare challenge us.</p>
					</div>
				</div>
			</div>
		</div>
	</div>
@stop