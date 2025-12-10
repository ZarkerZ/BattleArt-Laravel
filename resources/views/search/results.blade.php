<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results: {{ $query }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/navbar.css') }}">

    <style>
        :root {
            /* --- DESIGN SYSTEM VARIABLES --- */
            --primary-color: #5a4bda;       /* Deep Royal Purple */
            --primary-hover: #4335b8;
            --secondary-color: #f8f9fa;     /* Clean Light Gray Background */
            --text-dark: #1e293b;           /* Slate Dark */
            --text-muted: #64748b;          /* Slate Gray */
            --card-radius: 16px;

            /* Navbar Fix Variables */
            --primary-bg: #5a4bda;
        }

        body {
            background-color: var(--secondary-color);
            font-family: 'Inter', sans-serif;
            color: var(--text-dark);
            padding-top: 80px; /* Space for fixed navbar */
            min-height: 100vh;
        }

        /* --- FORCE NAVBAR PURPLE --- */
        .custom-navbar {
            background-color: var(--primary-color) !important;
            backdrop-filter: none;
            -webkit-backdrop-filter: none;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        /* --- CARDS --- */
        .result-card {
            background-color: #fff;
            border-radius: var(--card-radius);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            border: 1px solid rgba(0,0,0,0.05);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            height: 100%;
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        .result-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
        }

        .card-img-wrapper {
            height: 180px;
            overflow: hidden;
            border-bottom: 1px solid rgba(0,0,0,0.05);
        }

        .card-img-top {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .result-card:hover .card-img-top {
            transform: scale(1.05);
        }

        /* --- SECTIONS --- */
        .section-title {
            color: var(--text-dark);
            font-weight: 700;
            padding-bottom: 0.5rem;
            margin-bottom: 1.5rem;
            border-bottom: 1px solid rgba(0,0,0,0.1);
        }

        /* --- BUTTONS --- */
        .btn-outline-custom {
            border: 2px solid #e2e8f0;
            color: var(--primary-color);
            font-weight: 600;
            transition: all 0.2s ease;
        }

        .btn-outline-custom:hover {
            background-color: var(--primary-color);
            color: white;
            border-color: var(--primary-color);
        }

        /* --- EMPTY STATE --- */
        .empty-state {
            background: #fff;
            border-radius: 12px;
            padding: 2rem;
            text-align: center;
            color: var(--text-muted);
            box-shadow: 0 4px 6px rgba(0,0,0,0.02);
            border: 1px solid rgba(0,0,0,0.05);
        }
    </style>
</head>
<body>
    @include('partials.navbar')

    <div class="container my-5">
        <h2 class="mb-5 fw-bold text-center" style="color: var(--text-dark);">Search Results for "<span style="color: var(--primary-color);">{{ $query }}</span>"</h2>

        <h4 class="section-title">Challenges</h4>
        @if($challenges->isEmpty())
            <div class="empty-state mb-5">
                <i class="fas fa-search mb-2"></i> No challenges found matching your query.
            </div>
        @else
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4 mb-5">
                @foreach($challenges as $challenge)
                    <div class="col">
                        <a href="{{ route('challenges.show', $challenge->challenge_id) }}" class="text-decoration-none">
                            <div class="result-card">
                                <div class="card-img-wrapper">
                                    <img src="{{ asset('assets/uploads/'.$challenge->original_art_filename) }}" class="card-img-top">
                                </div>
                                <div class="card-body p-3 d-flex flex-column h-100">
                                    <h6 class="fw-bold text-dark mb-1">{{ Str::limit($challenge->challenge_name, 25) }}</h6>
                                    <small class="text-muted">by {{ $challenge->user_userName }}</small>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        @endif

        <h4 class="section-title">Artists</h4>
        @if($users->isEmpty())
            <div class="empty-state">
                <i class="fas fa-users-slash mb-2"></i> No artists found matching your query.
            </div>
        @else
            <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-4 g-4">
                @foreach($users as $user)
                    @php
                        // Default Logic: Go to public profile
                        $profileUrl = route('profile.show', $user->user_id);

                        // If clicked user is ME, check MY Auth details for admin/user status
                        // We use Auth::user()->user_type instead of $user->user_type to avoid the error
                        if (Auth::check() && Auth::id() == $user->user_id) {
                            if (Auth::user()->user_type === 'admin') {
                                $profileUrl = route('admin.profile');
                            } else {
                                $profileUrl = route('profile');
                            }
                        }
                    @endphp

                    <div class="col">
                        <a href="{{ $profileUrl }}" class="text-decoration-none">
                            <div class="result-card p-4 text-center align-items-center justify-content-center">
                                <img src="{{ $user->user_profile_pic ? asset('assets/uploads/'.$user->user_profile_pic) : asset('assets/images/blank-profile-picture.png') }}"
                                     class="rounded-circle mb-3 border shadow-sm" width="80" height="80" style="object-fit:cover;"
                                     onerror="this.src='{{ asset('assets/images/blank-profile-picture.png') }}';">

                                <h6 class="fw-bold text-dark mb-3">{{ $user->user_userName }}</h6>

                                @if(Auth::id() == $user->user_id)
                                    <span class="btn btn-sm btn-outline-custom rounded-pill w-100">My Profile</span>
                                @else
                                    <span class="btn btn-sm btn-outline-custom rounded-pill w-100">View Profile</span>
                                @endif
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
