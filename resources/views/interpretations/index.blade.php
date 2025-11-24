<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Interpretations for {{ $challenge->challenge_name }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="{{ asset('assets/css/navbar.css') }}" rel="stylesheet">
    <style>
        body { background-image: linear-gradient(to bottom, #a6e7ff, #c3b4fc); min-height: 100vh; padding-top: 20px; }
        .card { border: none; border-radius: 15px; transition: transform 0.2s; }
        .card:hover { transform: translateY(-5px); box-shadow: 0 8px 25px rgba(0,0,0,0.15); }
        .interpretation-card { background-color: #f9f9f9; }
        .badge-banned { background-color: #dc3545; color: white; font-size: 0.65em; margin-left: 4px; }
        .floating-back-btn { z-index: 1050; }
    </style>
</head>
<body>
    @include('partials.navbar')

    <div class="container py-3 py-md-5">
        <header class="text-center mb-4 mb-md-5 bg-white p-3 p-md-4 rounded-3 shadow-sm mx-auto" style="max-width: 600px;">
            <h1 class="h3 fw-bold">All Interpretations</h1>
            <p class="text-muted mb-0">For: <strong>{{ $challenge->challenge_name }}</strong></p>
        </header>

        <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-4 g-3 g-md-4">
            @forelse($interpretations as $interp)
                <div class="col">
                    <div class="card h-100 interpretation-card" data-interpretation-id="{{ $interp->interpretation_id }}">
                        <div class="card-body d-flex flex-column p-3">
                            <div class="d-flex align-items-center mb-2">
                                <img src="{{ $interp->user_profile_pic ? asset('assets/uploads/'.$interp->user_profile_pic) : asset('assets/images/blank-profile-picture.png') }}"
                                     class="rounded-circle me-2 border" width="24" height="24" style="object-fit: cover;">
                                <span class="small fw-bold text-truncate">{{ $interp->user_userName }}</span>
                                @if($interp->account_status === 'banned') <span class="badge badge-banned">Banned</span> @endif
                            </div>

                            <a href="#" class="d-block mb-3" data-bs-toggle="modal" data-bs-target="#interpretationModal"
                               data-img-src="{{ asset('assets/uploads/' . $interp->art_filename) }}"
                               data-artist-name="{{ $interp->user_userName }}"
                               data-artist-avatar="{{ $interp->user_profile_pic ? asset('assets/uploads/'.$interp->user_profile_pic) : asset('assets/images/blank-profile-picture.png') }}"
                               data-artist-id="{{ $interp->author_id ?? $interp->user_id }}"
                               data-description="{{ $interp->description }}"
                               data-interpretation-id="{{ $interp->interpretation_id }}"
                               data-like-count="{{ $interp->like_count }}"
                               data-user-has-liked="{{ $interp->user_has_liked }}">
                                <img src="{{ asset('assets/uploads/'.$interp->art_filename) }}" class="img-fluid rounded shadow-sm w-100" style="height: 250px; object-fit: cover;">
                            </a>

                            <p class="small text-muted fst-italic mt-auto text-truncate">{{ $interp->description }}</p>

                            <div class="border-top pt-2 mt-2 text-muted small">
                                <i class="fas fa-heart {{ $interp->user_has_liked ? 'text-danger' : 'text-secondary' }}"></i>
                                <span class="card-like-count">{{ $interp->like_count }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center">
                    <div class="alert alert-light shadow-sm border-0">No interpretations yet.</div>
                </div>
            @endforelse
        </div>

        <a href="{{ route('challenges.show', $challenge->challenge_id) }}" class="btn btn-light shadow position-fixed bottom-0 end-0 m-3 m-md-4 rounded-pill px-4 floating-back-btn">
            <i class="fas fa-arrow-left me-2"></i> Back
        </a>
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

    <div class="modal fade" id="interpretationModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content rounded-4">
                <div class="modal-header border-0">
                    <div id="modalArtistInfo"></div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center p-0">
                    <img id="modalImage" src="" class="img-fluid" style="max-height: 70vh; width: 100%; object-fit: contain; background: #f8f9fa;">
                    <div class="p-3">
                        <p id="modalDescription" class="fst-italic text-muted mb-0"></p>
                    </div>
                </div>
                <div class="modal-footer border-0 justify-content-center" id="modalFooterActions"></div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function showLoginModal() {
            new bootstrap.Modal(document.getElementById('loginPromptModal')).show();
        }

        document.addEventListener('DOMContentLoaded', () => {
            const isUserLoggedIn = @auth true @else false @endauth;
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

                    const profileUrl = "{{ url('/profile') }}/" + artistId;
                    interpretationModal.querySelector('#modalArtistInfo').innerHTML = `
                        <a href="${profileUrl}" class="d-flex align-items-center text-decoration-none text-dark">
                            <img src="${artistAvatar}" class="rounded-circle me-2" width="32" height="32" style="object-fit:cover;">
                            <span class="fw-bold">${artistName}</span>
                        </a>`;

                    const likeBtnClass = userHasLiked ? 'text-danger' : 'text-secondary';
                    const footer = interpretationModal.querySelector('#modalFooterActions');
                    footer.innerHTML = `
                        <button id="modalLikeBtn" class="btn btn-outline-danger rounded-pill px-4">
                            <i id="modalLikeIcon" class="fas fa-heart ${likeBtnClass}"></i>
                            <span id="modalLikeCount" class="ms-1">${likeCount}</span>
                        </button>`;

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
                                if (data.userHasLiked) {
                                    icon.classList.remove('text-secondary');
                                    icon.classList.add('text-danger');
                                } else {
                                    icon.classList.remove('text-danger');
                                    icon.classList.add('text-secondary');
                                }

                                const card = document.querySelector(`.interpretation-card[data-interpretation-id="${interpretationId}"]`);
                                if (card) {
                                    card.querySelector('.card-like-count').textContent = data.likeCount;
                                    const cardIcon = card.querySelector('.fa-heart');
                                    if (data.userHasLiked) {
                                        cardIcon.classList.remove('text-secondary');
                                        cardIcon.classList.add('text-danger');
                                    } else {
                                        cardIcon.classList.remove('text-danger');
                                        cardIcon.classList.add('text-secondary');
                                    }
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
