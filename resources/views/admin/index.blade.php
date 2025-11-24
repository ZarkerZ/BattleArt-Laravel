<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Artworks</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="{{ asset('assets/css/navbar.css') }}" rel="stylesheet">
</head>
<body style="background-color: #a6e7ff;">
    @include('partials.navbar')

    <div class="container mt-5 bg-white p-4 rounded shadow">
        <h2 class="text-center mb-4" style="color: #8c76ec;">Manage Artworks</h2>

        <div class="row mb-4 bg-light p-3 rounded">
            <div class="col-md-6"><input type="text" id="searchInput" class="form-control" placeholder="Search artwork or artist..."></div>
            <div class="col-md-6">
                <select id="categoryFilter" class="form-select">
                    <option value="">All Categories</option>
                    <option value="Digital Painting">Digital Painting</option>
                    <option value="Sci-Fi">Sci-Fi</option>
                    <option value="Fantasy">Fantasy</option>
                    </select>
            </div>
        </div>

        <div class="row g-4" id="artGrid">
            @foreach($artworks as $art)
                <div class="col-md-4 art-item" data-title="{{ strtolower($art->challenge_name) }}" data-artist="{{ strtolower($art->user_userName) }}" data-category="{{ strtolower($art->category) }}">
                    <div class="card h-100 border-0 shadow-sm">
                        <img src="{{ asset('assets/uploads/'.$art->original_art_filename) }}" class="card-img-top" style="height:200px; object-fit:cover;">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">{{ $art->challenge_name }}</h5>
                            <p class="text-muted small">{{ $art->user_userName }}</p>
                            <div class="mb-3">
                                <span class="badge bg-light text-dark border"><i class="bi bi-heart-fill text-danger"></i> {{ $art->like_count }}</span>
                                <span class="badge bg-light text-dark border"><i class="bi bi-palette-fill text-primary"></i> {{ $art->interpretation_count }}</span>
                            </div>
                            <form action="{{ route('admin.art.delete') }}" method="POST" class="mt-auto" onsubmit="return confirm('Permanently delete this artwork?');">
                                @csrf
                                <input type="hidden" name="art_id" value="{{ $art->challenge_id }}">
                                <button class="btn btn-danger w-100 rounded-pill">Delete</button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const searchInput = document.getElementById('searchInput');
        const categoryFilter = document.getElementById('categoryFilter');
        const items = document.querySelectorAll('.art-item');

        function filter() {
            const term = searchInput.value.toLowerCase();
            const cat = categoryFilter.value.toLowerCase();

            items.forEach(item => {
                const title = item.dataset.title;
                const artist = item.dataset.artist;
                const itemCat = item.dataset.category;
                
                const matchesSearch = title.includes(term) || artist.includes(term);
                const matchesCat = cat === '' || itemCat === cat;

                item.style.display = matchesSearch && matchesCat ? '' : 'none';
            });
        }

        searchInput.addEventListener('input', filter);
        categoryFilter.addEventListener('change', filter);
    </script>
</body>
</html>