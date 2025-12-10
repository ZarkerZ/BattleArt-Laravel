<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>BattleArt Challenges</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <link href="{{ asset('assets/css/navbar.css') }}" rel="stylesheet">

    <style>
        :root {
            /* --- VARIABLES --- */
            --primary-color: #5a4bda;       /* Deep Royal Purple */
            --primary-hover: #4335b8;
            --secondary-color: #f8f9fa;     /* Clean Light Gray Background */
            --text-dark: #1e293b;           /* Slate Dark */
            --text-muted: #64748b;          /* Slate Gray */
            --card-radius: 16px;

            /* Required for navbar.css variable consistency */
            --primary-bg: #5a4bda;
        }

        body {
            background-color: var(--secondary-color);
            font-family: 'Inter', sans-serif;
            color: var(--text-dark);
            padding-top: 80px;
            min-height: 100vh;
        }

        /* --- FORCE NAVBAR PURPLE (MATCHING HOME.BLADE) --- */
        .custom-navbar {
            background-color: var(--primary-color) !important;
            backdrop-filter: none;
            -webkit-backdrop-filter: none;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        /* --- PAGE HEADER --- */
        .page-header {
            margin-bottom: 3rem;
            border-bottom: 1px solid rgba(0,0,0,0.05);
            padding-bottom: 1rem;
        }

        .page-title {
            color: var(--text-dark);
            font-weight: 800;
            letter-spacing: -0.5px;
        }

        /* --- BUTTONS --- */
        .btn-create {
            background-color: var(--primary-color);
            color: white;
            padding: 0.7rem 2rem;
            border-radius: 50px;
            font-weight: 600;
            border: none;
            box-shadow: 0 4px 15px rgba(90, 75, 218, 0.3);
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .btn-create:hover {
            background-color: var(--primary-hover);
            transform: translateY(-2px);
            color: white;
        }

        /* --- CATEGORY CARDS --- */
        .category-card {
            background: #fff;
            border: 1px solid rgba(0,0,0,0.05);
            border-radius: 12px;
            padding: 1.2rem;
            text-align: center;
            transition: all 0.2s ease;
            text-decoration: none !important;
            color: var(--text-muted);
            display: block;
            height: 100%;
        }

        .category-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.08);
            color: var(--primary-color);
        }

        .category-card.active {
            background-color: var(--primary-color);
            color: white;
            box-shadow: 0 5px 15px rgba(90, 75, 218, 0.3);
            border-color: var(--primary-color);
        }

        .category-card.active i {
            color: white !important;
        }

        /* --- CHALLENGE CARDS --- */
        .card-custom {
            background: #fff;
            border-radius: var(--card-radius);
            padding: 1rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            height: 100%;
            border: 1px solid rgba(0,0,0,0.05);
            text-decoration: none;
            display: flex;
            flex-direction: column;
        }

        .card-custom:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
        }

        .card-img-wrapper {
            height: 200px;
            border-radius: 12px;
            overflow: hidden;
            margin-bottom: 1rem;
        }

        .card-img-top {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .card-custom:hover .card-img-top {
            transform: scale(1.05);
        }

        .card-title-text {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 0.25rem;
        }

        .card-meta {
            color: var(--text-muted);
            font-size: 0.9rem;
            margin-bottom: 1rem;
        }

        /* --- BADGES --- */
        .badge-banned {
            background-color: #ef4444;
            color: white;
            font-size: 0.65em;
            padding: 0.25em 0.6em;
            border-radius: 4px;
            vertical-align: middle;
            margin-left: 5px;
            text-transform: uppercase;
            font-weight: 700;
        }

        .stat-badge {
            background-color: #f1f5f9;
            color: var(--text-muted);
            padding: 0.4rem 0.8rem;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 500;
        }
    </style>
</head>
<body>
    @include('partials.navbar')

    <div class="container my-5">
        <header class="d-flex justify-content-between align-items-center page-header">
            <div>
                <h1 class="page-title">Creative Challenges</h1>
                <p class="text-muted mb-0">Join a challenge or start your own.</p>
            </div>
            <a href="{{ route('challenges.create') }}" class="btn btn-create">
                <i class="fas fa-plus me-2"></i> Create Challenge
            </a>
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
            <div class="row row-cols-2 row-cols-md-3 row-cols-lg-6 g-3">
                @foreach($cats as $cat)
                    <div class="col">
                        <a href="{{ route('challenges.index', ['category' => $category == $cat['val'] ? null : $cat['val']]) }}"
                           class="category-card {{ $category == $cat['val'] ? 'active' : '' }}">
                            <i class="fas {{ $cat['icon'] }} fs-3 mb-2" style="{{ $category == $cat['val'] ? '' : 'color: var(--primary-color);' }}"></i>
                            <div class="fw-semibold small">{{ $cat['name'] }}</div>
                        </a>
                    </div>
                @endforeach
            </div>
        </section>

        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4">
            @forelse($challenges as $challenge)
                <div class="col">
                    <a href="{{ route('challenges.show', $challenge->challenge_id) }}" class="card-custom">
                        <div class="card-img-wrapper">
                            <img src="{{ asset('assets/uploads/' . $challenge->original_art_filename) }}" class="card-img-top" alt="Challenge Art">
                        </div>

                        <div class="d-flex flex-column h-100">
                            <h5 class="card-title-text">{{ Str::limit($challenge->challenge_name, 25) }}</h5>

                            <div class="card-meta">
                                <span>by {{ $challenge->user->user_userName }}</span>
                                @if($challenge->user->account_status === 'banned')
                                    <span class="badge badge-banned">Banned</span>
                                @endif
                            </div>

                            <div class="mt-auto pt-2">
                                <span class="stat-badge">
                                    <i class="fas fa-paint-brush me-1 text-primary"></i>
                                    {{ $challenge->interpretation_count }} Reinterpretations
                                </span>
                            </div>
                        </div>
                    </a>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-light text-center shadow-sm border-0 py-5" style="border-radius: 12px;">
                        <i class="fas fa-folder-open fa-2x text-muted mb-3"></i>
                        <h5 class="text-muted">No challenges found.</h5>
                    </div>
                </div>
            @endforelse
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
