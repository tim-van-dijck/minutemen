@extends('layouts.app')

@section('title', 'Edit '.$team->name)
@section('content')
	<form action="teams/{{ $team->id }}" method="POST">
		{{ csrf_field() }}
		<input type="hidden" name="_method" value="PATCH">
		<h2><input type="text" value="{{ $team->name }}"> - [<input id="tag" type="text" value="{{ $team->tag }}">]</h2>
		<div class="row">
			<div class="col-md-5">
				<div class="profile-img">
					<img src="{{ $team->emblem or 'img/emblem.png' }}" alt="{{ $team->name }}">
				</div>
			</div>
			<div class="col-md-7">
				<div class="row">
					<div class="col-md-12">
						<textarea name="description" id="description">{{ $team->description }}</textarea>
					</div>
				</div>
				<div class="stats row">
					<div class="col-md-4">
						<h4>Wins</h4>
						<p>{{ $team->wins or 0 }}</p>
					</div>
					<div class="col-md-4">
						<h4>Losses</h4>
						<p>{{ $team->losses or 0 }}</p>
					</div>
					<div class="col-md-4">
						<h4>Average Wins</h4>
						<p>{{ ($team->losses > 0) ? $team->wins/$team->losses : '0' }}</p>
					</div>
				</div>
			</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-4 col-md-offset-4">
				<button type="submit">Save</button>
			</div>
		</div>
		<div class="row">
			<div class="col-md-8 col-md-offset-2">
				<div class="row">
					<div class="col-md-12">
						<h3>Participations</h3>
						@forelse($team->participations as $participation)
							<div class="row">
								<div class="col-md-12">
									<span class="title">{{ $participation->title }}</span>
									<span class="rank">{{ $participation->rank }}</span>
								</div>
							</div>
						@empty
							<div class="row">
								<div class="col-md-8 col-md-offset-2">
									<p class="text-center">This team hasn't participated in any events yet.</p>
								</div>
							</div>
						@endforelse
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