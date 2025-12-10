<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create New Challenge</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
            max-width: 850px;
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
                <h1 class="h3 fw-bold text-dark mb-2">Start a New Creative Challenge</h1>
                <p class="text-muted">Share your art and inspire the community.</p>
            </div>

            @if ($errors->any())
                <div class="alert alert-danger shadow-sm border-0 mb-4">
                    <ul class="mb-0 small ps-3">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('challenges.store') }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="row g-4 mb-4">
                    <div class="col-md-6">
                        <label class="form-label">Challenge Name</label>
                        <input type="text" name="challengeName" class="form-control" required placeholder="e.g., Cyberpunk Cityscape">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Category</label>
                        <select name="category" class="form-select" required>
                            <option value="" disabled selected>Select a category...</option>
                            <option value="digital_painting">Digital Painting</option>
                            <option value="sci-fi">Sci-Fi</option>
                            <option value="fantasy">Fantasy</option>
                            <option value="abstract">Abstract</option>
                            <option value="portraits">Portraits</option>
                            <option value="landscapes">Landscapes</option>
                        </select>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label">Upload Original Art</label>
                    <div id="dropZone" class="drop-zone p-5 text-center">
                        <i class="fas fa-cloud-upload-alt fs-1 mb-3"></i>
                        <h6 class="fw-bold mb-1">Click to upload image</h6>
                        <p class="text-muted small mb-0">Drag & Drop your file here (Max 5MB)</p>
                        <input type="file" name="artFile" id="artFile" class="d-none" required accept="image/*" onchange="handleFileSelect(this)">
                    </div>

                    <div id="previewContainer" class="mt-3 d-none p-3 border rounded-3 bg-light position-relative">
                        <div class="d-flex align-items-center">
                            <img id="previewImg" class="rounded border shadow-sm me-3" style="width: 80px; height: 80px; object-fit: cover;">
                            <div>
                                <p class="mb-0 fw-bold text-success small"><i class="fas fa-check-circle me-1"></i> Image selected successfully</p>
                                <p id="fileNameDisplay" class="text-muted small mb-0 text-truncate" style="max-width: 250px;"></p>
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-danger ms-auto rounded-pill" onclick="resetFileSelection()">
                                <i class="fas fa-times"></i> Remove
                            </button>
                        </div>
                    </div>
                </div>

                <div class="mb-5">
                    <label class="form-label">Description</label>
                    <textarea name="challengeDescription" rows="4" class="form-control" required placeholder="Describe the theme, rules, or inspiration for this challenge..."></textarea>
                </div>

                <div class="d-flex justify-content-between pt-3 border-top">
                    <a href="{{ route('challenges.index') }}" class="btn btn-secondary-custom px-4">Cancel</a>
                    <button type="submit" class="btn btn-primary-custom px-4">
                        <i class="fas fa-rocket me-2"></i> Submit Challenge
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
            const previewContainer = document.getElementById('previewContainer');
            const fileNameDisplay = document.getElementById('fileNameDisplay');
            const imagePreview = document.getElementById('previewImg');
            const dropZone = document.getElementById('dropZone');

            if (file) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    imagePreview.src = e.target.result;
                    previewContainer.classList.remove('d-none');
                    dropZone.classList.add('d-none');
                };
                reader.readAsDataURL(file);
                fileNameDisplay.textContent = file.name;
            }
        }

        function resetFileSelection() {
            document.getElementById('artFile').value = '';
            document.getElementById('previewContainer').classList.add('d-none');
            document.getElementById('dropZone').classList.remove('d-none');
        }
    </script>
</body>
</html>
