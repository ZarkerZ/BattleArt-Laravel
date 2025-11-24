<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Search Results: {{ $query }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="assets/css/navbar.css">
    <style>
        :root {
            --primary-bg: #8c76ec;
            --secondary-bg: #a6e7ff;
            --light-purple: #c3b4fc;
            --text-dark: #333;
        }
        body {
            background-image: linear-gradient(to bottom, var(--secondary-bg), var(--light-purple));
            font-family: 'Inter', sans-serif;
            padding-top: 50px;
        }
        .card {
            background-color: #fff;
            border-radius: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            color: var(--text-dark);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
        }
    </style>
</head>
<body>
    @include('partials.navbar')

    <div class="container my-5">
        <h2 class="mb-4 text-white fw-bold text-center">Search Results for "{{ $query }}"</h2>

        <h4 class="text-white mb-3 border-bottom pb-2">Challenges</h4>
        @if($challenges->isEmpty())
            <div class="alert alert-light text-center">No challenges found.</div>
        @else
            <div class="row g-4 mb-5">
                @foreach($challenges as $challenge)
                    <div class="col-md-3">
                        <a href="{{ route('challenges.show', $challenge->challenge_id) }}" class="text-decoration-none">
                            <div class="card h-100">
                                <img src="{{ asset('assets/uploads/'.$challenge->original_art_filename) }}" class="card-img-top" style="height:180px; object-fit:cover;">
                                <div class="card-body">
                                    <h6 class="fw-bold text-dark">{{ $challenge->challenge_name }}</h6>
                                    <small class="text-muted">by {{ $challenge->user_userName }}</small>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        @endif

        <h4 class="text-white mb-3 border-bottom pb-2">Users</h4>
        @if($users->isEmpty())
            <div class="alert alert-light text-center">No users found.</div>
        @else
            <div class="row g-4">
                @foreach($users as $user)
                    <div class="col-md-3">
                        <a href="{{ route('profile.show', $user->user_id) }}" class="text-decoration-none">
                            <div class="card h-100 p-3 text-center align-items-center">
                                <img src="{{ $user->user_profile_pic ? asset('assets/uploads/'.$user->user_profile_pic) : asset('assets/images/blank-profile-picture.png') }}"
                                     class="rounded-circle mb-2 border" width="80" height="80" style="object-fit:cover;">
                                <h6 class="fw-bold text-dark m-0">{{ $user->user_userName }}</h6>
                                <span class="btn btn-sm btn-outline-primary mt-2 rounded-pill">View Profile</span>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</body>
</html>
