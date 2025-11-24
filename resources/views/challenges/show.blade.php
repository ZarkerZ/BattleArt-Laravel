<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $challenge->challenge_name }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="{{ asset('assets/css/navbar.css') }}" rel="stylesheet">
    <style>
        :root { --primary-bg: #8c76ec; --secondary-bg: #a6e7ff; --light-purple: #c3b4fc; --text-dark: #333; }
        body { background-image: linear-gradient(to bottom, var(--secondary-bg), var(--light-purple)); min-height: 100vh; padding-top: 20px; font-family: 'Inter', sans-serif; }
        .card { border-radius: 20px; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1); border: none; }
        .btn-primary-custom { background-color: var(--primary-bg); color: white; border: none; transition: background-color 0.2s ease; }
        .btn-primary-custom:hover { background-color: #7b68ee; color: white; }
        .interpretation-card, .comment-card { background-color: #f8f9fa; }
        .star-rating-input { display: flex; flex-direction: row-reverse; justify-content: center; gap: 5px; }
        .star-rating-input input { display: none; }
        .star-rating-input label { font-size: 1.5rem; color: #ddd; cursor: pointer; transition: color 0.2s; padding: 0 0.1rem; }
        .star-rating-input input:checked~label, .star-rating-input label:hover, .star-rating-input label:hover~label { color: #ffda6a; }
        .badge-banned { background-color: #dc3545; color: white; font-size: 0.7em; margin-left: 5px; vertical-align: middle; }
        /* Hover effect for report icon */
        .report-link { color: #ccc; transition: color 0.2s; cursor: pointer; }
        .report-link:hover { color: #dc3545; }
    </style>
</head>
<body>
    @include('partials.navbar')

    <div class="container my-5">

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card p-4 p-md-5 mb-4">

            <div class="text-center mb-4">
                <h1 class="display-5 fw-bold text-dark">{{ $challenge->challenge_name }}</h1>
                <a href="{{ route('profile.show', $challenge->user_id) }}" class="text-decoration-none text-muted d-flex align-items-center justify-content-center gap-2 mt-2">
                    <img src="{{ $challenge->user->user_profile_pic ? asset('assets/uploads/' . $challenge->user->user_profile_pic) : asset('assets/images/blank-profile-picture.png') }}"
                         class="rounded-circle border"
                         width="40" height="40"
                         style="object-fit: cover;"
                         onerror="this.onerror=null; this.src='{{ asset('assets/images/blank-profile-picture.png') }}';">
                    <span>
                        by {{ $challenge->user->user_userName }}
                        @if($challenge->user->account_status === 'banned')
                            <span class="badge badge-banned">Banned</span>
                        @endif
                    </span>
                </a>
            </div>

            @if(Auth::id() && Auth::id() != $challenge->user_id)
                <div class="text-center mb-4">
                    <small class="text-muted">Rate the author!</small>
                    <div class="star-rating-input" data-rated-user-id="{{ $challenge->user_id }}">
                        @for ($i = 5; $i >= 1; $i--)
                            <input type="radio" id="star{{ $i }}" name="rating" value="{{ $i }}" {{ $current_user_rating == $i ? 'checked' : '' }}>
                            <label for="star{{ $i }}" title="{{ $i }} stars"><i class="fas fa-star"></i></label>
                        @endfor
                    </div>
                </div>
            @endif

            <div class="text-center mb-4 position-relative">
                <img class="img-fluid rounded shadow-sm" style="max-height: 500px;" src="{{ asset('assets/uploads/' . $challenge->original_art_filename) }}">
            </div>

            <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex gap-3 align-items-center">
                    <button class="btn btn-link text-decoration-none text-muted p-0" id="likeButton" data-id="{{ $challenge->challenge_id }}">
                        <i class="fas fa-heart {{ $userHasLiked ? 'text-danger' : 'text-secondary' }}"></i> <span id="likeCount">{{ $like_count }}</span> Likes
                    </button>
                    <span class="text-muted"><i class="fas fa-comment"></i> {{ count($comments) }} Comments</span>
                </div>

                <div class="d-flex gap-2">
                    @auth
                        @if(Auth::id() == $challenge->user_id)
                            <div class="btn-group">
                                <a href="{{ route('challenges.edit', $challenge->challenge_id) }}" class="btn btn-outline-primary">Edit</a>
                                <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteChallengeModal">Delete</button>
                            </div>
                        @else
                            <button type="button" class="btn btn-outline-secondary rounded-pill px-3"
                                    onclick="openReportModal('challenge', {{ $challenge->challenge_id }}, '{{ addslashes($challenge->challenge_name) }}')">
                                <i class="fas fa-flag"></i> Report
                            </button>
                            <a href="{{ route('interpretations.create', ['challenge_id' => $challenge->challenge_id]) }}" class="btn btn-primary-custom rounded-pill px-4 py-2">Challenge this Art</a>
                        @endif
                    @else
                        <button type="button" class="btn btn-primary-custom rounded-pill px-4 py-2" onclick="showLoginModal()">Challenge this Art</button>
                    @endauth
                </div>
            </div>
            <hr>
            <p>{{ $challenge->challenge_description }}</p>
        </div>

        <div class="card p-4 mb-4">
            <h3>Interpretations ({{ $total_interpretations }})</h3>
            <div class="row g-4">
                @foreach($interpretations as $interp)
                    <div class="col-md-4">
                        <div class="card h-100 bg-light border-0 interpretation-card" data-interpretation-id="{{ $interp->interpretation_id }}">
                            <div class="card-body position-relative">
                                @auth
                                    @if(Auth::id() != $interp->user_id)
                                        <i class="fas fa-flag report-link position-absolute top-0 end-0 m-3" title="Report"
                                           onclick="openReportModal('interpretation', {{ $interp->interpretation_id }}, 'Interpretation by {{ $interp->user_userName }}')"></i>
                                    @endif
                                @endauth

                                <div class="d-flex align-items-center mb-2">
                                    <img src="{{ $interp->user_profile_pic ? asset('assets/uploads/'.$interp->user_profile_pic) : asset('assets/images/blank-profile-picture.png') }}" class="rounded-circle me-2" width="24" height="24" onerror="this.src='{{ asset('assets/images/blank-profile-picture.png') }}';">
                                    <small class="fw-bold">
                                        {{ $interp->user_userName }}
                                        @if($interp->account_status === 'banned') <span class="badge badge-banned">Banned</span> @endif
                                    </small>
                                </div>
                                <a href="#" class="d-block" data-bs-toggle="modal" data-bs-target="#interpretationModal"
                                   data-img-src="{{ asset('assets/uploads/' . $interp->art_filename) }}"
                                   data-artist-name="{{ $interp->user_userName }}"
                                   data-artist-avatar="{{ $interp->user_profile_pic ? asset('assets/uploads/'.$interp->user_profile_pic) : asset('assets/images/blank-profile-picture.png') }}"
                                   data-artist-id="{{ $interp->user_id }}"
                                   data-description="{{ $interp->description }}"
                                   data-interpretation-id="{{ $interp->interpretation_id }}"
                                   data-like-count="{{ $interp->like_count }}"
                                   data-user-has-liked="{{ $interp->user_has_liked }}">
                                    <img src="{{ asset('assets/uploads/' . $interp->art_filename) }}" class="img-fluid rounded mb-2 shadow-sm">
                                </a>
                                <p class="small text-muted fst-italic">"{{ $interp->description }}"</p>
                                <div class="small text-muted"><i class="fas fa-heart text-danger"></i> {{ $interp->like_count }}</div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            @if($total_interpretations > 6)
                <a href="{{ route('interpretations.index', ['challenge_id' => $challenge->challenge_id]) }}" class="btn btn-outline-secondary mt-3">View All</a>
            @endif
        </div>

        <div class="card p-4">
            <h3>Comments</h3>

            @auth
                <form action="{{ route('comments.store') }}" method="POST" class="mb-4">
                    @csrf
                    <input type="hidden" name="challenge_id" value="{{ $challenge->challenge_id }}">
                    <textarea name="comment_text" class="form-control mb-2" required placeholder="Write a comment..." rows="3"></textarea>
                    <button class="btn btn-primary-custom rounded-pill px-4">Post</button>
                </form>
            @else
                <div class="alert alert-light text-center mb-4 border">
                    <p class="mb-2 text-muted">Please login to join the discussion.</p>
                    <a href="{{ route('login') }}" class="btn btn-outline-primary rounded-pill px-4">Login</a>
                </div>
            @endguest

            @foreach($comments as $comment)
                <div class="d-flex mb-3">
                    <div class="flex-shrink-0">
                        <img class="rounded-circle border"
                             src="{{ $comment->user_profile_pic ? asset('assets/uploads/' . $comment->user_profile_pic) : asset('assets/images/blank-profile-picture.png') }}"
                             style="width: 40px; height: 40px; object-fit: cover;"
                             onerror="this.src='{{ asset('assets/images/blank-profile-picture.png') }}';">
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <div class="bg-light p-3 rounded position-relative">
                            <div class="fw-bold d-flex justify-content-between">
                                <span>
                                    {{ $comment->user_userName }}
                                    @if($comment->user_type === 'admin') <span class="badge bg-primary" style="font-size: 0.7em;">Admin</span> @endif
                                    @if($comment->account_status === 'banned') <span class="badge badge-banned">Banned</span> @endif
                                </span>
                                @auth
                                    @if(Auth::id() != $comment->user_id)
                                        <i class="fas fa-flag report-link text-muted" style="cursor:pointer;" title="Report"
                                           onclick="openReportModal('comment', {{ $comment->comment_id }}, 'Comment by {{ $comment->user_userName }}')"></i>
                                    @endif
                                @endauth
                            </div>
                            <p class="mb-0">{{ $comment->comment_text }}</p>
                        </div>
                        <small class="text-muted ms-2">{{ \Carbon\Carbon::parse($comment->created_at)->diffForHumans() }}</small>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="modal fade" id="loginPromptModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <div class="modal-content rounded-4 shadow-lg text-center p-4">
                <div class="mb-3"><i class="fas fa-user-lock fa-3x text-secondary"></i></div>
                <h5 class="fw-bold mb-2">Login Required</h5>
                <p class="text-muted small mb-4">You need to be logged in to perform this action.</p>
                <div class="d-grid gap-2">
                    <a href="{{ route('login') }}" class="btn btn-primary rounded-pill">Login</a>
                    <button type="button" class="btn btn-outline-secondary rounded-pill" data-bs-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="reportModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title"><i class="fas fa-flag me-2"></i>Report Content</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('report.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="type" id="reportType">
                        <input type="hidden" name="target_id" id="reportTargetId">
                        <p class="text-muted">Reporting: <strong id="reportTargetName"></strong></p>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Reason</label>
                            <select name="reason" class="form-select" required>
                                <option value="">Select a reason...</option>
                                <option value="Spam">Spam</option>
                                <option value="Harassment">Harassment</option>
                                <option value="Inappropriate">Inappropriate</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Details</label>
                            <textarea name="details" class="form-control" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger">Submit Report</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="deleteChallengeModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title"><i class="fas fa-exclamation-triangle me-2"></i>Delete Challenge</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this challenge?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form action="{{ route('challenges.destroy', $challenge->challenge_id) }}" method="POST">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete Permanently</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="interpretationModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <div id="modalArtistInfo"></div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center">
                    <img id="modalImage" src="" class="img-fluid rounded mb-3" style="max-height: 70vh;">
                    <p id="modalDescription" class="fst-italic text-muted"></p>
                </div>
                <div class="modal-footer" id="modalFooterActions"></div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="profanityModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4">
                <div class="modal-body p-5 text-center">
                    <i class="fas fa-exclamation-triangle text-warning fa-3x mb-3"></i>
                    <h3 class="fw-bold">Comment Rejected</h3>
                    <p class="text-muted">Please keep the conversation respectful.</p>
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
                    interpretationModal.querySelector('#modalArtistInfo').innerHTML = `<a href="{{ url('/profile') }}/${artistId}" class="d-flex align-items-center text-decoration-none text-dark"><img src="${artistAvatar}" class="rounded-circle me-2" width="32" height="32" style="object-fit:cover;"><span class="fw-bold">${artistName}</span></a>`;

                    const likeBtnClass = userHasLiked ? 'text-danger' : 'text-secondary';
                    interpretationModal.querySelector('#modalFooterActions').innerHTML = `<button id="modalLikeBtn" class="btn btn-outline-danger"><i id="modalLikeIcon" class="fas fa-heart ${likeBtnClass}"></i> <span id="modalLikeCount">${likeCount}</span></button>`;

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
                                    card.querySelector('.card-like-count').textContent = data.likeCount;
                                    const cardIcon = card.querySelector('.fa-heart');
                                    cardIcon.classList.toggle('text-danger'); cardIcon.classList.toggle('text-secondary');
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
