<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Challenge</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <link href="{{ asset('assets/css/navbar.css') }}" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/images/doro.ico') }}">

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

        /* --- FORM CARD --- */
        .form-card {
            background-color: #ffffff;
            border-radius: var(--card-radius);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            border: 1px solid rgba(0,0,0,0.05);
            padding: 2.5rem;
            max-width: 800px;
            margin: 0 auto;
        }

        /* --- FORM ELEMENTS --- */
        .form-label {
            font-weight: 600;
            color: var(--text-dark);
            font-size: 0.9rem;
            margin-bottom: 0.5rem;
        }

        .form-control, .form-select {
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            padding: 0.7rem 1rem;
            font-size: 0.95rem;
            color: var(--text-dark);
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(90, 75, 218, 0.1);
        }

        /* --- DROP ZONE --- */
        .drop-zone {
            border: 2px dashed #cbd5e1;
            background-color: #f8f9fa;
            border-radius: 12px;
            transition: all 0.3s ease;
            cursor: pointer;
            position: relative;
            overflow: hidden;
        }

        .drop-zone:hover, .drop-zone.drag-over {
            border-color: var(--primary-color);
            background-color: #f5f3ff; /* Very light purple tint */
            color: var(--primary-color);
        }

        .drop-zone i {
            transition: transform 0.3s ease;
            color: #94a3b8;
        }

        .drop-zone:hover i {
            transform: scale(1.1);
            color: var(--primary-color);
        }

        /* --- BUTTONS --- */
        .btn-primary-custom {
            background-color: var(--primary-color);
            color: white;
            border: none;
            border-radius: 50px;
            padding: 0.7rem 2rem;
            font-weight: 600;
            box-shadow: 0 4px 15px rgba(90, 75, 218, 0.3);
            transition: all 0.3s ease;
        }

        .btn-primary-custom:hover {
            background-color: var(--primary-hover);
            transform: translateY(-2px);
            color: white;
        }

        .btn-secondary-custom {
            background-color: #f1f5f9;
            color: var(--text-dark);
            border: none;
            border-radius: 50px;
            padding: 0.7rem 2rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-secondary-custom:hover {
            background-color: #e2e8f0;
            color: var(--text-dark);
        }

        /* --- ALERTS --- */
        .alert-danger {
            background-color: #fef2f2;
            border-color: #fee2e2;
            color: #dc2626;
            border-radius: 12px;
        }
    </style>
</head>

<body>
    @include('partials.navbar')

    <div class="container my-5">
        <div class="form-card">
            <div class="text-center mb-5">
                <h1 class="h3 fw-bold text-dark mb-2">Edit Your Challenge</h1>
                <p class="text-muted">Update details for: <strong style="color: var(--primary-color);">{{ $challenge->challenge_name }}</strong></p>
            </div>

            @if ($errors->any())
                <div class="alert alert-danger mb-4 shadow-sm border-0">
                    <ul class="mb-0 small ps-3">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('challenges.update', $challenge->challenge_id) }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="row g-4 mb-4">
                    <div class="col-md-6">
                        <label for="challengeName" class="form-label">Challenge Name</label>
                        <input type="text" name="challengeName" class="form-control" value="{{ old('challengeName', $challenge->challenge_name) }}" required>
                    </div>

                    <div class="col-md-6">
                        <label for="category" class="form-label">Category</label>
                        <select name="category" class="form-select" required>
                            @foreach(['digital_painting' => 'Digital Painting', 'sci-fi' => 'Sci-Fi', 'fantasy' => 'Fantasy', 'abstract' => 'Abstract', 'portraits' => 'Portraits', 'landscapes' => 'Landscapes'] as $val => $label)
                                <option value="{{ $val }}" {{ $challenge->category == $val ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label">Update Original Art <span class="text-muted fw-normal small">(Optional)</span></label>

                    <div class="row g-3 align-items-center mb-3">
                        <div class="col-auto">
                            <img src="{{ asset('assets/uploads/' . $challenge->original_art_filename) }}"
                                 class="img-thumbnail rounded-3 shadow-sm"
                                 style="width: 100px; height: 100px; object-fit: cover;">
                        </div>
                        <div class="col">
                            <small class="text-muted d-block">Current Image</small>
                        </div>
                    </div>

                    <div id="dropZone" class="drop-zone p-5 text-center">
                        <i class="fas fa-cloud-upload-alt fs-1 mb-3"></i>
                        <h6 class="fw-bold mb-1">Click to upload new image</h6>
                        <p class="text-muted small mb-0">Drag & Drop to replace current art</p>
                        <input type="file" id="artFile" name="artFile" accept="image/*" class="d-none" onchange="handleFileSelect(this)">
                    </div>

                    <div id="filePreviewContainer" class="mt-3 d-none p-3 border rounded-3 bg-light position-relative">
                        <div class="d-flex align-items-center">
                            <img id="imagePreview" class="rounded border shadow-sm me-3" style="width: 80px; height: 80px; object-fit: cover;">
                            <div>
                                <p class="mb-0 fw-bold text-success small"><i class="fas fa-check-circle me-1"></i> New image selected</p>
                                <p id="fileNameDisplay" class="text-muted small mb-0 text-truncate" style="max-width: 250px;"></p>
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-danger ms-auto rounded-pill" onclick="resetFileSelection()">
                                <i class="fas fa-times"></i> Remove
                            </button>
                        </div>
                    </div>
                </div>

                <div class="mb-5">
                    <label for="challengeDescription" class="form-label">Description</label>
                    <textarea name="challengeDescription" rows="5" class="form-control" placeholder="Describe the challenge rules or inspiration..." required>{{ old('challengeDescription', $challenge->challenge_description) }}</textarea>
                </div>

                <div class="d-flex justify-content-between pt-3 border-top">
                    <a href="{{ route('challenges.show', $challenge->challenge_id) }}" class="btn btn-secondary-custom">
                        Cancel
                    </a>
                    <button type="submit" class="btn btn-primary-custom">
                        <i class="fas fa-save me-2"></i> Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        window.onload = () => {
            setupDragDrop();
        };

        function setupDragDrop() {
            const dropZone = document.getElementById('dropZone');
            const artFile = document.getElementById('artFile');
            if (!dropZone || !artFile) return;

            dropZone.addEventListener('click', () => artFile.click());

            ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                dropZone.addEventListener(eventName, e => {
                    e.preventDefault();
                    e.stopPropagation();
                }, false);
                document.body.addEventListener(eventName, e => {
                    e.preventDefault();
                    e.stopPropagation();
                }, false);
            });

            ['dragenter', 'dragover'].forEach(eventName => {
                dropZone.addEventListener(eventName, () => dropZone.classList.add('drag-over'), false);
            });

            ['dragleave', 'drop'].forEach(eventName => {
                dropZone.addEventListener(eventName, () => dropZone.classList.remove('drag-over'), false);
            });

            dropZone.addEventListener('drop', (e) => {
                const files = e.dataTransfer.files;
                if (files.length > 0) {
                    artFile.files = files;
                    handleFileSelect(artFile);
                }
            }, false);
        }

        function handleFileSelect(input) {
            const file = input.files[0];
            const previewContainer = document.getElementById('filePreviewContainer');
            const fileNameDisplay = document.getElementById('fileNameDisplay');
            const imagePreview = document.getElementById('imagePreview');
            const dropZone = document.getElementById('dropZone');

            if (file) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    imagePreview.src = e.target.result;
                    previewContainer.classList.remove('d-none');
                    dropZone.classList.add('d-none'); // Hide dropzone to show preview clearly
                };
                reader.readAsDataURL(file);
                fileNameDisplay.textContent = file.name;
            }
        }

        function resetFileSelection() {
            document.getElementById('artFile').value = '';
            document.getElementById('filePreviewContainer').classList.add('d-none');
            document.getElementById('dropZone').classList.remove('d-none');
        }
    </script>
</body>
</html>
