<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Interpretations for {{ $challenge->challenge_name }}</title>
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

        /* --- FORCE NAVBAR PURPLE --- */
        .custom-navbar {
            background-color: var(--primary-color) !important;
            backdrop-filter: none;
            -webkit-backdrop-filter: none;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        /* --- PAGE HEADER --- */
        .page-header-card {
            background: #fff;
            border-radius: var(--card-radius);
            padding: 2rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            border: 1px solid rgba(0,0,0,0.05);
            margin-bottom: 2rem;
            text-align: center;
        }

        /* --- INTERPRETATION CARDS --- */
        .interpretation-card {
            background: #fff;
            border-radius: 12px;
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border: 1px solid rgba(0,0,0,0.05);
            height: 100%;
        }

        .interpretation-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0,0,0,0.1) !important;
        }

        .badge-banned {
            background-color: #ef4444;
            color: white;
            font-size: 0.65em;
            margin-left: 4px;
            padding: 0.2em 0.5em;
            border-radius: 4px;
        }

        /* --- BUTTONS --- */
        .floating-back-btn {
            z-index: 1050;
            background-color: #fff;
            color: var(--text-dark);
            border: 1px solid rgba(0,0,0,0.1);
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .floating-back-btn:hover {
            background-color: var(--primary-color);
            color: white;
            border-color: var(--primary-color);
            transform: translateY(-2px);
        }

        .btn-primary-custom {
            background-color: var(--primary-color);
            color: white;
            border: none;
        }

        .btn-primary-custom:hover {
            background-color: var(--primary-hover);
            color: white;
        }
    </style>
</head>
<body>
    @include('partials.navbar')

    <div class="container py-4">
        <header class="page-header-card mx-auto" style="max-width: 700px;">
            <h1 class="h3 fw-bold" style="color: var(--text-dark);">All Interpretations</h1>
            <p class="text-muted mb-0">Browsing gallery for: <strong style="color: var(--primary-color);">{{ $challenge->challenge_name }}</strong></p>
        </header>

        <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-4 g-4">
            @forelse($interpretations as $interp)
                <div class="col">
                    <div class="card interpretation-card shadow-sm" data-interpretation-id="{{ $interp->interpretation_id }}">
                        <div class="card-body d-flex flex-column p-3">
                            <div class="d-flex align-items-center mb-3">
                                <img src="{{ $interp->user_profile_pic ? asset('assets/uploads/'.$interp->user_profile_pic) : asset('assets/images/blank-profile-picture.png') }}"
                                     class="rounded-circle me-2 border" width="28" height="28" style="object-fit: cover;"
                                     onerror="this.src='{{ asset('assets/images/blank-profile-picture.png') }}';">
                                <span class="small fw-bold text-truncate text-dark">{{ $interp->user_userName }}</span>
                                @if($interp->account_status === 'banned') <span class="badge badge-banned">Banned</span> @endif
                            </div>

                            <a href="#" class="d-block mb-3 rounded overflow-hidden" data-bs-toggle="modal" data-bs-target="#interpretationModal"
                               data-img-src="{{ asset('assets/uploads/' . $interp->art_filename) }}"
                               data-artist-name="{{ $interp->user_userName }}"
                               data-artist-avatar="{{ $interp->user_profile_pic ? asset('assets/uploads/'.$interp->user_profile_pic) : asset('assets/images/blank-profile-picture.png') }}"
                               data-artist-id="{{ $interp->author_id ?? $interp->user_id }}"
                               data-description="{{ $interp->description }}"
                               data-interpretation-id="{{ $interp->interpretation_id }}"
                               data-like-count="{{ $interp->like_count }}"
                               data-user-has-liked="{{ $interp->user_has_liked }}">
                                <img src="{{ asset('assets/uploads/'.$interp->art_filename) }}" class="img-fluid w-100" style="height: 250px; object-fit: cover; transition: transform 0.5s ease;">
                            </a>

                            <p class="small text-muted fst-italic mt-auto text-truncate mb-2">
                                {{ $interp->description ? '"'.$interp->description.'"' : '' }}
                            </p>

                            <div class="border-top pt-2 mt-auto d-flex align-items-center text-muted small fw-semibold">
                                <i class="fas fa-heart {{ $interp->user_has_liked ? 'text-danger' : 'text-secondary' }} me-1"></i>
                                <span class="card-like-count">{{ $interp->like_count }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center">
                    <div class="alert alert-light shadow-sm border-0 py-5 rounded-4">
                        <i class="fas fa-images fa-2x text-muted mb-3"></i>
                        <p class="mb-0 text-muted">No interpretations submitted yet.</p>
                    </div>
                </div>
            @endforelse
        </div>

        <a href="{{ route('challenges.show', $challenge->challenge_id) }}" class="btn floating-back-btn shadow rounded-pill px-4 py-2 position-fixed bottom-0 end-0 m-4">
            <i class="fas fa-arrow-left me-2"></i> Back to Challenge
        </a>
    </div>

    <div class="modal fade" id="loginPromptModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <div class="modal-content rounded-4 shadow-lg text-center p-4 border-0">
                <div class="mb-3"><i class="fas fa-lock fa-3x" style="color: var(--primary-color);"></i></div>
                <h5 class="fw-bold mb-2">Login Required</h5>
                <p class="text-muted small mb-4">You need to be logged in to perform this action.</p>
                <div class="d-grid gap-2">
                    <a href="{{ route('login') }}" class="btn btn-primary-custom rounded-pill">Login</a>
                    <button type="button" class="btn btn-outline-secondary rounded-pill" data-bs-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="interpretationModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content rounded-4 border-0 shadow-lg">
                <div class="modal-header border-0 pb-0">
                    <div id="modalArtistInfo"></div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center p-4">
                    <img id="modalImage" src="" class="img-fluid rounded shadow-sm" style="max-height: 70vh; width: auto; object-fit: contain;">
                    <div class="mt-3">
                        <p id="modalDescription" class="fst-italic text-muted mb-0"></p>
                    </div>
                </div>
                <div class="modal-footer border-0 justify-content-center pt-0 pb-4" id="modalFooterActions"></div>
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

                    // --- FIXED: ADDED ONERROR HANDLER TO MODAL JS ---
                    interpretationModal.querySelector('#modalArtistInfo').innerHTML = `
                        <a href="${profileUrl}" class="d-flex align-items-center text-decoration-none text-dark">
                            <img src="${artistAvatar}"
                                 class="rounded-circle me-2"
                                 width="32" height="32"
                                 style="object-fit:cover;"
                                 onerror="this.onerror=null; this.src='{{ asset('assets/images/blank-profile-picture.png') }}';">
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
