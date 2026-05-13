@extends('layouts.app')

@section('title', 'Películas (SPA)')

@section('content')

    <h1 class="mb-4">Películas (cargadas vía API)</h1>

    {{-- Mensaje mientras carga --}}
    <div id="loading" class="alert alert-info">
        Cargando películas...
    </div>

    {{-- Mensaje de error --}}
    <div id="error" class="alert alert-danger d-none"></div>

    {{-- Contenedor donde JS pintará las tarjetas --}}
    <div id="movies-container" class="row g-3"></div>

    {{-- Paginación simple --}}
    <div class="mt-4 d-flex justify-content-between align-items-center">
        <button id="prev-btn" class="btn btn-secondary" disabled>← Anterior</button>
        <span id="page-info" class="text-muted"></span>
        <button id="next-btn" class="btn btn-secondary" disabled>Siguiente →</button>
    </div>

    <script>
        const container  = document.getElementById('movies-container');
        const loading    = document.getElementById('loading');
        const errorBox   = document.getElementById('error');
        const prevBtn    = document.getElementById('prev-btn');
        const nextBtn    = document.getElementById('next-btn');
        const pageInfo   = document.getElementById('page-info');

        let currentPage = 1;

        async function loadMovies(page = 1) {
            loading.classList.remove('d-none');
            errorBox.classList.add('d-none');
            container.innerHTML = '';

            try {
                const res = await fetch(`/api/movies?page=${page}`, {
                    headers: { 'Accept': 'application/json' }
                });

                if (!res.ok) {
                    throw new Error(`Error HTTP ${res.status}`);
                }

                const json = await res.json();
                renderMovies(json.data);
                updatePagination(json);
            } catch (err) {
                errorBox.textContent = 'Error al cargar películas: ' + err.message;
                errorBox.classList.remove('d-none');
            } finally {
                loading.classList.add('d-none');
            }
        }

        function renderMovies(movies) {
            if (!movies || movies.length === 0) {
                container.innerHTML = '<p class="text-muted">No hay películas.</p>';
                return;
            }

            container.innerHTML = movies.map(movie => `
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title">${escapeHtml(movie.title)}</h5>
                            <h6 class="card-subtitle mb-2 text-muted">
                                ${escapeHtml(movie.director ?? 'Director desconocido')}
                                ${movie.year ? `· ${movie.year}` : ''}
                            </h6>
                            <p class="card-text">
                                <span class="badge bg-warning text-dark">
                                    ⭐ ${movie.score}
                                </span>
                            </p>
                            ${movie.synopsis
                                ? `<p class="card-text small">${escapeHtml(movie.synopsis)}</p>`
                                : ''}
                            <a href="/movies/${movie.id}" class="btn btn-primary btn-sm">
                                Ver detalles
                            </a>
                        </div>
                    </div>
                </div>
            `).join('');
        }

        function updatePagination(json) {
            currentPage = json.current_page;
            pageInfo.textContent = `Página ${json.current_page} de ${json.last_page} · ${json.total} películas`;

            prevBtn.disabled = json.current_page <= 1;
            nextBtn.disabled = json.current_page >= json.last_page;
        }

        function escapeHtml(str) {
            if (str === null || str === undefined) return '';
            return String(str)
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;');
        }

        prevBtn.addEventListener('click', () => loadMovies(currentPage - 1));
        nextBtn.addEventListener('click', () => loadMovies(currentPage + 1));

        // Cargar al abrir la página
        loadMovies();
    </script>

@endsection