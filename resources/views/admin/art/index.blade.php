<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Manage Artworks</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <link href="{{ asset('assets/css/navbar.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">

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

        /* --- DASHBOARD CONTAINER --- */
        .dashboard-container {
            max-width: 1400px;
            margin: 2rem auto;
            background: #fff;
            padding: 2.5rem;
            border-radius: var(--card-radius);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            border: 1px solid rgba(0,0,0,0.05);
        }

        /* --- TABS --- */
        .nav-pills .nav-link {
            color: var(--text-muted);
            font-weight: 600;
            border-radius: 50px;
            padding: 0.6rem 1.5rem;
            margin-right: 0.5rem;
            transition: all 0.2s ease;
        }

        .nav-pills .nav-link.active {
            background-color: var(--primary-color);
            color: #fff;
            box-shadow: 0 4px 10px rgba(90, 75, 218, 0.3);
        }

        .nav-pills .nav-link:hover:not(.active) {
            background-color: #f1f5f9;
            color: var(--primary-color);
        }

        /* --- FILTERS --- */
        .filter-section {
            background: #f8f9fa;
            padding: 1.5rem;
            border-radius: 12px;
            margin-bottom: 2rem;
            border: 1px solid #e2e8f0;
        }

        .form-control, .form-select {
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            padding: 0.7rem 1rem;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(90, 75, 218, 0.1);
        }

        /* --- VIEW TOGGLES --- */
        .btn-toggle {
            background: #fff;
            border: 1px solid #e2e8f0;
            color: var(--text-muted);
            font-weight: 600;
            padding: 0.6rem 1rem;
        }

        .btn-toggle.active {
            background: var(--primary-color);
            color: #fff;
            border-color: var(--primary-color);
        }

        .btn-toggle:hover:not(.active) {
            background: #f1f5f9;
            color: var(--primary-color);
        }

        /* --- CARDS --- */
        .art-card {
            border: 1px solid rgba(0,0,0,0.05);
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0,0,0,0.02);
            transition: 0.3s ease;
            height: 100%;
            background: #fff;
        }

        .art-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.08);
        }

        .card-img-top {
            width: 100%;
            height: 220px;
            object-fit: cover;
        }

        /* --- BADGES & BUTTONS --- */
        .stats-badge {
            background: #f8f9fa;
            padding: 0.4rem 0.8rem;
            border-radius: 50px;
            font-size: 0.8rem;
            margin-right: 0.5rem;
            border: 1px solid #e2e8f0;
            color: var(--text-muted);
            font-weight: 600;
        }

        .btn-archive {
            background-color: #64748b; /* Slate 500 */
            color: white;
            border: none;
            border-radius: 50px;
            width: 100%;
            padding: 0.5rem;
            font-weight: 600;
            font-size: 0.9rem;
            transition: 0.3s;
        }
        .btn-archive:hover { background-color: #475569; color: white; }

        .btn-restore {
            background-color: #10b981; /* Green */
            color: white;
            border: none;
            border-radius: 50px;
            width: 100%;
            padding: 0.5rem;
            font-weight: 600;
            font-size: 0.9rem;
            transition: 0.3s;
        }
        .btn-restore:hover { background-color: #059669; color: white; }

        /* --- TABLE STYLING --- */
        .table-hover tbody tr:hover { background-color: #f8f9fa; }
        .table th {
            font-weight: 600;
            color: var(--text-muted);
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.5px;
            border-bottom: 2px solid #f1f5f9;
            padding-bottom: 1rem;
        }
        .table td {
            vertical-align: middle;
            padding: 1rem 0.5rem;
            border-bottom: 1px solid #f1f5f9;
            color: var(--text-dark);
        }
    </style>
</head>

<body>
    @include('partials.navbar')

    <div class="dashboard-container">
        <div class="text-center mb-5">
            <h2 class="fw-bold mb-2" style="color: var(--text-dark);">
                <i class="bi bi-images me-2" style="color: var(--primary-color);"></i> Manage Artworks
            </h2>
            <p class="text-muted">Review, archive, or restore content across the platform.</p>
        </div>

        <ul class="nav nav-pills mb-4 justify-content-center" id="pills-tab" role="tablist">
            <li class="nav-item">
                <button class="nav-link active" id="pills-challenges-tab" data-bs-toggle="pill" data-bs-target="#pills-challenges" type="button">
                    Challenges <span class="badge bg-white text-primary shadow-sm ms-2 border">{{ $challenges->count() }}</span>
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link" id="pills-interps-tab" data-bs-toggle="pill" data-bs-target="#pills-interps" type="button">
                    Interpretations <span class="badge bg-white text-primary shadow-sm ms-2 border">{{ $interpretations->count() }}</span>
                </button>
            </li>
        </ul>

        <div class="filter-section">
            <div class="row align-items-center g-3">
                <div class="col-md-5">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                        <input type="text" class="form-control border-start-0 ps-0" id="searchInput" placeholder="Search by title or artist...">
                    </div>
                </div>
                <div class="col-md-4">
                    <select class="form-select" id="categoryFilter">
                        <option value="">All Categories</option>
                        <option value="digital_painting">Digital Painting</option>
                        <option value="sci-fi">Sci-Fi</option>
                        <option value="fantasy">Fantasy</option>
                        <option value="abstract">Abstract</option>
                        <option value="portraits">Portraits</option>
                        <option value="landscapes">Landscapes</option>
                    </select>
                </div>
                <div class="col-md-3 text-md-end">
                    <div class="btn-group shadow-sm rounded-pill overflow-hidden" role="group">
                        <button class="btn btn-toggle active" id="cardViewBtn"><i class="bi bi-grid-3x3-gap me-1"></i> Cards</button>
                        <button class="btn btn-toggle" id="tableViewBtn"><i class="bi bi-list-ul me-1"></i> Table</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="tab-content" id="pills-tabContent">

            <div class="tab-pane fade show active" id="pills-challenges" role="tabpanel">
                <div class="view-mode card-view row g-4">
                    @forelse($challenges as $art)
                        <div class="col-lg-3 col-md-6 artwork-item"
                             data-title="{{ strtolower($art->challenge_name) }}"
                             data-artist="{{ strtolower($art->user_userName) }}"
                             data-category="{{ strtolower($art->category) }}">
                            <div class="card art-card">
                                <div class="position-relative">
                                    <img src="{{ asset('assets/uploads/' . $art->original_art_filename) }}" class="card-img-top" style="opacity: {{ $art->status === 'archived' ? '0.5' : '1' }}">
                                    @if($art->status === 'archived')
                                        <span class="position-absolute top-0 end-0 badge bg-secondary m-2 shadow-sm">Archived</span>
                                    @endif
                                </div>
                                <div class="card-body d-flex flex-column p-4">
                                    <h6 class="fw-bold text-truncate text-dark mb-1">{{ $art->challenge_name }}</h6>
                                    <small class="text-muted mb-3">by {{ $art->user_userName }}</small>

                                    <div class="mb-4">
                                        <span class="stats-badge"><i class="bi bi-heart-fill text-danger me-1"></i> {{ $art->like_count }}</span>
                                        <span class="stats-badge"><i class="bi bi-palette-fill text-primary me-1"></i> {{ $art->interpretation_count }}</span>
                                    </div>

                                    <form action="{{ route('admin.art.archive') }}" method="POST" class="mt-auto trigger-form" id="cForm_{{ $art->challenge_id }}">
                                        @csrf <input type="hidden" name="art_id" value="{{ $art->challenge_id }}">
                                        <button type="button" class="btn {{ $art->status === 'archived' ? 'btn-restore' : 'btn-archive' }} trigger-modal"
                                                data-type="{{ $art->status === 'archived' ? 'restore' : 'archive' }}" data-form="cForm_{{ $art->challenge_id }}">
                                            <i class="bi {{ $art->status === 'archived' ? 'bi-arrow-counterclockwise' : 'bi-archive' }} me-2"></i>
                                            {{ $art->status === 'archived' ? 'Restore' : 'Archive' }}
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12 text-center py-5">
                            <div class="text-muted p-5 rounded-4 bg-light">
                                <i class="bi bi-folder2-open display-4 mb-3 d-block opacity-50"></i>
                                No challenges found.
                            </div>
                        </div>
                    @endforelse
                </div>

                <div class="view-mode table-view table-responsive" style="display:none;">
                    <table class="table table-hover align-middle">
                        <thead class="table-light"><tr><th>Preview</th><th>Title</th><th>Artist</th><th>Category</th><th>Stats</th><th class="text-end">Action</th></tr></thead>
                        <tbody>
                            @foreach($challenges as $art)
                                <tr class="artwork-item"
                                    data-title="{{ strtolower($art->challenge_name) }}"
                                    data-artist="{{ strtolower($art->user_userName) }}"
                                    data-category="{{ strtolower($art->category) }}">
                                    <td><img src="{{ asset('assets/uploads/' . $art->original_art_filename) }}" width="50" height="50" class="rounded border"></td>
                                    <td>
                                        <span class="fw-semibold text-dark">{{ $art->challenge_name }}</span>
                                        @if($art->status === 'archived') <span class="badge bg-secondary ms-2 rounded-pill">Archived</span> @endif
                                    </td>
                                    <td class="text-muted">{{ $art->user_userName }}</td>
                                    <td><span class="badge bg-light text-dark border">{{ ucfirst(str_replace('_', ' ', $art->category)) }}</span></td>
                                    <td><i class="bi bi-heart-fill text-danger"></i> {{ $art->like_count }}</td>
                                    <td class="text-end">
                                        <form action="{{ route('admin.art.archive') }}" method="POST" id="ctForm_{{ $art->challenge_id }}">
                                            @csrf <input type="hidden" name="art_id" value="{{ $art->challenge_id }}">
                                            <button type="button" class="btn btn-sm {{ $art->status === 'archived' ? 'btn-restore' : 'btn-archive' }} trigger-modal rounded-pill px-3"
                                                data-type="{{ $art->status === 'archived' ? 'restore' : 'archive' }}" data-form="ctForm_{{ $art->challenge_id }}">
                                                {{ $art->status === 'archived' ? 'Restore' : 'Archive' }}
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="tab-pane fade" id="pills-interps" role="tabpanel">
                <div class="view-mode card-view row g-4">
                    @forelse($interpretations as $interp)
                        <div class="col-lg-3 col-md-6 artwork-item"
                             data-title="{{ strtolower($interp->challenge_name) }}"
                             data-artist="{{ strtolower($interp->user_userName) }}"
                             data-category="{{ strtolower($interp->category) }}">
                            <div class="card art-card">
                                <div class="position-relative">
                                    <img src="{{ asset('assets/uploads/' . $interp->art_filename) }}" class="card-img-top" style="opacity: {{ $interp->status === 'archived' ? '0.5' : '1' }}">
                                    @if($interp->status === 'archived')
                                        <span class="position-absolute top-0 end-0 badge bg-secondary m-2 shadow-sm">Archived</span>
                                    @endif
                                </div>
                                <div class="card-body d-flex flex-column p-4">
                                    <small class="text-muted text-uppercase fw-bold" style="font-size: 0.7rem;">Interpretation For</small>
                                    <h6 class="fw-bold text-truncate text-dark mb-1">{{ $interp->challenge_name }}</h6>
                                    <small class="text-muted mb-3">by {{ $interp->user_userName }}</small>

                                    <div class="mb-4">
                                        <span class="stats-badge"><i class="bi bi-heart-fill text-danger me-1"></i> {{ $interp->like_count }}</span>
                                    </div>

                                    <form action="{{ route('admin.interpretations.archive') }}" method="POST" class="mt-auto trigger-form" id="iForm_{{ $interp->interpretation_id }}">
                                        @csrf <input type="hidden" name="interpretation_id" value="{{ $interp->interpretation_id }}">
                                        <button type="button" class="btn {{ $interp->status === 'archived' ? 'btn-restore' : 'btn-archive' }} trigger-modal"
                                                data-type="{{ $interp->status === 'archived' ? 'restore' : 'archive' }}" data-form="iForm_{{ $interp->interpretation_id }}">
                                            <i class="bi {{ $interp->status === 'archived' ? 'bi-arrow-counterclockwise' : 'bi-archive' }} me-2"></i>
                                            {{ $interp->status === 'archived' ? 'Restore' : 'Archive' }}
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12 text-center py-5">
                            <div class="text-muted p-5 rounded-4 bg-light">
                                <i class="bi bi-folder2-open display-4 mb-3 d-block opacity-50"></i>
                                No interpretations found.
                            </div>
                        </div>
                    @endforelse
                </div>

                <div class="view-mode table-view table-responsive" style="display:none;">
                    <table class="table table-hover align-middle">
                        <thead class="table-light"><tr><th>Preview</th><th>For Challenge</th><th>Artist</th><th>Category</th><th>Stats</th><th class="text-end">Action</th></tr></thead>
                        <tbody>
                            @foreach($interpretations as $interp)
                                <tr class="artwork-item"
                                    data-title="{{ strtolower($interp->challenge_name) }}"
                                    data-artist="{{ strtolower($interp->user_userName) }}"
                                    data-category="{{ strtolower($interp->category) }}">
                                    <td><img src="{{ asset('assets/uploads/' . $interp->art_filename) }}" width="50" height="50" class="rounded border"></td>
                                    <td>
                                        <span class="fw-semibold text-dark">{{ $interp->challenge_name }}</span>
                                        @if($interp->status === 'archived') <span class="badge bg-secondary ms-2 rounded-pill">Archived</span> @endif
                                    </td>
                                    <td class="text-muted">{{ $interp->user_userName }}</td>
                                    <td><span class="badge bg-light text-dark border">{{ ucfirst(str_replace('_', ' ', $interp->category)) }}</span></td>
                                    <td><i class="bi bi-heart-fill text-danger"></i> {{ $interp->like_count }}</td>
                                    <td class="text-end">
                                        <form action="{{ route('admin.interpretations.archive') }}" method="POST" id="itForm_{{ $interp->interpretation_id }}">
                                            @csrf <input type="hidden" name="interpretation_id" value="{{ $interp->interpretation_id }}">
                                            <button type="button" class="btn btn-sm {{ $interp->status === 'archived' ? 'btn-restore' : 'btn-archive' }} trigger-modal rounded-pill px-3"
                                                data-type="{{ $interp->status === 'archived' ? 'restore' : 'archive' }}" data-form="itForm_{{ $interp->interpretation_id }}">
                                                {{ $interp->status === 'archived' ? 'Restore' : 'Archive' }}
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div id="noResultsMsg" class="alert alert-light text-center mt-4 border-0 shadow-sm" style="display:none;">
            <i class="bi bi-search me-2"></i> No results match your search filter.
        </div>
    </div>

    <div id="message-container"></div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const cardViewBtn = document.getElementById('cardViewBtn');
            const tableViewBtn = document.getElementById('tableViewBtn');
            const searchInput = document.getElementById('searchInput');
            const categoryFilter = document.getElementById('categoryFilter');

            // 1. View Toggle Logic
            function setView(view) {
                if(view === 'card') {
                    document.querySelectorAll('.card-view').forEach(el => el.style.display = 'flex');
                    document.querySelectorAll('.table-view').forEach(el => el.style.display = 'none');
                    cardViewBtn.classList.add('active'); tableViewBtn.classList.remove('active');
                } else {
                    document.querySelectorAll('.card-view').forEach(el => el.style.display = 'none');
                    document.querySelectorAll('.table-view').forEach(el => el.style.display = 'block');
                    tableViewBtn.classList.add('active'); cardViewBtn.classList.remove('active');
                }
            }
            cardViewBtn.addEventListener('click', () => setView('card'));
            tableViewBtn.addEventListener('click', () => setView('table'));

            // 2. Filtering Logic
            function filterItems() {
                const term = searchInput.value.toLowerCase();
                const cat = categoryFilter.value.toLowerCase();
                let visibleCount = 0;

                document.querySelectorAll('.artwork-item').forEach(item => {
                    const title = item.dataset.title;
                    const artist = item.dataset.artist;
                    const category = item.dataset.category;

                    const matchesSearch = title.includes(term) || artist.includes(term);
                    const matchesCat = cat === '' || category === cat || category.includes(cat);

                    if(matchesSearch && matchesCat) {
                        item.style.display = '';
                        visibleCount++;
                    } else {
                        item.style.display = 'none';
                    }
                });

                document.getElementById('noResultsMsg').style.display = visibleCount === 0 ? 'block' : 'none';
            }
            searchInput.addEventListener('input', filterItems);
            categoryFilter.addEventListener('change', filterItems);

            // 3. Modal Logic
            document.querySelectorAll('.trigger-modal').forEach(btn => {
                btn.addEventListener('click', function() {
                    const type = this.dataset.type;
                    const formId = this.dataset.form;
                    const title = type === 'archive' ? 'Archive Content' : 'Restore Content';
                    const msg = type === 'archive' ? 'This content will be hidden from the public view.' : 'This content will be visible to the public again.';
                    const variant = type === 'archive' ? 'secondary' : 'success';
                    const icon = type === 'archive' ? 'bi-archive' : 'bi-check-circle';

                    const container = document.getElementById('message-container');
                    container.innerHTML = `
                    <div class="modal fade" id="confirmModal" tabindex="-1">
                        <div class="modal-dialog modal-sm modal-dialog-centered">
                            <div class="modal-content rounded-4 border-0 shadow-lg">
                                <div class="modal-header border-0 pb-0 justify-content-center">
                                    <div class="bg-light rounded-circle p-3 mb-2 mt-2">
                                        <i class="bi ${icon} fs-2 text-${variant}"></i>
                                    </div>
                                </div>
                                <div class="modal-body text-center pt-0 pb-4">
                                    <h5 class="fw-bold mb-2">${title}</h5>
                                    <p class="text-muted mb-0 small">${msg}</p>
                                </div>
                                <div class="modal-footer border-0 pt-0 px-4 pb-4 flex-nowrap">
                                    <button type="button" class="btn btn-light rounded-pill w-100 me-2" data-bs-dismiss="modal">Cancel</button>
                                    <button type="button" id="confirmActionBtn" class="btn btn-${variant} rounded-pill w-100 text-white shadow-sm">${type.charAt(0).toUpperCase() + type.slice(1)}</button>
                                </div>
                            </div>
                        </div>
                    </div>`;
                    const modal = new bootstrap.Modal(document.getElementById('confirmModal'));
                    modal.show();
                    document.getElementById('confirmActionBtn').onclick = () => document.getElementById(formId).submit();
                });
            });
        });
    </script>
</body>
</html>
