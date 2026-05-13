@extends('layouts.app')

@section('title', 'Películas')

@section('content')

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

<h1 class="mb-4">Películas</h1>

@auth
    @if(auth()->user()->isAdmin())
        <a href="/movies/create" class="btn btn-success mb-3">
            + Añadir película
        </a>
    @endif
@endauth

<div class="list-group">

    @foreach($movies as $movie)

        <div class="list-group-item d-flex justify-content-between align-items-center">

            <div>
                <h5>{{ $movie->title }}</h5>

                <small>
                    ⭐ {{ $movie->score }}
                    · {{ $movie->director }}
                </small>
            </div>

            <a href="/movies/{{ $movie->id }}"
               class="btn btn-primary btn-sm">
                Ver detalles
            </a>

        </div>

    @endforeach

</div>

<div class="mt-4">
    {{ $movies->links() }}
</div>

@endsection