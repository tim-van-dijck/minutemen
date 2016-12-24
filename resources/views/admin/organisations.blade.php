@extends('layouts.app')

@section('title', 'Organisations')
@section('content')
    <h1>Untrusted organisations</h1>
    <div class="row blocklink-wrapper">
        <div class="col-md-12">
            @forelse($organisations as $i => $organisation)
                <div class="col-md-2 blocklink">
                    <div class="check">
                        <input type="checkbox"><label></label>
                    </div>
                    <a href="{{ route('organisations.show', ['id' => $organisation->id]) }}">
                        <div class="profile-img"><img src="{{ $organisation->thumb or 'img/organisation.png' }}" alt="{{ $organisation->name }}"></div>
                        <p>{{$organisation->name}}</p>
                    </a>
                </div>
                @if ($i != 0 && $i % 6 == 0)
                    </div><div class="row">
                @endif
            @empty
                <p>There are no organisations yet.</p>
            @endforelse
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <a href="/" class="btn btn-primary">Trust selected</a>
        </div>
    </div>
@stop
