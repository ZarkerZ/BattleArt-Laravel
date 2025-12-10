<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $challenge->challenge_name }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <link href="{{ asset('assets/css/navbar.css') }}" rel="stylesheet">

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

        /* --- FORCE NAVBAR PURPLE (Consistency Fix) --- */
        .custom-navbar {
            background-color: var(--primary-color) !important;
            backdrop-filter: none;
            -webkit-backdrop-filter: none;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        /* --- CARDS & CONTAINERS --- */
        .card-custom {
            background: #fff;
            border-radius: var(--card-radius);
            padding: 2rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            border: 1px solid rgba(0,0,0,0.05);
            margin-bottom: 2rem;
        }

        /* --- TYPOGRAPHY --- */
        h1.display-5 {
            font-weight: 800;
            color: var(--text-dark);
            letter-spacing: -1px;
        }

        /* --- BUTTONS --- */
        .btn-primary-custom {
            background-color: var(--primary-color);
            color: white;
            border: none;
            border-radius: 50px;
            padding: 0.6rem 1.5rem;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(90, 75, 218, 0.3);
        }

        .btn-primary-custom:hover {
            background-color: var(--primary-hover);
            transform: translateY(-2px);
            color: white;
        }

        .btn-outline-custom {
            border: 2px solid #e2e8f0;
            color: var(--text-muted);
            border-radius: 50px;
            font-weight: 600;
            transition: all 0.2s ease;
        }

        .btn-outline-custom:hover {
            border-color: var(--text-dark);
            color: var(--text-dark);
            background: transparent;
        }

        /* --- INTERPRETATION CARDS --- */
        .interpretation-card {
            border-radius: 12px;
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            background: #fff;
            border: 1px solid rgba(0,0,0,0.05);
        }

        .interpretation-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0,0,0,0.1) !important;
        }

        /* --- RATINGS & UTILS --- */
        .star-rating-input { display: flex; flex-direction: row-reverse; justify-content: center; gap: 5px; }
        .star-rating-input input { display: none; }
        .star-rating-input label { font-size: 1.5rem; color: #cbd5e1; cursor: pointer; transition: color 0.2s; padding: 0 0.1rem; }
        .star-rating-input input:checked~label, .star-rating-input label:hover, .star-rating-input label:hover~label { color: #f59e0b; }

        .badge-banned { background-color: #ef4444; color: white; font-size: 0.65em; margin-left: 5px; vertical-align: middle; border-radius: 4px; padding: 0.25em 0.5em; }

        .report-link { color: #cbd5e1; transition: color 0.2s; cursor: pointer; }
        .report-link:hover { color: #ef4444; }

        /* --- COMMENTS --- */
        .comment-bubble {
            background-color: #f8f9fa;
            border-radius: 16px;
            padding: 1rem 1.25rem;
            position: relative;
        }
    </style>
</head>
<body>
    @include('partials.navbar')

    <div class="container my-5">

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4 shadow-sm border-0" role="alert" style="border-radius: 12px; background-color: #d1fae5; color: #065f46;">
                <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card-custom">
            <div class="text-center mb-4">
                <h1 class="display-5 mb-2">{{ $challenge->challenge_name }}</h1>

                <a href="{{ route('profile.show', $challenge->user_id) }}" class="text-decoration-none d-inline-flex align-items-center justify-content-center gap-2 mt-2 px-3 py-1 rounded-pill" style="background-color: #f1f5f9;">
                    <img src="{{ $challenge->user->user_profile_pic ? asset('assets/uploads/' . $challenge->user->user_profile_pic) : asset('assets/images/blank-profile-picture.png') }}"
                         class="rounded-circle"
                         width="30" height="30"
                         style="object-fit: cover;"
                         onerror="this.onerror=null; this.src='{{ asset('assets/images/blank-profile-picture.png') }}';">
                    <span class="fw-semibold text-dark">
                        {{ $challenge->user->user_userName }}
                        @if($challenge->user->account_status === 'banned')
                            <span class="badge badge-banned">Banned</span>
                        @endif
                    </span>
                </a>
            </div>

            @if(Auth::id() && Auth::id() != $challenge->user_id)
                <div class="text-center mb-4">
                    <small class="text-muted text-uppercase fw-bold" style="font-size: 0.75rem; letter-spacing: 1px;">Rate the author</small>
                    <div class="star-rating-input" data-rated-user-id="{{ $challenge->user_id }}">
                        @for ($i = 5; $i >= 1; $i--)
                            <input type="radio" id="star{{ $i }}" name="rating" value="{{ $i }}" {{ $current_user_rating == $i ? 'checked' : '' }}>
                            <label for="star{{ $i }}" title="{{ $i }} stars"><i class="fas fa-star"></i></label>
                        @endfor
                    </div>
                </div>
            @endif

            <div class="text-center mb-4 position-relative">
                <img class="img-fluid rounded shadow" style="max-height: 500px; border-radius: 16px;" src="{{ asset('assets/uploads/' . $challenge->original_art_filename) }}">
            </div>

            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3 pt-3 border-top">
                <div class="d-flex gap-4 align-items-center">
                    <button class="btn btn-link text-decoration-none p-0 fw-semibold" id="likeButton" data-id="{{ $challenge->challenge_id }}" style="color: var(--text-dark);">
                        <i class="fas fa-heart {{ $userHasLiked ? 'text-danger' : 'text-secondary' }} me-1"></i>
                        <span id="likeCount">{{ $like_count }}</span> Likes
                    </button>
                    <span class="text-muted fw-semibold"><i class="fas fa-comment me-1"></i> {{ count($comments) }} Comments</span>
                </div>

                <div class="d-flex gap-2">
                    @auth
                        @if(Auth::id() == $challenge->user_id)
                            <div class="btn-group">
                                <a href="{{ route('challenges.edit', $challenge->challenge_id) }}" class="btn btn-outline-primary rounded-pill px-4">Edit</a>
                                <button type="button" class="btn btn-outline-danger rounded-pill px-4 ms-2" data-bs-toggle="modal" data-bs-target="#deleteChallengeModal">Delete</button>
                            </div>
                        @else
                            <button type="button" class="btn btn-outline-custom px-3"
                                    onclick="openReportModal('challenge', {{ $challenge->challenge_id }}, '{{ addslashes($challenge->challenge_name) }}')">
                                <i class="fas fa-flag"></i> Report
                            </button>
                            <a href="{{ route('interpretations.create', ['challenge_id' => $challenge->challenge_id]) }}" class="btn btn-primary-custom">
                                Challenge this Art
                            </a>
                        @endif
                    @else
                        <button type="button" class="btn btn-primary-custom" onclick="showLoginModal()">Challenge this Art</button>
                    @endauth
                </div>
            </div>

            @if($challenge->challenge_description)
                <div class="mt-4 p-3 rounded" style="background-color: #f8f9fa;">
                    <p class="mb-0 text-muted">{{ $challenge->challenge_description }}</p>
                </div>
            @endif
        </div>

        <div class="card-custom">
            <h3 class="fw-bold mb-4" style="color: var(--text-dark);">Interpretations <span class="text-muted fs-5">({{ $total_interpretations }})</span></h3>
            <div class="row g-4">
                @foreach($interpretations as $interp)
                    <div class="col-md-4">
                        <div class="card h-100 interpretation-card border-0 shadow-sm" data-interpretation-id="{{ $interp->interpretation_id }}">
                            <div class="card-body p-3 position-relative">
                                @auth
                                    @if(Auth::id() != $interp->user_id)
                                        <div class="position-absolute top-0 end-0 m-3 z-1">
                                            <i class="fas fa-flag report-link" title="Report"
                                               onclick="openReportModal('interpretation', {{ $interp->interpretation_id }}, 'Interpretation by {{ $interp->user_userName }}')"></i>
                                        </div>
                                    @endif
                                @endauth

                                <div class="d-flex align-items-center mb-3">
                                    <img src="{{ $interp->user_profile_pic ? asset('assets/uploads/'.$interp->user_profile_pic) : asset('assets/images/blank-profile-picture.png') }}"
                                         class="rounded-circle me-2"
                                         width="28" height="28"
                                         onerror="this.onerror=null; this.src='{{ asset('assets/images/blank-profile-picture.png') }}';">
                                    <small class="fw-bold text-dark">
                                        {{ $interp->user_userName }}
                                        @if($interp->account_status === 'banned') <span class="badge badge-banned">Banned</span> @endif
                                    </small>
                                </div>

                                <a href="#" class="d-block mb-3 overflow-hidden rounded" data-bs-toggle="modal" data-bs-target="#interpretationModal"
                                   data-img-src="{{ asset('assets/uploads/' . $interp->art_filename) }}"
                                   data-artist-name="{{ $interp->user_userName }}"
                                   data-artist-avatar="{{ $interp->user_profile_pic ? asset('assets/uploads/'.$interp->user_profile_pic) : asset('assets/images/blank-profile-picture.png') }}"
                                   data-artist-id="{{ $interp->user_id }}"
                                   data-description="{{ $interp->description }}"
                                   data-interpretation-id="{{ $interp->interpretation_id }}"
                                   data-like-count="{{ $interp->like_count }}"
                                   data-user-has-liked="{{ $interp->user_has_liked }}">
                                    <img src="{{ asset('assets/uploads/' . $interp->art_filename) }}" class="img-fluid rounded" style="width: 100%; height: 200px; object-fit: cover;">
                                </a>

                                @if($interp->description)
                                    <p class="small text-muted fst-italic mb-2 text-truncate">"{{ $interp->description }}"</p>
                                @endif

                                <div class="small fw-semibold text-muted">
                                    <i class="fas fa-heart text-danger me-1"></i> {{ $interp->like_count }}
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            @if($total_interpretations > 6)
                <div class="text-center mt-4">
                    <a href="{{ route('interpretations.index', ['challenge_id' => $challenge->challenge_id]) }}" class="btn btn-outline-custom px-4">View All Interpretations</a>
                </div>
            @endif
        </div>

        <div class="card-custom">
            <h3 class="fw-bold mb-4">Comments</h3>

            @auth
                <form action="{{ route('comments.store') }}" method="POST" class="mb-5">
                    @csrf
                    <input type="hidden" name="challenge_id" value="{{ $challenge->challenge_id }}">
                    <div class="d-flex gap-3">
                        <img src="{{ Auth::user()->profile_pic ? asset('assets/uploads/' . Auth::user()->profile_pic) : asset('assets/images/blank-profile-picture.png') }}"
                             class="rounded-circle d-none d-md-block" width="40" height="40" style="object-fit: cover;"
                             onerror="this.onerror=null; this.src='{{ asset('assets/images/blank-profile-picture.png') }}';">
                        <div class="flex-grow-1">
                            <textarea name="comment_text" class="form-control mb-3" required placeholder="Share your thoughts..." rows="2" style="border-radius: 12px; background-color: #f8f9fa; border: 1px solid #e2e8f0;"></textarea>
                            <button class="btn btn-primary-custom float-end">Post Comment</button>
                        </div>
                    </div>
                </form>
            @else
                <div class="alert alert-light text-center mb-5 border shadow-sm rounded-4 py-4">
                    <p class="mb-3 text-muted">Please login to join the discussion.</p>
                    <a href="{{ route('login') }}" class="btn btn-primary-custom px-4">Login</a>
                </div>
            @endguest

            <div class="comments-list">
                @foreach($comments as $comment)
                    <div class="d-flex mb-4">
                        <div class="flex-shrink-0">
                            <img class="rounded-circle border"
                                 src="{{ $comment->user_profile_pic ? asset('assets/uploads/' . $comment->user_profile_pic) : asset('assets/images/blank-profile-picture.png') }}"
                                 style="width: 40px; height: 40px; object-fit: cover;"
                                 onerror="this.src='{{ asset('assets/images/blank-profile-picture.png') }}';">
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="comment-bubble">
                                <div class="fw-bold d-flex justify-content-between mb-1">
                                    <span class="text-dark">
                                        {{ $comment->user_userName }}
                                        @if($comment->user_type === 'admin') <span class="badge bg-primary" style="font-size: 0.7em;">Admin</span> @endif
                                        @if($comment->account_status === 'banned') <span class="badge badge-banned">Banned</span> @endif
                                    </span>
                                    @auth
                                        @if(Auth::id() != $comment->user_id)
                                            <i class="fas fa-flag report-link small" title="Report"
                                               onclick="openReportModal('comment', {{ $comment->comment_id }}, 'Comment by {{ $comment->user_userName }}')"></i>
                                        @endif
                                    @endauth
                                </div>
                                <p class="mb-0 text-muted">{{ $comment->comment_text }}</p>
                            </div>
                            <small class="text-muted ms-2 mt-1 d-block" style="font-size: 0.8rem;">{{ \Carbon\Carbon::parse($comment->created_at)->diffForHumans() }}</small>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="modal fade" id="loginPromptModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <div class="modal-content rounded-4 shadow-lg text-center p-4 border-0">
                <div class="mb-3"><i class="fas fa-lock fa-3x" style="color: var(--primary-color);"></i></div>
                <h5 class="fw-bold mb-2">Login Required</h5>
                <p class="text-muted small mb-4">You need to be logged in to perform this action.</p>
                <div class="d-grid gap-2">
                    <a href="{{ route('login') }}" class="btn btn-primary-custom">Login</a>
                    <button type="button" class="btn btn-outline-secondary rounded-pill" data-bs-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="reportModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4 border-0 shadow-lg">
                <div class="modal-header text-white" style="background-color: #ef4444;">
                    <h5 class="modal-title"><i class="fas fa-flag me-2"></i>Report Content</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('report.store') }}" method="POST">
                    @csrf
                    <div class="modal-body p-4">
                        <input type="hidden" name="type" id="reportType">
                        <input type="hidden" name="target_id" id="reportTargetId">
                        <p class="text-muted mb-4">Reporting: <strong id="reportTargetName" class="text-dark"></strong></p>
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-uppercase text-muted">Reason</label>
                            <select name="reason" class="form-select rounded-3" required>
                                <option value="">Select a reason...</option>
                                <option value="Spam">Spam</option>
                                <option value="Harassment">Harassment</option>
                                <option value="Inappropriate">Inappropriate</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-uppercase text-muted">Details</label>
                            <textarea name="details" class="form-control rounded-3" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer border-0 pt-0 px-4 pb-4">
                        <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger rounded-pill px-4">Submit Report</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="deleteChallengeModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4 border-0 shadow-lg">
                <div class="modal-body text-center p-5">
                    <i class="fas fa-exclamation-circle text-danger fa-3x mb-3"></i>
                    <h4 class="fw-bold">Delete Challenge?</h4>
                    <p class="text-muted mb-4">This action cannot be undone. Are you sure?</p>
                    <div class="d-flex justify-content-center gap-2">
                        <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                        <form action="{{ route('challenges.destroy', $challenge->challenge_id) }}" method="POST">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-danger rounded-pill px-4">Delete Permanently</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="interpretationModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content rounded-4 border-0">
                <div class="modal-header border-0 pb-0">
                    <div id="modalArtistInfo"></div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center p-4">
                    <img id="modalImage" src="" class="img-fluid rounded shadow-sm mb-3" style="max-height: 70vh;">
                    <p id="modalDescription" class="fst-italic text-muted"></p>
                </div>
                <div class="modal-footer border-0 justify-content-center pt-0 pb-4" id="modalFooterActions"></div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="profanityModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4 border-0 shadow-lg">
                <div class="modal-body p-5 text-center">
                    <i class="fas fa-hand-paper text-warning fa-3x mb-3"></i>
                    <h3 class="fw-bold">Comment Rejected</h3>
                    <p class="text-muted">Please keep the conversation respectful and free of profanity.</p>
                    <button class="btn btn-warning text-white rounded-pill px-4" data-bs-dismiss="modal">Okay</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function showLoginModal() {
            new bootstrap.Modal(document.getElementById('loginPromptModal')).show();
        }

        function openReportModal(type, id, name) {
            document.getElementById('reportType').value = type;
            document.getElementById('reportTargetId').value = id;
            document.getElementById('reportTargetName').textContent = name;
            new bootstrap.Modal(document.getElementById('reportModal')).show();
        }

        document.addEventListener('DOMContentLoaded', () => {
            const isUserLoggedIn = @auth true @else false @endauth;

            @if($errors->has('comment_text'))
                new bootstrap.Modal(document.getElementById('profanityModal')).show();
            @endif

            const likeBtn = document.getElementById('likeButton');
            if(likeBtn) {
                likeBtn.addEventListener('click', function() {
                    if (!isUserLoggedIn) { showLoginModal(); return; }
                    fetch("{{ url('/like/challenge') }}", {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                        body: JSON.stringify({ challenge_id: this.dataset.id })
                    })
                    .then(res => res.json())
                    .then(data => {
                        if(data.success) {
                            document.getElementById('likeCount').innerText = data.likeCount;
                            this.querySelector('i').classList.toggle('text-danger');
                            this.querySelector('i').classList.toggle('text-secondary');
                        }
                    });
                });
            }

            document.querySelectorAll('.star-rating-input input').forEach(input => {
                input.addEventListener('change', function() {
                    if (!isUserLoggedIn) { showLoginModal(); return; }
                    fetch("{{ url('/rate/user') }}", {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                        body: JSON.stringify({ rated_user_id: this.closest('.star-rating-input').dataset.ratedUserId, rating_value: this.value })
                    });
                });
            });

            // --- FIXED: ADDED ONERROR HANDLER TO MODAL JS ---
            const interpretationModal = document.getElementById('interpretationModal');
            if (interpretationModal) {
                interpretationModal.addEventListener('show.bs.modal', function(event) {
                    const button = event.relatedTarget;
                    const imgSrc = button.getAttribute('data-img-src');
                    const artistName = button.getAttribute('data-artist-name');
                    const artistAvatar = button.getAttribute('data-artist-avatar');
                    const artistId = button.getAttribute('data-artist-id');
                    const description = button.getAttribute('data-description');
                    const interpretationId = button.getAttribute('data-interpretation-id');
                    let likeCount = parseInt(button.getAttribute('data-like-count'));
                    let userHasLiked = parseInt(button.getAttribute('data-user-has-liked')) > 0;

                    interpretationModal.querySelector('#modalImage').src = imgSrc;
                    interpretationModal.querySelector('#modalDescription').textContent = description ? `"${description}"` : '';

                    // The fix is here: Added onerror handler to the injected HTML string
                    interpretationModal.querySelector('#modalArtistInfo').innerHTML = `
                        <a href="{{ url('/profile') }}/${artistId}" class="d-flex align-items-center text-decoration-none text-dark">
                            <img src="${artistAvatar}"
                                 class="rounded-circle me-2"
                                 width="32" height="32"
                                 style="object-fit:cover;"
                                 onerror="this.onerror=null; this.src='{{ asset('assets/images/blank-profile-picture.png') }}';">
                            <span class="fw-bold">${artistName}</span>
                        </a>
                    `;

                    const likeBtnClass = userHasLiked ? 'text-danger' : 'text-secondary';
                    interpretationModal.querySelector('#modalFooterActions').innerHTML = `<button id="modalLikeBtn" class="btn btn-outline-danger rounded-pill px-4"><i id="modalLikeIcon" class="fas fa-heart ${likeBtnClass}"></i> <span id="modalLikeCount">${likeCount}</span></button>`;

                    document.getElementById('modalLikeBtn').onclick = function() {
                        if (!isUserLoggedIn) {
                            bootstrap.Modal.getInstance(interpretationModal).hide();
                            showLoginModal();
                            return;
                        }

                        fetch("{{ url('/like/interpretation') }}", {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                            body: JSON.stringify({ interpretation_id: interpretationId })
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.success) {
                                document.getElementById('modalLikeCount').textContent = data.likeCount;
                                const icon = document.getElementById('modalLikeIcon');
                                icon.classList.toggle('text-danger'); icon.classList.toggle('text-secondary');
                                const card = document.querySelector(`.interpretation-card[data-interpretation-id="${interpretationId}"]`);
                                if (card) {
                                    card.querySelector('.fa-heart').nextSibling.textContent = " " + data.likeCount;
                                    const cardIcon = card.querySelector('.fa-heart');
                                    cardIcon.classList.toggle('text-danger');

                                    button.setAttribute('data-like-count', data.likeCount);
                                    button.setAttribute('data-user-has-liked', data.userHasLiked ? '1' : '0');
                                }
                            }
                        });
                    };
                });
            }
        });
    </script>
</body>
</html>
