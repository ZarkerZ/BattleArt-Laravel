<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>BattleArt Challenges</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="{{ asset('assets/css/navbar.css') }}" rel="stylesheet">
    <style>
        :root { --primary-bg: #8c76ec; --secondary-bg: #a6e7ff; --light-purple: #c3b4fc; --text-dark: #333; }
        body { background-image: linear-gradient(to bottom, #a6e7ff, #c3b4fc); min-height: 100vh; background-repeat: no-repeat; background-attachment: fixed; font-family: 'Inter', sans-serif; padding-top: 20px; }
        .card { border-radius: 20px; border: none; transition: transform 0.2s ease, box-shadow 0.2s ease; }
        .card:hover { transform: translateY(-5px); box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15) !important; }
        .btn-challenge-create { background-color: var(--primary-bg); color: white; transition: background-color 0.2s ease; }
        .btn-challenge-create:hover { background-color: #7b68ee; color: white; }
        .category-card.active { border: 3px solid var(--primary-bg); }
        /* Banned Badge Style */
        .badge-banned { background-color: #dc3545; color: white; font-size: 0.65em; vertical-align: middle; margin-left: 4px; }
    </style>
</head>
<body>
    @include('partials.navbar')

    <div class="container my-5">
        <header class="d-flex justify-content-between align-items-center mb-5">
            <h1 class="text-white fw-bold">Creative Challenges</h1>
            <a href="{{ route('challenges.create') }}" class="btn btn-primary rounded-pill shadow"><i class="fas fa-plus me-2"></i> Create Challenge</a>
        </header>

        @php
            $cats = [
                ['name' => 'Digital Painting', 'val' => 'digital_painting', 'icon' => 'fa-brush'],
                ['name' => 'Sci-Fi', 'val' => 'sci-fi', 'icon' => 'fa-robot'],
                ['name' => 'Fantasy', 'val' => 'fantasy', 'icon' => 'fa-dragon'],
                ['name' => 'Abstract', 'val' => 'abstract', 'icon' => 'fa-palette'],
                ['name' => 'Portraits', 'val' => 'portraits', 'icon' => 'fa-users'],
                ['name' => 'Landscapes', 'val' => 'landscapes', 'icon' => 'fa-mountain']
            ];
        @endphp

        <section class="mb-5">
            <div class="row row-cols-2 row-cols-md-6 g-3">
                @foreach($cats as $cat)
                    <div class="col">
                        <a href="{{ route('challenges.index', ['category' => $category == $cat['val'] ? null : $cat['val']]) }}"
                           class="card text-center p-3 text-decoration-none shadow-sm {{ $category == $cat['val'] ? 'active' : '' }}">
                            <i class="fas {{ $cat['icon'] }} fs-2 mb-2" style="color: var(--primary-bg);"></i>
                            <p class="fw-semibold mb-0 small">{{ $cat['name'] }}</p>
                        </a>
                    </div>
                @endforeach
            </div>
        </section>

        <div class="row row-cols-1 row-cols-md-4 g-4">
            @forelse($challenges as $challenge)
                <div class="col">
                    <a href="{{ route('challenges.show', $challenge->challenge_id) }}" class="card h-100 text-decoration-none shadow-sm">
                        <img src="{{ asset('assets/uploads/' . $challenge->original_art_filename) }}" class="card-img-top" style="height: 180px; object-fit: cover;">
                        <div class="card-body d-flex flex-column">
                            <h5 class="fw-bold text-dark">{{ $challenge->challenge_name }}</h5>
                            <p class="text-muted small">
                                by {{ $challenge->user->user_userName }}
                                @if($challenge->user->account_status === 'banned')
                                    <span class="badge badge-banned">Banned</span>
                                @endif
                            </p>
                            <div class="mt-auto pt-2 d-flex justify-content-between text-muted small">
                                <span><i class="fas fa-paint-brush"></i> {{ $challenge->interpretation_count }} Reinterpretations</span>
                            </div>
                        </div>
                    </a>
                </div>
            @empty
                <div class="col-12 alert alert-light text-center">No challenges found.</div>
            @endforelse
        </div>
    </div>
</body>
</html>
