<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Challenge</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/images/doro.ico') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/navbar.css') }}">
    <style>
        :root {
            --primary-bg: #8c76ec;
            --secondary-bg: #a6e7ff;
            --light-purple: #c3b4fc;
            --text-dark: #333;
        }

        body {
            background-image: linear-gradient(to bottom, var(--secondary-bg), var(--light-purple));
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            padding-top: 20px;
        }

        .form-card {
            background-color: #fff;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            color: var(--text-dark);
        }

        .drop-zone {
            border: 3px dashed var(--light-purple);
            background-color: #f7f7ff;
            transition: all 0.2s;
            cursor: pointer;
        }

        .drop-zone.drag-over {
            border-color: var(--primary-bg);
            background-color: #e6e0fc;
        }
    </style>
</head>

<body class="font-sans">
    @include('partials.navbar')

    <div class="container my-5">
        <div class="form-card col-lg-8 mx-auto p-4 p-md-5">
            <h1 class="h3 fw-bold text-dark mb-4 text-center">Edit Your Challenge</h1>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('challenges.update', $challenge->challenge_id) }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label for="challengeName" class="form-label fw-semibold">Challenge Name</label>
                    <input type="text" name="challengeName" class="form-control" value="{{ old('challengeName', $challenge->challenge_name) }}" required>
                </div>

                <div class="mb-3">
                    <label for="category" class="form-label fw-semibold">Category</label>
                    <select name="category" class="form-select" required>
                        @foreach(['digital_painting' => 'Digital Painting', 'sci-fi' => 'Sci-Fi', 'fantasy' => 'Fantasy', 'abstract' => 'Abstract', 'portraits' => 'Portraits', 'landscapes' => 'Landscapes'] as $val => $label)
                            <option value="{{ $val }}" {{ $challenge->category == $val ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Update Original Art (Optional)</label>

                    <div class="mb-3 text-center">
                        <p class="small text-muted mb-2">Current Image:</p>
                        <img src="{{ asset('assets/uploads/' . $challenge->original_art_filename) }}" class="img-thumbnail" style="max-width: 200px;">
                    </div>

                    <div id="dropZone" class="drop-zone p-4 text-center rounded-3">
                        <i class="fas fa-upload fs-2 text-muted mb-2"></i>
                        <p class="text-muted small">Drag & Drop to replace, or click to select a new file.</p>
                        <input type="file" id="artFile" name="artFile" accept="image/*" class="d-none" onchange="handleFileSelect(this)">
                    </div>

                    <div id="filePreviewContainer" class="mt-3 d-none text-center">
                        <p id="fileNameDisplay" class="small text-muted mb-2"></p>
                        <img id="imagePreview" class="img-thumbnail" style="max-height: 200px;" alt="New Art Preview">
                    </div>
                </div>

                <div class="mb-4">
                    <label for="challengeDescription" class="form-label fw-semibold">Description</label>
                    <textarea name="challengeDescription" rows="4" class="form-control" required>{{ old('challengeDescription', $challenge->challenge_description) }}</textarea>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('challenges.show', $challenge->challenge_id) }}" class="btn btn-secondary rounded-pill px-4">Cancel</a>
                    <button type="submit" class="btn btn-primary rounded-pill px-4" style="background-color: var(--primary-bg); border: none;">Save Changes</button>
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

            if (file) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    imagePreview.src = e.target.result;
                    previewContainer.classList.remove('d-none');
                };
                reader.readAsDataURL(file);
                fileNameDisplay.textContent = `New file selected: ${file.name}`;
                document.getElementById('dropZone').classList.add('d-none');
            } else {
                previewContainer.classList.add('d-none');
                document.getElementById('dropZone').classList.remove('d-none');
            }
        }
    </script>
</body>
</html>
