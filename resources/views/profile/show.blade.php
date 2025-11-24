<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $user->user_userName }} - Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="{{ asset('assets/css/navbar.css') }}" rel="stylesheet">
    <style>
        :root {
            --primary-bg: #8c76ec;
            --secondary-bg: #a6e7ff;
            --light-purple: #c3b4fc;
            --text-dark: #333;
        }
        body {
            background-color: #f0f2f5;
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
        }

        /* Banner Section */
        .profile-banner-container {
            height: 320px;
            width: 100%;
            overflow: hidden;
            position: relative;
            background-color: #ddd;
        }
        .profile-banner-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* Main Content Overlap */
        .main-profile-container {
            margin-top: -100px;
            position: relative;
            z-index: 10;
            padding-bottom: 4rem;
        }

        /* Left Column: Profile Card */
        .profile-card {
            background: #fff;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            border: none;
            overflow: visible;
            height: 100%;
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
            box-shadow: 0 2px 10px rgba(0,0,0,0.15);
            background-color: #fff;
        }

        /* Right Column: Content Card */
        .content-card {
            background: #fff;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            border: none;
            min-height: 400px;
        }

        /* Custom Tabs */
        .nav-pills-custom .nav-link {
            color: #6c757d;
            font-weight: 600;
            border-radius: 30px;
            padding: 0.5rem 1.5rem;
            margin-right: 0.5rem;
            transition: all 0.2s;
        }
        .nav-pills-custom .nav-link.active {
            background-color: var(--primary-bg);
            color: #fff;
            box-shadow: 0 4px 6px rgba(140, 118, 236, 0.3);
        }
        .nav-pills-custom .nav-link:hover:not(.active) {
            background-color: #f8f9fa;
            color: var(--primary-bg);
        }

        /* Stats */
        .stat-box {
            text-align: center;
            padding: 1rem;
            border-right: 1px solid #eee;
        }
        .stat-box:last-child { border-right: none; }
        .stat-value { font-size: 1.5rem; font-weight: 800; color: var(--text-dark); display: block; }
        .stat-label { font-size: 0.85rem; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px; }

        /* Badges */
        .badge-custom { font-size: 0.75rem; padding: 0.35em 0.65em; border-radius: 10px; }

        /* Cards inside tabs */
        .art-card { border-radius: 12px; border: none; transition: 0.2s; box-shadow: 0 2px 8px rgba(0,0,0,0.05); }
        .art-card:hover { transform: translateY(-3px); box-shadow: 0 5px 15px rgba(0,0,0,0.1); }
        .art-card img { height: 180px; object-fit: cover; border-top-left-radius: 12px; border-top-right-radius: 12px; }

        /* Ghost State Styling */
        .ghost-state {
            text-align: center;
            padding: 4rem 1rem;
            color: #adb5bd;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100%;
        }
        .ghost-icon {
            font-size: 4rem;
            margin-bottom: 1rem;
            opacity: 0.5;
            animation: float 3s ease-in-out infinite;
            color: var(--light-purple);
        }
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
            100% { transform: translateY(0px); }
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
        <div class="row g-4">

            <div class="col-lg-4">
                <div class="card profile-card p-4">
                    <div class="profile-avatar-wrapper">
                        <img src="{{ $user->user_profile_pic ? asset('assets/uploads/' . $user->user_profile_pic) : asset('assets/images/blank-profile-picture.png') }}"
                             class="profile-avatar"
                             alt="{{ $user->user_userName }}"
                             onerror="this.onerror=null; this.src='{{ asset('assets/images/blank-profile-picture.png') }}';">
                    </div>

                    <div class="text-center mb-4">
                        <h3 class="fw-bold mb-1">
                            {{ $user->user_userName }}
                            @if($user->user_type === 'admin')
                                <span class="badge bg-primary badge-custom align-middle">Admin</span>
                            @elseif($user->user_type === 'artist')
                                <span class="badge bg-info badge-custom align-middle">Artist</span>
                            @else
                                <span class="badge bg-secondary badge-custom align-middle">User</span>
                            @endif
                        </h3>

                        <p class="text-muted mb-2">{{ $user->user_email }}</p>

                        @if($user->account_status === 'banned')
                            <span class="badge bg-danger">Banned</span>
                        @elseif($user->account_status === 'archived')
                            <span class="badge bg-secondary">Archived</span>
                        @endif

                        @if($isOwnProfile)
                            <div class="mt-3">
                                <a href="{{ route('edit-profile.edit') }}" class="btn btn-outline-primary rounded-pill px-4 fw-bold w-100">Edit Profile</a>
                            </div>
                        @endif
                    </div>

                    <hr>

                    <div class="mb-4">
                        <h6 class="fw-bold text-uppercase text-muted small mb-2">About</h6>
                        <p class="text-muted" style="line-height: 1.6;">
                            {!! nl2br(e($user->user_bio ?? 'No bio yet.')) !!}
                        </p>
                    </div>

                    <div class="text-center bg-light p-3 rounded-3">
                        <div class="text-warning fs-4 mb-1">
                            @for($i = 1; $i <= 5; $i++)
                                @if($avg_rating >= $i) <i class="fas fa-star"></i>
                                @elseif($avg_rating > ($i - 1)) <i class="fas fa-star-half-alt"></i>
                                @else <i class="far fa-star"></i>
                                @endif
                            @endfor
                        </div>
                        <small class="text-muted">{{ $avg_rating }} rating ({{ $rating_count }} reviews)</small>
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="card mb-4 border-0 shadow-sm">
                    <div class="card-body p-0">
                        <div class="row g-0">
                            <div class="col-4 stat-box">
                                <span class="stat-value">{{ $challenges_count }}</span>
                                <span class="stat-label">Challenges</span>
                            </div>
                            <div class="col-4 stat-box">
                                <span class="stat-value">{{ $interpretations_count }}</span>
                                <span class="stat-label">Interpretations</span>
                            </div>
                            <div class="col-4 stat-box">
                                <span class="stat-value">{{ $total_art }}</span>
                                <span class="stat-label">Total Art</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card content-card p-4">
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
                            <h5 class="fw-bold mb-3">Original Challenges</h5>
                            <div class="row g-3">
                                @forelse($user_challenges as $art)
                                    <div class="col-md-6">
                                        <div class="card art-card h-100">
                                            <img src="{{ asset('assets/uploads/'.$art->original_art_filename) }}" class="card-img-top" onerror="this.src='{{ asset('assets/images/placeholder.png') }}'">
                                            <div class="card-body">
                                                <h6 class="card-title fw-bold text-truncate">{{ $art->challenge_name }}</h6>
                                                <a href="{{ route('challenges.show', $art->challenge_id) }}" class="btn btn-sm btn-primary rounded-pill mt-2 px-3">View</a>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="col-12 text-center py-5 text-muted">
                                        <i class="fas fa-palette fa-3x mb-3 text-light"></i>
                                        <p>No artworks posted yet.</p>
                                    </div>
                                @endforelse
                            </div>
                        @else
                            <div class="ghost-state">
                                <i class="fas fa-ghost ghost-icon"></i>
                                <h4 class="fw-bold">Nothing to see here!</h4>
                                <p>This user has hidden their artworks.</p>
                            </div>
                        @endif
                    </div>

                    <div id="history-content" style="display: none;">
                        @if($user->show_history)
                            <h5 class="fw-bold mb-3">Recent Activity</h5>
                            <ul class="list-group list-group-flush">
                                @forelse($user_history as $event)
                                    <li class="list-group-item px-0 py-3">
                                        <div class="d-flex">
                                            <div class="me-3">
                                                @if($event->event_type === 'created_challenge') <i class="fas fa-plus-circle text-primary fa-lg"></i>
                                                @elseif($event->event_type === 'posted_comment') <i class="fas fa-comment text-info fa-lg"></i>
                                                @elseif($event->event_type === 'created_interpretation') <i class="fas fa-paint-brush text-success fa-lg"></i>
                                                @elseif(str_contains($event->event_type, 'liked')) <i class="fas fa-heart text-danger fa-lg"></i>
                                                @endif
                                            </div>
                                            <div>
                                                <p class="mb-1">
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
                                    <p class="text-muted text-center py-4">No recent activity.</p>
                                @endforelse
                            </ul>
                        @else
                            <div class="ghost-state">
                                <i class="fas fa-ghost ghost-icon"></i>
                                <h4 class="fw-bold">Nothing to see here!</h4>
                                <p>This user has hidden their history.</p>
                            </div>
                        @endif
                    </div>

                    <div id="comments-content" style="display: none;">
                        @if($user->show_comments)
                            <h5 class="fw-bold mb-3">Recent Comments on Art</h5>
                            <div class="list-group list-group-flush">
                                @forelse($comments_on_art as $comment)
                                    <div class="list-group-item px-0 py-3 d-flex">
                                        <img src="{{ $comment->user_profile_pic ? asset('assets/uploads/'.$comment->user_profile_pic) : asset('assets/images/blank-profile-picture.png') }}"
                                             class="rounded-circle me-3 border" width="45" height="45" style="object-fit: cover;"
                                             onerror="this.src='{{ asset('assets/images/blank-profile-picture.png') }}'">
                                        <div>
                                            <p class="mb-1"><strong>{{ $comment->user_userName }}</strong> on <strong>{{ $comment->challenge_name }}</strong></p>
                                            <p class="text-muted mb-1 fst-italic">"{{ Str::limit($comment->comment_text, 100) }}"</p>
                                            <small class="text-muted">{{ \Carbon\Carbon::parse($comment->created_at)->diffForHumans() }}</small>
                                        </div>
                                    </div>
                                @empty
                                    <p class="text-muted text-center py-4">No comments yet.</p>
                                @endforelse
                            </div>
                        @else
                            <div class="ghost-state">
                                <i class="fas fa-ghost ghost-icon"></i>
                                <h4 class="fw-bold">Nothing to see here!</h4>
                                <p>This user has hidden their comments.</p>
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
