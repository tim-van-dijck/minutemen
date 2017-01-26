@extends('layouts.app')

@section('title', 'Edit '.$event->title)
@section('content')
    <h1>Edit {{ $event->title }}</h1>
    <form id="edit-event" class="form-horizontal" role="form" method="POST" action="{{ route('events.update', ['id' => $event->id]) }}" enctype="multipart/form-data">
        {{ csrf_field() }}
        <input type="hidden" name="_method" value="PATCH">

        <div class="row">
            <div class="col-md-12">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group banner-upload{{ $errors->has('img') ? ' has-error' : '' }}">
                                <div class="col-md-12">
                                    <label class="control-label">Banner</label><br>
                                    <div class="banner">
                                        <img id="preview-banner" src="{{ $event->banner or 'img/event.png' }}">
                                    </div>
                                    <label for="banner" class="form-control img-label">
                                        <span>Browse</span><input id="banner" type="file" class="hidden" name="banner" accept="image/*">
                                    </label>

                                    @if ($errors->has('banner'))
                                        <span class="help-block">
										<strong>{{ $errors->first('banner') }}</strong>
									</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
                                <div class="col-md-12">
                                    <label for="title" class="control-label">Event title<i class="fa fa-asterisk"></i></label><br>
                                    <input id="title" type="text" class="form-control" name="title" value="{{ $event->title }}" placeholder="Awesome Cup" required autofocus>

                                    @if ($errors->has('title'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('title') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-12">
                                    <h3>Address</h3>
                                    <div class="row">
                                        <div class="col-md-8">
                                            <label for="street" class="control-label">Street<i class="fa fa-asterisk"></i></label><br>
                                            @if ($errors->has('street'))
                                                <span class="help-block">
												<strong>{{ $errors->first('street') }}</strong>
											</span>
                                            @endif
                                            <input id="street" type="text" class="form-control" name="street" value="{{ $event->street }}" required autofocus>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="number" class="control-label">Number<i class="fa fa-asterisk"></i></label><br>
                                            @if ($errors->has('number'))
                                                <span class="help-block">
												<strong>{{ $errors->first('number') }}</strong>
											</span>
                                            @endif
                                            <input id="number" type="text" class="form-control" name="number" value="{{ $event->number }}" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-5">
                                            <label for="zip" class="control-label">ZIP-code<i class="fa fa-asterisk"></i></label><br>
                                            @if ($errors->has('zip'))
                                                <span class="help-block">
												<strong>{{ $errors->first('zip') }}</strong>
											</span>
                                            @endif
                                            <input id="zip" type="text" class="form-control" name="zip" value="{{ $event->zip }}" required autofocus>
                                        </div>
                                        <div class="col-md-7">
                                            <label for="city" class="control-label">City<i class="fa fa-asterisk"></i></label><br>
                                            @if ($errors->has('city'))
                                                <span class="help-block">
												<strong>{{ $errors->first('city') }}</strong>
											</span>
                                            @endif
                                            <input id="city" type="text" class="form-control" name="city" value="{{ $event->city }}" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <input id="coords" type="hidden" name="coords">
                            <div id="map"></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group{{ $errors->has('starts_at') ? ' has-error' : '' }}">
                                <div class="col-md-12">
                                    <label for="starts_at" class="control-label">Starts <ati class="fa fa-asterisk"></ati></label><br>
                                    <input id="starts_at" type="datetime-local" class="form-control" name="starts_at" value="{{ date('Y-m-d\TH:i:s', strtotime($event->starts_at)) }}" required autofocus>

                                    @if ($errors->has('starts_at'))
                                        <span class="help-block">
										<strong>{{ $errors->first('starts_at') }}</strong>
									</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group{{ $errors->has('ends_at') ? ' has-error' : '' }}">
                                <div class="col-md-12">
                                    <label for="ends_at" class="control-label">Ends <ati class="fa fa-asterisk"></ati></label><br>
                                    <input id="ends_at" type="datetime-local" class="form-control" name="ends_at" value="{{ date('Y-m-d\TH:i:s', strtotime($event->ends_at)) }}" required autofocus>

                                    @if ($errors->has('ends_at'))
                                        <span class="help-block">
										<strong>{{ $errors->first('ends_at') }}</strong>
									</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <label for="description" class="control-label">Description</label><br>
                            <textarea id="description" class="form-control" name="description" required autofocus>{{ $event->description }}</textarea>

                            @if ($errors->has('description'))
                                <span class="help-block">
								<strong>{{ $errors->first('description') }}</strong>
							</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="col-md-12">
                    <div class="form-group">
                        <div class="col-md-4 col-md-offset-4">
                            <button type="submit" class="btn btn-primary pull-right full-width" form="edit-event">
                                Save
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <div class="row">
        <div class="col-md-12">
            <div class="col-md-12">
                <form class="delete" data-confirm="delete {{ $event->title}}" action="{{ route('events.destroy', ['id' => $event->id]) }}" method="POST">
                    {{ csrf_field() }}
                    <input type="hidden" name="_method" value="DELETE">
                    <button type="submit" class="btn btn-danger pull-left"><i class="fa fa-trash"></i> Delete</button>
                </form>
            </div>
        </div>
    </div>
@stop

@section('js')
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDjI_a7-CJA5anDE0q3NSBHoccjlL31Dmk"></script>
    <script src="js/forms.js"></script>
    <script src="js/delete-confirm.js"></script>
    <script src="js/ckeditor/ckeditor.js"></script>
    <script>
        CKEDITOR.replace('description');
    </script>
@stop