@extends('layouts.app')

@section('title', 'Editar película')

@section('content')

    <h1 class="mb-4">Editar: {{ $movie->title }}</h1>

    <form action="/movies/{{ $movie->id }}" method="POST" class="card card-body">
        @csrf
        @method('PUT')

        {{-- Título --}}
        <div class="mb-3">
            <label for="title" class="form-label">Título</label>
            <input
                type="text"
                name="title"
                id="title"
                value="{{ old('title', $movie->title) }}"
                class="form-control @error('title') is-invalid @enderror">
            @error('title')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Director --}}
        <div class="mb-3">
            <label for="director" class="form-label">Director</label>
            <input
                type="text"
                name="director"
                id="director"
                value="{{ old('director', $movie->director) }}"
                class="form-control @error('director') is-invalid @enderror">
            @error('director')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Año --}}
        <div class="mb-3">
            <label for="year" class="form-label">Año</label>
            <input
                type="number"
                name="year"
                id="year"
                value="{{ old('year', $movie->year) }}"
                class="form-control @error('year') is-invalid @enderror">
            @error('year')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Puntuación --}}
        <div class="mb-3">
            <label for="score" class="form-label">Puntuación (0-10)</label>
            <input
                type="number"
                step="0.1"
                name="score"
                id="score"
                value="{{ old('score', $movie->score) }}"
                class="form-control @error('score') is-invalid @enderror">
            @error('score')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Sinopsis --}}
        <div class="mb-3">
            <label for="synopsis" class="form-label">Sinopsis</label>
            <textarea
                name="synopsis"
                id="synopsis"
                rows="4"
                class="form-control @error('synopsis') is-invalid @enderror">{{ old('synopsis', $movie->synopsis) }}</textarea>
            @error('synopsis')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary">
                Actualizar
            </button>

            <a href="/movies/{{ $movie->id }}" class="btn btn-secondary">
                Cancelar
            </a>
        </div>

    </form>

@endsection