@extends('layouts.app')

@section('title', 'Organisations')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="col-md-12">
                <h1>Manage organisations</h1><br>
                <p class="info">Only 6 organisations at any given time.</p>
                @if(count($organisations) > 1)
                    <form action="">
                        <p class="info">Type in the search box to find an organisation</p>
                        <input type="search" name="q" placeholder="Search for an organisation" id="q" class="form-control">
                    </form>
                @endif
                <form action="{{ route('organisations.trust.batch') }}" method="POST" id="admin">
                    {{ csrf_field() }}
                    <div class="col-md-12">
                        <div class="row blocklink-wrapper">
                            @forelse($organisations as $i => $organisation)
                                <div class="col-md-2 blocklink">
                                    <div class="check">
                                        <input id="{{ $organisation->id }}" type="checkbox" name="trusted[]" value="{{ $organisation->id }}">
                                        <label for="{{ $organisation->id }}"></label>
                                    </div>
                                    <a href="{{ route('organisations.show', ['id' => $organisation->id]) }}">
                                        <div class="profile-img"><img src="{{ $organisation->thumb or 'img/organisation.png' }}" alt="{{ $organisation->name }}"></div>
                                        <p class="name">{{$organisation->name}}</p>
                                    </a>
                                </div>
                                @if ($i != 0 && ($i+1) % 6 == 0)
                                    </div><div class="row">
                                @endif
                            @empty
                                <div class="col-md-12">
                                    <p class="text-center">There are no untrusted organisations at this time.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                    @if (!$organisations->isEmpty())
                        <div class="row">
                            <div class="col-md-12">
                                <button id="trust-selected" type="submit" class="btn btn-primary pull-right">Trust selected</button>
                            </div>
                        </div>
                    @endif
                </form>
            </div>
        </div>
    </div>
@stop
@section('js')
    <script src="js/organisations.js" type="text/javascript"></script>
@stop