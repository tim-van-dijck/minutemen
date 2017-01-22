@extends('layouts.app')

@section('title', 'Settings')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="col-md-12">
                <i class="fa fa-2x fa-cogs menu-icons"></i>
                <h1>Settings</h1>
            </div>
        </div>
    </div>
    <form id="edit-form" class="form-horizontal image-form settings" role="form" method="POST" action="{{ route('settings.update') }}" enctype="multipart/form-data">
        {{ csrf_field() }}
        <input type="hidden" name="_method" value="PATCH">
        <div class="row">
            <div class="col-md-12">
                <div class="col-md-12">
                    <h3>Address</h3>
                    <p class="info">This is solely used to filter lobbies by distance to your location.</p>
                </div>
                <div class="col-md-6">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <div class="col-md-9">
                                    <label for="street" class="control-label">Street</label><br>
                                    @if ($errors->has('street'))
                                        <span class="help-block">
											<strong>{{ $errors->first('street') }}</strong>
										</span>
                                    @endif
                                    <input id="street" type="text" class="form-control" name="street" value="{{ $user->street }}" autofocus>
                                </div>
                                <div class="col-md-3">
                                    <label for="number" class="control-label">Number</label><br>
                                    @if ($errors->has('number'))
                                        <span class="help-block">
											<strong>{{ $errors->first('number') }}</strong>
										</span>
                                    @endif
                                    <input id="number" type="text" class="form-control" name="number" value="{{ $user->number }}">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <div class="col-md-5">
                                    <label for="zip" class="control-label">ZIP-code</label><br>
                                    @if ($errors->has('zip'))
                                        <span class="help-block">
											<strong>{{ $errors->first('zip') }}</strong>
										</span>
                                    @endif
                                    <input id="zip" type="text" class="form-control" name="zip" value="{{ $user->zip }}" placeholder="1207" autofocus>
                                </div>
                                <div class="col-md-7">
                                    <label for="city" class="control-label">City</label><br>
                                    @if ($errors->has('city'))
                                        <span class="help-block">
											<strong>{{ $errors->first('city') }}</strong>
										</span>
                                    @endif
                                    <input id="city" type="text" class="form-control" name="city" value="{{ $user->city }}" placeholder="Fakopolis">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <input id="coords" type="hidden" name="coords">
                    <input id="country" type="hidden" name="country">
                    <div id="map"></div>
                </div>

            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="col-md-12">
                    <label for="range" class="control-label">Range</label>
                    <p class="info">The radius in which to look for lobbies with Looking For Group</p>
                    <div class="row">
                        <div class="col-md-8 col-md-offset-2">
                            <div class="form-group text-center">
                                <input id="range" name="range" data-slider-id='range-slider' type="text" data-slider-min="10" data-slider-max="200" data-slider-step="10" data-slider-value="{{ $user->range }}"/>
                                <p class="text-center">
                                    <span class="range">10</span>km
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <div class="col-md-6 col-md-offset-4">
                        <button type="submit" class="btn btn-primary pull-right">
                            Save
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <div class="row">
        <div class="col-md-12">
            <div class="col-md-12">
                <form class="delete" data-confirm="delete your account" action="{{ route('users.destroy') }}" method="POST">
                    {{ csrf_field() }}
                    <input type="hidden" name="_method" value="DELETE">
                    <button type="submit" class="btn btn-danger"><i class="fa fa-trash"></i> Delete</button>
                </form>
            </div>
        </div>
    </div>
@stop

@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-slider/9.7.0/bootstrap-slider.min.js"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDjI_a7-CJA5anDE0q3NSBHoccjlL31Dmk"></script>
    <script src="js/forms.js"></script>
    <script src="js/delete-confirm.js"></script>
    <script>
        $(function() {
            $('#range').slider({
                formatter: function(value) {
                    $('span.range').text(value);
                }
            });
        })
    </script>
@stop