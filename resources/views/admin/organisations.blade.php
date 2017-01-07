@extends('layouts.app')

@section('title', 'Organisations')
@section('content')
    <h1>Untrusted organisations</h1>
    @if(!$organisations->isEmpty())
        <input id="q" type="search" name="q" autocomplete="off">
    @endif
    <form action="{{ route('organisations.trust.batch') }}" method="POST">
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
                            <p>{{$organisation->name}}</p>
                        </a>
                    </div>
                    @if ($i != 0 && ($i+1) % 6 == 0)
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
@section('js')
    <script type="text/javascript">
        $(function() {
            $('#q').change(function() {
                if ($(this).val().length) { getOrganisations($(this).val()); }
            });
        });

        function getOrganisations(query) {
            $.getJSON('admin/ajax/organisations/find?q='+query, function(data) {
                $('.blocklink-wrapper').empty();
                $.each(data, function (i,v) {
                    if (v.thumb == null) { v.thumb = 'img/organisation.png'; }
                    $('main h1').text('Organisations');
                    $('.blocklink-wrapper').append('<div class="col-md-2 blocklink"><div class="check">'+
                        '<input id="org-'+v.id+'" type="checkbox" name="trusted[]" value="'+v.id+'">'+
                        '<label for="org-'+v.id+'"></label></div><a href="organisations/'+v.id+'">'+
                        '<div class="profile-img"><img src="'+v.thumb+'" alt="'+v.name+'"></div>'+
                        '<p>'+v.name+'</p></a></div>');
                });
            });
        }
    </script>
@stop