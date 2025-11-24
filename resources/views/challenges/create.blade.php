<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create New Challenge</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="{{ asset('assets/css/navbar.css') }}" rel="stylesheet">
    <style>
        :root { --primary-bg: #8c76ec; --secondary-bg: #a6e7ff; --light-purple: #c3b4fc; }
        body { background-image: linear-gradient(to bottom, var(--secondary-bg), var(--light-purple)); min-height: 100vh; padding-top: 30px; font-family: 'Inter', sans-serif; }
        .drop-zone { border: 3px dashed var(--light-purple); background-color: #f7f7ff; cursor: pointer; }
    </style>
</head>
<body>
    @include('partials.navbar')

    <div class="container my-5">
        <h1 class="fw-bold text-white mb-5 text-center">Start a New Creative Challenge</h1>

        <div class="card shadow-lg mx-auto p-4 p-md-5" style="max-width: 50rem;">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>@foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach</ul>
                </div>
            @endif

            <form action="{{ route('challenges.store') }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="mb-4">
                    <label class="form-label fs-5 fw-semibold">Challenge Name</label>
                    <input type="text" name="challengeName" class="form-control form-control-lg" required>
                </div>

                <div class="mb-4">
                    <label class="form-label fs-5 fw-semibold">Category</label>
                    <select name="category" class="form-select form-select-lg" required>
                        <option value="digital_painting">Digital Painting</option>
                        <option value="sci-fi">Sci-Fi</option>
                        <option value="fantasy">Fantasy</option>
                        <option value="abstract">Abstract</option>
                        <option value="portraits">Portraits</option>
                        <option value="landscapes">Landscapes</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="form-label fs-5 fw-semibold">Upload Original Art</label>
                    <div id="dropZone" class="drop-zone p-5 text-center rounded-3">
                        <i class="fas fa-upload fs-1 text-secondary mb-3"></i>
                        <p class="text-body-secondary fw-medium">Drag & Drop your image here, or click to select file.</p>
                        <input type="file" name="artFile" id="artFile" class="d-none" required onchange="previewFile(this)">
                    </div>
                    <img id="previewImg" class="img-fluid mt-3 rounded d-none" style="max-height: 256px;">
                </div>

                <div class="mb-5">
                    <label class="form-label fs-5 fw-semibold">Description</label>
                    <textarea name="challengeDescription" rows="4" class="form-control form-control-lg" required></textarea>
                </div>

                <div class="d-flex justify-content-end gap-3">
                    <a href="{{ route('challenges.index') }}" class="btn btn-secondary rounded-pill py-2 px-4 fw-bold">Cancel</a>
                    <button type="submit" class="btn btn-primary rounded-pill py-2 px-4 fw-bold" style="background-color: var(--primary-bg);">Submit Challenge</button>
                </div>
            </form>
        </div>
    </div>
    <script>
        document.getElementById('dropZone').addEventListener('click', () => document.getElementById('artFile').click());
        function previewFile(input) {
            const file = input.files[0];
            if(file){
                const reader = new FileReader();
                reader.onload = e => {
                    const img = document.getElementById('previewImg');
                    img.src = e.target.result;
                    img.classList.remove('d-none');
                }
                reader.readAsDataURL(file);
            }
        }
    </script>
</body>
</html>
