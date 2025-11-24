<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Manage Artworks</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="{{ asset('assets/css/navbar.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        :root {
            --primary-bg: #8c76ec;
            --secondary-bg: #a6e7ff;
            --light-purple: #c3b4fc;
            --dark-purple-border: #7b68ee;
            --text-dark: #333;
        }

        body {
            background-color: var(--secondary-bg);
            font-family: 'Inter', sans-serif;
            padding-top: 20px;
        }

        .dashboard-container {
            max-width: 1400px;
            margin: 3rem auto;
            background: #fff;
            padding: 2.5rem;
            border-radius: 20px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.12);
        }

        h2 { color: var(--primary-bg); font-weight: bold; text-align: center; margin-bottom: 2rem; }

        /* Tabs */
        .nav-pills .nav-link { color: var(--text-dark); font-weight: 600; border-radius: 30px; padding: 0.6rem 1.5rem; margin-right: 10px; }
        .nav-pills .nav-link.active { background-color: var(--primary-bg); color: #fff; }

        /* Filter Section */
        .filter-section { background: #f8f9fa; padding: 1.5rem; border-radius: 15px; margin-bottom: 2rem; }

        /* View Toggles */
        .btn-toggle { background: #fff; border: 2px solid var(--light-purple); color: var(--primary-bg); }
        .btn-toggle.active { background: var(--primary-bg); color: #fff; }
        .btn-toggle:hover { background: var(--light-purple); color: #fff; }

        /* Cards */
        .art-card { border: none; border-radius: 15px; overflow: hidden; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1); transition: 0.3s; height: 100%; }
        .art-card:hover { transform: translateY(-5px); box-shadow: 0 8px 20px rgba(140, 118, 236, 0.2); }
        .art-card img { width: 100%; height: 220px; object-fit: cover; }

        /* Badges & Buttons */
        .stats-badge { background: #f0f0f0; padding: 0.25rem 0.75rem; border-radius: 20px; font-size: 0.85rem; margin-right: 0.5rem; }
        .btn-archive { background-color: #6c757d; color: white; border: none; border-radius: 20px; width: 100%; transition: 0.3s; }
        .btn-archive:hover { background-color: #5a6268; color: white; }
        .btn-restore { background-color: #28a745; color: white; border: none; border-radius: 20px; width: 100%; transition: 0.3s; }
        .btn-restore:hover { background-color: #218838; color: white; }
        .status-archived { background-color: #6c757d; color: white; font-size: 0.7em; padding: 2px 6px; border-radius: 4px; margin-left: 5px; vertical-align: middle; }
    </style>
</head>

<body>
    @include('partials.navbar')

    <div class="dashboard-container">
        <h2><i class="bi bi-images me-2"></i>Manage Artworks</h2>

        <ul class="nav nav-pills mb-4 justify-content-center" id="pills-tab" role="tablist">
            <li class="nav-item">
                <button class="nav-link active" id="pills-challenges-tab" data-bs-toggle="pill" data-bs-target="#pills-challenges" type="button">
                    Challenges <span class="badge bg-light text-dark ms-1">{{ $challenges->count() }}</span>
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link" id="pills-interps-tab" data-bs-toggle="pill" data-bs-target="#pills-interps" type="button">
                    Interpretations <span class="badge bg-light text-dark ms-1">{{ $interpretations->count() }}</span>
                </button>
            </li>
        </ul>

        <div class="filter-section">
            <div class="row align-items-center g-3">
                <div class="col-md-5">
                    <input type="text" class="form-control" id="searchInput" placeholder="🔍 Search by title, artist...">
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
                    <div class="btn-group" role="group">
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
                                    @if($art->status === 'archived') <span class="position-absolute top-0 end-0 badge bg-secondary m-2">Archived</span> @endif
                                </div>
                                <div class="card-body d-flex flex-column">
                                    <h6 class="fw-bold text-truncate">{{ $art->challenge_name }}</h6>
                                    <small class="text-muted mb-2">by {{ $art->user_userName }}</small>
                                    <div class="mb-3">
                                        <span class="stats-badge"><i class="bi bi-heart-fill text-danger"></i> {{ $art->like_count }}</span>
                                        <span class="stats-badge"><i class="bi bi-palette-fill text-primary"></i> {{ $art->interpretation_count }}</span>
                                    </div>
                                    <form action="{{ route('admin.art.archive') }}" method="POST" class="mt-auto trigger-form" id="cForm_{{ $art->challenge_id }}">
                                        @csrf <input type="hidden" name="art_id" value="{{ $art->challenge_id }}">
                                        <button type="button" class="btn {{ $art->status === 'archived' ? 'btn-restore' : 'btn-archive' }} trigger-modal"
                                                data-type="{{ $art->status === 'archived' ? 'restore' : 'archive' }}" data-form="cForm_{{ $art->challenge_id }}">
                                            {{ $art->status === 'archived' ? 'Restore' : 'Archive' }}
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12 text-center py-5 text-muted">No challenges found.</div>
                    @endforelse
                </div>

                <div class="view-mode table-view table-responsive" style="display:none;">
                    <table class="table table-hover align-middle">
                        <thead class="table-light"><tr><th>Preview</th><th>Title</th><th>Artist</th><th>Category</th><th>Stats</th><th>Action</th></tr></thead>
                        <tbody>
                            @foreach($challenges as $art)
                                <tr class="artwork-item"
                                    data-title="{{ strtolower($art->challenge_name) }}"
                                    data-artist="{{ strtolower($art->user_userName) }}"
                                    data-category="{{ strtolower($art->category) }}">
                                    <td><img src="{{ asset('assets/uploads/' . $art->original_art_filename) }}" width="50" height="50" class="rounded"></td>
                                    <td>{{ $art->challenge_name }} @if($art->status === 'archived') <span class="badge bg-secondary ms-1">Archived</span> @endif</td>
                                    <td>{{ $art->user_userName }}</td>
                                    <td><span class="badge bg-secondary">{{ $art->category }}</span></td>
                                    <td><i class="bi bi-heart-fill text-danger"></i> {{ $art->like_count }}</td>
                                    <td>
                                        <form action="{{ route('admin.art.archive') }}" method="POST" id="ctForm_{{ $art->challenge_id }}">
                                            @csrf <input type="hidden" name="art_id" value="{{ $art->challenge_id }}">
                                            <button type="button" class="btn btn-sm {{ $art->status === 'archived' ? 'btn-restore' : 'btn-archive' }} trigger-modal"
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
                                    @if($interp->status === 'archived') <span class="position-absolute top-0 end-0 badge bg-secondary m-2">Archived</span> @endif
                                </div>
                                <div class="card-body d-flex flex-column">
                                    <small class="text-muted">For: {{ Str::limit($interp->challenge_name, 20) }}</small>
                                    <h6 class="fw-bold my-1">by {{ $interp->user_userName }}</h6>
                                    <div class="mb-2"><span class="badge bg-light text-dark border"><i class="bi bi-heart-fill text-danger"></i> {{ $interp->like_count }}</span></div>
                                    <form action="{{ route('admin.interpretations.archive') }}" method="POST" class="mt-auto trigger-form" id="iForm_{{ $interp->interpretation_id }}">
                                        @csrf <input type="hidden" name="interpretation_id" value="{{ $interp->interpretation_id }}">
                                        <button type="button" class="btn {{ $interp->status === 'archived' ? 'btn-restore' : 'btn-archive' }} trigger-modal"
                                                data-type="{{ $interp->status === 'archived' ? 'restore' : 'archive' }}" data-form="iForm_{{ $interp->interpretation_id }}">
                                            {{ $interp->status === 'archived' ? 'Restore' : 'Archive' }}
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12 text-center py-5 text-muted">No interpretations found.</div>
                    @endforelse
                </div>

                <div class="view-mode table-view table-responsive" style="display:none;">
                    <table class="table table-hover align-middle">
                        <thead class="table-light"><tr><th>Preview</th><th>For Challenge</th><th>Artist</th><th>Category</th><th>Stats</th><th>Action</th></tr></thead>
                        <tbody>
                            @foreach($interpretations as $interp)
                                <tr class="artwork-item"
                                    data-title="{{ strtolower($interp->challenge_name) }}"
                                    data-artist="{{ strtolower($interp->user_userName) }}"
                                    data-category="{{ strtolower($interp->category) }}">
                                    <td><img src="{{ asset('assets/uploads/' . $interp->art_filename) }}" width="50" height="50" class="rounded"></td>
                                    <td>{{ $interp->challenge_name }} @if($interp->status === 'archived') <span class="badge bg-secondary ms-1">Archived</span> @endif</td>
                                    <td>{{ $interp->user_userName }}</td>
                                    <td><span class="badge bg-secondary">{{ $interp->category }}</span></td>
                                    <td><i class="bi bi-heart-fill text-danger"></i> {{ $interp->like_count }}</td>
                                    <td>
                                        <form action="{{ route('admin.interpretations.archive') }}" method="POST" id="itForm_{{ $interp->interpretation_id }}">
                                            @csrf <input type="hidden" name="interpretation_id" value="{{ $interp->interpretation_id }}">
                                            <button type="button" class="btn btn-sm {{ $interp->status === 'archived' ? 'btn-restore' : 'btn-archive' }} trigger-modal"
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

        <div id="noResultsMsg" class="alert alert-light text-center mt-4" style="display:none;">No results match your filter.</div>
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

                // Filter in active tab only? No, filter everything so switching tabs works immediately
                document.querySelectorAll('.artwork-item').forEach(item => {
                    const title = item.dataset.title;
                    const artist = item.dataset.artist;
                    const category = item.dataset.category;

                    const matchesSearch = title.includes(term) || artist.includes(term);
                    const matchesCat = cat === '' || category === cat || category.includes(cat);

                    if(matchesSearch && matchesCat) {
                        item.style.display = ''; // Show (default display for col/tr)
                        visibleCount++;
                    } else {
                        item.style.display = 'none';
                    }
                });
            }
            searchInput.addEventListener('input', filterItems);
            categoryFilter.addEventListener('change', filterItems);

            // 3. Modal Logic
            document.querySelectorAll('.trigger-modal').forEach(btn => {
                btn.addEventListener('click', function() {
                    const type = this.dataset.type;
                    const formId = this.dataset.form;
                    const title = type === 'archive' ? 'Archive Content' : 'Restore Content';
                    const msg = type === 'archive' ? 'Hidden from public view.' : 'Visible to public again.';
                    const variant = type === 'archive' ? 'secondary' : 'success';

                    const container = document.getElementById('message-container');
                    container.innerHTML = `
                    <div class="modal fade" id="confirmModal" tabindex="-1">
                        <div class="modal-dialog modal-sm modal-dialog-centered">
                            <div class="modal-content rounded-4 shadow-lg">
                                <div class="modal-header border-0 pb-0"><h5 class="modal-title text-${variant}">${title}</h5></div>
                                <div class="modal-body pt-2 pb-4"><p class="text-muted">${msg}</p></div>
                                <div class="modal-footer border-0 pt-0 d-flex justify-content-between">
                                    <button type="button" class="btn btn-outline-secondary rounded-pill flex-grow-1 me-2" data-bs-dismiss="modal">Cancel</button>
                                    <button type="button" id="confirmActionBtn" class="btn btn-${variant} rounded-pill flex-grow-1">${type.charAt(0).toUpperCase() + type.slice(1)}</button>
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
