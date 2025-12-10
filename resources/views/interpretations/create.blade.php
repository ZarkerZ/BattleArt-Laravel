<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Submit Interpretation</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/images/doro.ico') }}">
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

        /* --- FORM CARD --- */
        .form-card {
            background-color: #fff;
            border-radius: var(--card-radius);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            border: 1px solid rgba(0,0,0,0.05);
            padding: 2.5rem;
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

        .drop-zone:hover {
            border-color: var(--primary-color);
            background-color: #f5f3ff; /* Very light purple */
            color: var(--primary-color);
        }

        .drop-zone:hover .upload-icon {
            transform: scale(1.1);
            color: var(--primary-color);
        }

        .upload-icon {
            transition: transform 0.3s ease;
            color: #94a3b8;
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

        .form-control {
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            padding: 0.8rem 1rem;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(90, 75, 218, 0.1);
        }
    </style>
</head>
<body>
    @include('partials.navbar')

    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="form-card">
                    <div class="text-center mb-5">
                        <h1 class="h3 fw-bold text-dark mb-2">Submit Your Interpretation</h1>
                        <p class="text-muted">You are submitting art for the challenge: <br>
                            <strong style="color: var(--primary-color); font-size: 1.1rem;">{{ $challenge->challenge_name }}</strong>
                        </p>
                    </div>

                    @if ($errors->any())
                        <div class="alert alert-danger shadow-sm border-0 rounded-3 mb-4">
                            <ul class="mb-0 small">
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
                            <label class="form-label fw-bold text-dark">Upload Your Art</label>
                            <div class="drop-zone p-5 text-center" onclick="document.getElementById('artFile').click()">
                                <i class="fas fa-cloud-upload-alt fs-1 mb-3 upload-icon"></i>
                                <h5 class="fw-bold mb-1">Click to upload</h5>
                                <p class="text-muted small mb-0">SVG, PNG, JPG or GIF (Max. 800x400px)</p>
                                <input type="file" id="artFile" name="artFile" class="d-none" accept="image/*" onchange="previewImage(this)">
                            </div>

                            <div id="previewContainer" class="mt-3 text-center d-none">
                                <div class="position-relative d-inline-block">
                                    <img id="imgPreview" class="img-fluid rounded shadow-sm border" style="max-height: 250px;">
                                    <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 m-2 rounded-circle" onclick="removeImage(event)" title="Remove Image">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                                <p class="small text-success mt-2"><i class="fas fa-check-circle me-1"></i> Image selected successfully</p>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold text-dark">Description <span class="text-muted fw-normal small">(Optional)</span></label>
                            <textarea name="description" class="form-control" rows="4" placeholder="Tell us about your creative process, style choices, or inspiration..."></textarea>
                        </div>

                        <div class="d-flex justify-content-between align-items-center pt-3 border-top mt-4">
                            <a href="{{ route('challenges.show', $challenge->challenge_id) }}" class="btn btn-secondary-custom">
                                Cancel
                            </a>
                            <button type="submit" class="btn btn-primary-custom">
                                <i class="fas fa-paper-plane me-2"></i> Submit Interpretation
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function previewImage(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('imgPreview').src = e.target.result;
                    document.getElementById('previewContainer').classList.remove('d-none');
                    // Hide the dropzone content text visually if needed, or keep it as "Change image" area
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        function removeImage(event) {
            event.stopPropagation(); // Prevent triggering the file input click
            event.preventDefault();
            document.getElementById('artFile').value = "";
            document.getElementById('previewContainer').classList.add('d-none');
            document.getElementById('imgPreview').src = "";
        }
    </script>
</body>
</html>
