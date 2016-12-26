@extends('layouts.app')

@section('title', 'Organisations')
@section('content')
    <h1>Untrusted organisations</h1>
    <form action="{{ route('organisations.trust.batch') }}" method="POST">
        {{ csrf_field() }}
        <div class="row blocklink-wrapper">
            <div class="col-md-12">
                @forelse($organisations as $i => $organisation)
                    <div class="col-md-2 blocklink">
                        <div class="check">
                            <input id="{{ $organisation->id }}" type="checkbox" name="trusted[]" value="{{ $organisation->id }}">
                            <label for="{{ $organisation->id }}"></label>
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
                <button type="submit" class="btn btn-primary">Trust selected</button>
            </div>
        </div>
    </form>
@stop
