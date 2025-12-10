<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $user->user_userName }} - Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <link href="{{ asset('assets/css/navbar.css') }}" rel="stylesheet">
    <style>
        :root {
            --primary-color: #5a4bda;
            --primary-hover: #4335b8;
            --secondary-color: #f8f9fa;
            --text-dark: #1e293b;
            --text-muted: #64748b;
            --card-radius: 16px;
            --primary-bg: #5a4bda;
        }

        body {
            background-color: var(--secondary-color);
            font-family: 'Inter', sans-serif;
            color: var(--text-dark);
            min-height: 100vh;
        }

        .custom-navbar {
            background-color: var(--primary-color) !important;
            backdrop-filter: none;
            -webkit-backdrop-filter: none;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .profile-banner-container {
            height: 320px;
            width: 100%;
            overflow: hidden;
            position: relative;
            background-color: #e2e8f0;
        }

        .profile-banner-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .main-profile-container {
            margin-top: -100px;
            position: relative;
            z-index: 10;
            padding-bottom: 4rem;
        }

        /* --- CARDS --- */
        /* Fixed: Removed height: 100% to prevent ugly stretching */
        .profile-card, .content-card {
            background: #fff;
            border-radius: var(--card-radius);
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            border: 1px solid rgba(0,0,0,0.05);
        }

        /* --- LEFT SIDEBAR STICKY --- */
        /* Keeps profile info visible while scrolling history */
        .profile-sidebar-wrapper {
            position: sticky;
            top: 100px; /* Adjust based on navbar height */
            z-index: 1;
        }

        .profile-avatar-wrapper {
            position: relative;
            margin-top: -75px;
            text-align: center;
            margin-bottom: 1rem;
        }

        .profile-avatar {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            border: 5px solid #fff;
            object-fit: cover;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            background-color: #fff;
        }

        .nav-pills-custom .nav-link {
            color: var(--text-muted);
            font-weight: 600;
            border-radius: 50px;
            padding: 0.6rem 1.5rem;
            margin-right: 0.5rem;
            transition: all 0.2s ease;
        }

        .nav-pills-custom .nav-link.active {
            background-color: var(--primary-color);
            color: #fff;
            box-shadow: 0 4px 10px rgba(90, 75, 218, 0.3);
        }

        .stat-box {
            text-align: center;
            padding: 1.5rem 1rem;
            border-right: 1px solid #f1f5f9;
        }

        .stat-box:last-child { border-right: none; }

        .stat-value {
            font-size: 1.5rem;
            font-weight: 800;
            color: var(--text-dark);
            display: block;
            line-height: 1.2;
        }

        .stat-label {
            font-size: 0.8rem;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 600;
        }

        .badge-custom { font-size: 0.75rem; padding: 0.4em 0.8em; border-radius: 6px; }

        .art-card {
            border-radius: 12px;
            border: 1px solid rgba(0,0,0,0.05);
            transition: 0.3s ease;
            overflow: hidden;
        }

        .art-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.08);
        }

        .art-card img {
            height: 200px;
            object-fit: cover;
            width: 100%;
        }

        .ghost-state {
            text-align: center;
            padding: 4rem 1rem;
            color: #cbd5e1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .ghost-icon {
            font-size: 4rem;
            margin-bottom: 1rem;
            opacity: 0.8;
            color: #e2e8f0;
            animation: float 3s ease-in-out infinite;
        }

        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
            100% { transform: translateY(0px); }
        }

        .btn-outline-primary-custom {
            border: 2px solid var(--primary-color);
            color: var(--primary-color);
            font-weight: 600;
            border-radius: 50px;
            transition: all 0.2s;
        }

        .btn-outline-primary-custom:hover {
            background-color: var(--primary-color);
            color: white;
            box-shadow: 0 4px 15px rgba(90, 75, 218, 0.3);
        }
    </style>
