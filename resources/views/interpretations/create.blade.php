<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Submit Interpretation</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/images/doro.ico') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/navbar.css') }}">
    <style>
        :root { --primary-bg: #8c76ec; --secondary-bg: #a6e7ff; --light-purple: #c3b4fc; }
        body { background-image: linear-gradient(to bottom, var(--secondary-bg), var(--light-purple)); min-height: 100vh; padding-top: 20px; }
        .form-card { background-color: #fff; border-radius: 20px; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15); color: #333; }
        .drop-zone { border: 3px dashed var(--light-purple); background-color: #f7f7ff; transition: all 0.2s; cursor: pointer; }
        .drop-zone:hover { border-color: var(--primary-bg); background-color: #e6e0fc; }
    </style>
</head>
<body>
    @include('partials.navbar')

    <div class="container my-5">
        <div class="form-card col-lg-8 mx-auto p-4 p-md-5">
            <div class="text-center mb-4">
                <h1 class="h3 fw-bold text-dark">Submit Your Interpretation</h1>
                <p class="text-muted">For the challenge: <strong style="color: var(--primary-bg);">{{ $challenge->challenge_name }}</strong></p>
            </div>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('interpretations.store') }}" method="post" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="challenge_id" value="{{ $challenge->challenge_id }}">

                <div class="mb-4">
                    <label class="form-label fw-bold">Upload Your Art</label>
                    <div class="drop-zone p-5 text-center rounded-3" onclick="document.getElementById('artFile').click()">
                        <i class="fas fa-upload fs-2 text-muted mb-2"></i>
                        <p class="text-muted small mb-0">Click to select your image</p>
                        <input type="file" id="artFile" name="artFile" class="d-none" accept="image/*" onchange="previewImage(this)">
                    </div>
                    <div id="previewContainer" class="mt-3 text-center d-none">
                        <img id="imgPreview" class="img-thumbnail" style="max-height: 200px;">
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-bold">Description (Optional)</label>
                    <textarea name="description" class="form-control" rows="4" placeholder="Describe your creative choices..."></textarea>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('challenges.show', $challenge->challenge_id) }}" class="btn btn-light rounded-pill px-4">Cancel</a>
                    <button type="submit" class="btn btn-primary rounded-pill px-4" style="background-color: var(--primary-bg); border: none;">Submit</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function previewImage(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('imgPreview').src = e.target.result;
                    document.getElementById('previewContainer').classList.remove('d-none');
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</body>
</html>
