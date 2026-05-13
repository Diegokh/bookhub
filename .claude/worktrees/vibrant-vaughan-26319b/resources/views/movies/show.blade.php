@extends('layouts.app')

@section('title', $movie->title)

@section('content')

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <h1>{{ $movie->title }}</h1>

    <div class="card mt-4">
        <div class="card-body">

            <p>
                <strong>ID:</strong>
                {{ $movie->id }}
            </p>

            <p>
                <strong>Año:</strong>
                {{ $movie->year }}
            </p>

            <p>
                <strong>Director:</strong>
                {{ $movie->director }}
            </p>

            <p>
                <strong>Puntuación:</strong>
                ⭐ {{ $movie->score }}
            </p>

            <p>
                <strong>Tier:</strong>
                {{ $movie->tier }}
            </p>

            @if ($movie->synopsis)
                <p>
                    <strong>Sinopsis:</strong><br>
                    {{ $movie->synopsis }}
                </p>
            @endif

            @auth
                @if (auth()->user()->isAdmin())
                    <a href="/movies/{{ $movie->id }}/edit" class="btn btn-warning">
                        Editar
                    </a>

                    <form action="/movies/{{ $movie->id }}" method="POST"
                        onsubmit="return confirm('¿Seguro que quieres borrar esta película?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Borrar</button>
                    </form>
                @endif
            @endauth

        </div>
    </div>

@endsection