</head>
<body>
    @include('partials.navbar')

    <div class="profile-banner-container">
        <img src="{{ $user->user_banner_pic ? asset('assets/uploads/' . $user->user_banner_pic) : asset('assets/images/night-road.png') }}"
             class="profile-banner-img"
             onerror="this.onerror=null; this.src='{{ asset('assets/images/night-road.png') }}';">
    </div>

    <div class="container main-profile-container">
        <div class="row g-4 align-items-start"> <div class="col-lg-4">
                <div class="profile-sidebar-wrapper">
                    <div class="profile-card p-4">
                        <div class="profile-avatar-wrapper">
                            <img src="{{ $user->user_profile_pic ? asset('assets/uploads/' . $user->user_profile_pic) : asset('assets/images/blank-profile-picture.png') }}"
                                 class="profile-avatar"
                                 alt="{{ $user->user_userName }}"
                                 onerror="this.onerror=null; this.src='{{ asset('assets/images/blank-profile-picture.png') }}';">
                        </div>

                        <div class="text-center mb-4">
                            <h3 class="fw-bold mb-1 text-break" style="color: var(--text-dark);">
                                {{ $user->user_userName }}
                                @if($user->user_type === 'admin')
                                    <span class="badge bg-primary badge-custom align-middle ms-1">Admin</span>
                                @elseif($user->user_type === 'artist')
                                    <span class="badge badge-custom align-middle ms-1" style="background-color: var(--primary-color);">Artist</span>
                                @else
                                    <span class="badge bg-secondary badge-custom align-middle ms-1">User</span>
                                @endif
                            </h3>

                            <p class="text-muted mb-2 text-break">{{ $user->user_email }}</p>

                            @if($user->account_status === 'banned')
                                <span class="badge bg-danger">Banned</span>
                            @elseif($user->account_status === 'archived')
                                <span class="badge bg-secondary">Archived</span>
                            @endif

                            @if($isOwnProfile)
                                <div class="mt-4">
                                    <a href="{{ route('edit-profile.edit') }}" class="btn btn-outline-primary-custom px-4 w-100">Edit Profile</a>
                                </div>
                            @endif
                        </div>

                        <hr style="border-color: #f1f5f9;">

                        <div class="mb-4">
                            <h6 class="fw-bold text-uppercase text-muted small mb-3">About</h6>
                            <p class="text-muted text-break" style="line-height: 1.6; font-size: 0.95rem;">
                                {!! nl2br(e($user->user_bio ?? 'No bio yet.')) !!}
                            </p>
                        </div>

                        <div class="text-center p-3 rounded-3" style="background-color: #f8f9fa;">
                            <div class="text-warning fs-4 mb-1">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($avg_rating >= $i) <i class="fas fa-star"></i>
                                    @elseif($avg_rating > ($i - 1)) <i class="fas fa-star-half-alt"></i>
                                    @else <i class="far fa-star text-muted" style="opacity: 0.3;"></i>
                                    @endif
                                @endfor
                            </div>
                            <small class="text-muted fw-semibold">{{ $avg_rating }} rating ({{ $rating_count }} reviews)</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="card mb-4 border-0 shadow-sm overflow-hidden" style="border-radius: var(--card-radius);">
                    <div class="card-body p-0">
                        <div class="row g-0">
                            <div class="col-4 stat-box">
                                <span class="stat-value">{{ $challenges_count }}</span>
                                <span class="stat-label">Challenges</span>
                            </div>
                            <div class="col-4 stat-box">
                                <span class="stat-value">{{ $interpretations_count }}</span>
                                <span class="stat-label">Entries</span>
                            </div>
                            <div class="col-4 stat-box">
                                <span class="stat-value">{{ $total_art }}</span>
                                <span class="stat-label">Total Art</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="content-card p-4">
                    <ul class="nav nav-pills nav-pills-custom mb-4" id="profile-tabs">
                        <li class="nav-item">
                            <a class="nav-link active" id="your-art-tab" href="#" onclick="switchTab('your-art-content', this)">Artworks</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="history-tab" href="#" onclick="switchTab('history-content', this)">History</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="comments-tab" href="#" onclick="switchTab('comments-content', this)">Comments</a>
                        </li>
                    </ul>
                    <div id="your-art-content">
                        @if($user->show_art)
                            <h5 class="fw-bold mb-4" style="color: var(--text-dark);">Original Challenges</h5>
                            <div class="row g-4">
                                @forelse($user_challenges as $art)
                                    <div class="col-md-6">
                                        <div class="card art-card h-100">
                                            <img src="{{ asset('assets/uploads/'.$art->original_art_filename) }}" class="card-img-top" onerror="this.src='{{ asset('assets/images/placeholder.png') }}'">
                                            <div class="card-body p-3">
                                                <h6 class="card-title fw-bold text-dark text-truncate mb-2">{{ $art->challenge_name }}</h6>
                                                <a href="{{ route('challenges.show', $art->challenge_id) }}" class="btn btn-sm btn-outline-primary-custom px-3">View Challenge</a>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="col-12 text-center py-5">
                                        <div class="text-muted p-4 rounded-3" style="background-color: #f8f9fa;">
                                            <i class="fas fa-palette fa-2x mb-3 text-muted" style="opacity: 0.5;"></i>
                                            <p class="mb-0">No artworks posted yet.</p>
                                        </div>
                                    </div>
                                @endforelse
                            </div>
                        @else
                            <div class="row">
                                <div class="col-12 text-center py-5">
                                    <div class="text-muted p-4 rounded-3" style="background-color: #f8f9fa;">
                                        <i class="fas fa-eye-slash fa-2x mb-3 text-muted" style="opacity: 0.5;"></i>
                                        <h5 class="fw-bold text-dark mb-1">Content Hidden</h5>
                                        <p class="mb-0">This user has hidden their artworks.</p>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>

                    <div id="history-content" style="display: none;">
                        @if($user->show_history)
                            <h5 class="fw-bold mb-3" style="color: var(--text-dark);">Recent Activity</h5>
                            <ul class="list-group list-group-flush">
                                @forelse($user_history as $event)
                                    <li class="list-group-item px-0 py-3 border-bottom" style="border-color: #f1f5f9;">
                                        <div class="d-flex">
                                            <div class="me-3 mt-1">
                                                @if($event->event_type === 'created_challenge') <i class="fas fa-plus-circle text-primary fa-lg"></i>
                                                @elseif($event->event_type === 'posted_comment') <i class="fas fa-comment text-info fa-lg"></i>
                                                @elseif($event->event_type === 'created_interpretation') <i class="fas fa-paint-brush text-success fa-lg"></i>
                                                @elseif(str_contains($event->event_type, 'liked')) <i class="fas fa-heart text-danger fa-lg"></i>
                                                @endif
                                            </div>
                                            <div>
                                                <p class="mb-1 text-dark">
                                                    @if($event->event_type === 'created_challenge') Created challenge <strong>{{ $event->event_title }}</strong>
                                                    @elseif($event->event_type === 'posted_comment') Commented on <strong>{{ $event->event_title }}</strong>
                                                    @elseif($event->event_type === 'created_interpretation') Submitted interpretation for <strong>{{ $event->event_title }}</strong>
                                                    @elseif($event->event_type === 'liked_challenge') Liked challenge <strong>{{ $event->event_title }}</strong>
                                                    @elseif($event->event_type === 'liked_interpretation') Liked <strong>{{ $event->event_title }}'s</strong> interpretation
                                                    @endif
                                                </p>
                                                <small class="text-muted">{{ \Carbon\Carbon::parse($event->event_date)->diffForHumans() }}</small>
                                            </div>
                                        </div>
                                    </li>
                                @empty
                                    <div class="col-12 text-center py-5">
                                        <div class="text-muted p-4 rounded-3" style="background-color: #f8f9fa;">
                                            <i class="fas fa-history fa-2x mb-3 text-muted" style="opacity: 0.5;"></i>
                                            <p class="mb-0">No activity yet.</p>
                                        </div>
                                    </div>
                                @endforelse
                            </ul>
                        @else
                            <div class="col-12 text-center py-5">
                                <div class="text-muted p-4 rounded-3" style="background-color: #f8f9fa;">
                                    <i class="fas fa-history fa-2x mb-3 text-muted" style="opacity: 0.5;"></i>
                                    <h5 class="fw-bold text-dark mb-1">History Hidden</h5>
                                    <p class="mb-0">This user has hidden their activity history.</p>
                                </div>
                            </div>
                        @endif
                    </div>

                    <div id="comments-content" style="display: none;">
                        @if($user->show_comments)
                            <h5 class="fw-bold mb-3" style="color: var(--text-dark);">Recent Comments</h5>
                            <div class="list-group list-group-flush">
                                @forelse($comments_on_art as $comment)
                                    <div class="list-group-item px-0 py-3 d-flex border-bottom" style="border-color: #f1f5f9;">
                                        <img src="{{ $comment->user_profile_pic ? asset('assets/uploads/'.$comment->user_profile_pic) : asset('assets/images/blank-profile-picture.png') }}"
                                            class="rounded-circle me-3 border" width="45" height="45" style="object-fit: cover;"
                                            onerror="this.src='{{ asset('assets/images/blank-profile-picture.png') }}'">
                                        <div>
                                            <p class="mb-1 text-dark"><strong>{{ $comment->user_userName }}</strong> on <strong>{{ $comment->challenge_name }}</strong></p>
                                            <div class="p-3 rounded-3 mb-2" style="background-color: #f8f9fa;">
                                                <p class="text-muted mb-0 fst-italic">"{{ Str::limit($comment->comment_text, 100) }}"</p>
                                            </div>
                                            <small class="text-muted">{{ \Carbon\Carbon::parse($comment->created_at)->diffForHumans() }}</small>
                                        </div>
                                    </div>
                                @empty
                                    <div class="col-12 text-center py-5">
                                        <div class="text-muted p-4 rounded-3" style="background-color: #f8f9fa;">
                                            <i class="fas fa-comment-slash fa-2x mb-3 text-muted" style="opacity: 0.5;"></i>
                                            <p class="mb-0">No comments yet.</p>
                                        </div>
                                    </div>
                                @endforelse
                            </div>
                        @else
                            <div class="col-12 text-center py-5">
                                <div class="text-muted p-4 rounded-3" style="background-color: #f8f9fa;">
                                    <i class="fas fa-comment-slash fa-2x mb-3 text-muted" style="opacity: 0.5;"></i>
                                    <h5 class="fw-bold text-dark mb-1">Comments Hidden</h5>
                                    <p class="mb-0">This user has hidden their comments.</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function switchTab(targetId, linkElement) {
            event.preventDefault();
            document.querySelectorAll('.nav-pills-custom .nav-link').forEach(el => el.classList.remove('active'));
            linkElement.classList.add('active');

            const sections = ['your-art-content', 'history-content', 'comments-content'];
            sections.forEach(id => {
                const el = document.getElementById(id);
                if(el) el.style.display = 'none';
            });

            const target = document.getElementById(targetId);
            if(target) target.style.display = 'block';
        }
    </script>
</body>
</html>
