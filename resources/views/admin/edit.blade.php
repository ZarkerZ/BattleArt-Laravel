<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Admin Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.css" />
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

        /* --- NAVBAR --- */
        .custom-navbar {
            background-color: var(--primary-color) !important;
            backdrop-filter: none;
            -webkit-backdrop-filter: none;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        /* --- MAIN CARD --- */
        .edit-profile-card {
            background: #fff;
            border-radius: var(--card-radius);
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            border: 1px solid rgba(0,0,0,0.05);
            overflow: hidden;
            max-width: 850px;
            margin: 0 auto 3rem;
        }

        /* --- BANNER --- */
        .banner-wrapper {
            height: 200px;
            background-color: #e2e8f0;
            position: relative;
        }

        .banner-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .btn-change-banner {
            position: absolute;
            top: 15px; /* Top Right - Safe for mobile */
            right: 15px;
            background: rgba(255, 255, 255, 0.9);
            color: var(--text-dark);
            border: none;
            font-size: 0.85rem;
            font-weight: 600;
            padding: 0.5rem 1rem;
            border-radius: 50px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            transition: all 0.2s;
            cursor: pointer;
            z-index: 10;
        }

        .btn-change-banner:hover {
            background: #fff;
            transform: translateY(-1px);
            color: var(--primary-color);
        }

        /* --- AVATAR --- */
        .avatar-column {
            margin-top: -60px; /* Pulls avatar into banner */
            text-align: center;
            padding-bottom: 1rem;
        }

        .avatar-wrapper {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            border: 4px solid #fff;
            background-color: #f1f5f9;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            overflow: hidden;
            margin: 0 auto 0.5rem; /* Center horizontally */
            position: relative;
        }

        .avatar-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .btn-upload-avatar {
            font-size: 0.85rem;
            color: var(--primary-color);
            background-color: #f5f3ff;
            padding: 0.4rem 1rem;
            border-radius: 50px;
            font-weight: 600;
            border: 1px solid rgba(90, 75, 218, 0.1);
            cursor: pointer;
            transition: all 0.2s;
            display: inline-block;
        }

        .btn-upload-avatar:hover {
            background-color: #ede9fe;
            color: var(--primary-hover);
        }

        /* --- FORMS --- */
        .form-label {
            font-weight: 600;
            font-size: 0.9rem;
            color: var(--text-muted);
            margin-bottom: 0.4rem;
        }

        .form-control {
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            padding: 0.7rem 1rem;
            font-size: 0.95rem;
            color: var(--text-dark);
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(90, 75, 218, 0.1);
        }

        .form-control[disabled] {
            background-color: #f8f9fa;
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

        .btn-outline-custom {
            border: 1px solid #e2e8f0;
            color: var(--text-dark);
            background: #fff;
            border-radius: 50px;
            padding: 0.7rem 1.5rem;
            font-weight: 600;
            transition: all 0.2s ease;
            text-decoration: none;
            display: inline-block;
        }

        .btn-outline-custom:hover {
            background-color: #f8f9fa;
            border-color: #cbd5e1;
            color: var(--text-dark);
        }

        /* --- TOGGLES --- */
        .form-check-input {
            width: 3em;
            height: 1.5em;
            cursor: pointer;
        }

        .form-check-input:checked {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .privacy-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.75rem 0;
            border-bottom: 1px solid #f1f5f9;
        }
        .privacy-item:last-child { border-bottom: none; }
    </style>
</head>
<body>

    @include('partials.navbar')

    <div class="container my-5">

        <form id="profileSettingsForm" action="{{ route('admin.update') }}" method="post" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="croppedImage" id="croppedImage">
            <input type="hidden" name="croppedBanner" id="croppedBanner">

            <div class="edit-profile-card">
                <div class="banner-wrapper">
                    <img id="bannerPreview"
                         src="{{ $user->user_banner_pic ? asset('assets/uploads/' . $user->user_banner_pic) : asset('assets/images/night-road.png') }}"
                         class="banner-img"
                         onerror="this.src='{{ asset('assets/images/night-road.png') }}';">

                    <label for="bannerUpload" class="btn-change-banner">
                        <i class="fas fa-camera me-1"></i> Change Banner
                    </label>
                    <input type="file" id="bannerUpload" class="d-none" accept="image/*">
                </div>

                <div class="px-4 pb-4">
                    <div class="row">
                        <div class="col-12 col-md-auto avatar-column d-flex flex-column align-items-center">
                            <div class="avatar-wrapper">
                                <img id="avatarPreview"
                                     src="{{ $user->user_profile_pic ? asset('assets/uploads/' . $user->user_profile_pic) : asset('assets/images/blank-profile-picture.png') }}"
                                     class="avatar-img"
                                     onerror="this.src='{{ asset('assets/images/blank-profile-picture.png') }}';">
                            </div>
                            <label for="profileUpload" class="btn-upload-avatar">
                                Upload Photo
                            </label>
                            <input type="file" id="profileUpload" class="d-none" accept="image/*">
                        </div>

                        <div class="col-12 col-md pt-3 pt-md-4 ps-md-4">
                            <div class="row g-3">
                                <div class="col-12 col-lg-6">
                                    <label class="form-label">Username</label>
                                    <input type="text" name="fullName" value="{{ old('fullName', $user->user_userName) }}" class="form-control" required>
                                </div>
                                <div class="col-12 col-lg-6">
                                    <label class="form-label">Email Address</label>
                                    <input type="email" name="user_email" value="{{ old('user_email', $user->user_email) }}" class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 mb-4">
                        <label class="form-label">Admin Bio</label>
                        <textarea id="userBio" name="userBio" rows="4" class="form-control" placeholder="Tell everyone a little about yourself..." oninput="updateCharCount()">{{ old('userBio', $user->user_bio) }}</textarea>
                        <div class="text-end mt-1">
                            <small id="charCount" class="text-muted fw-semibold">0</small>
                            <small class="text-muted">/ 500 characters</small>
                        </div>
                        <div id="charLimitAlert" class="alert alert-danger mt-2 py-2 d-none">
                            <i class="fas fa-exclamation-circle me-1"></i> Character limit exceeded!
                        </div>
                    </div>

                    <hr class="my-4" style="color: #f1f5f9;">

                    <h5 class="fw-bold mb-3" style="color: var(--text-dark);">Display Settings</h5>
                    <div class="mb-4">
                        <div class="privacy-item">
                            <span class="text-dark">Show <strong>"Artworks"</strong> Tab</span>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="toggleArt" {{ $user->show_art ? 'checked' : '' }}>
                            </div>
                        </div>
                        <div class="privacy-item">
                            <span class="text-dark">Show <strong>"History"</strong> Tab</span>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="toggleHistory" {{ $user->show_history ? 'checked' : '' }}>
                            </div>
                        </div>
                        <div class="privacy-item">
                            <span class="text-dark">Show <strong>"Comments"</strong> Tab</span>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="toggleComments" {{ $user->show_comments ? 'checked' : '' }}>
                            </div>
                        </div>
                    </div>

                    @if(session('success'))
                        <div class="alert alert-success border-0 shadow-sm">
                            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger border-0 shadow-sm">
                            <ul class="mb-0 ps-3">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="d-flex justify-content-end gap-3 mt-4">
                        <a href="{{ route('admin.profile') }}" class="btn btn-outline-custom px-4">Cancel</a>
                        <button type="button" id="submitBtn" class="btn btn-primary-custom px-4" onclick="document.getElementById('profileSettingsForm').submit()">
                            <i class="fas fa-save me-2"></i> Save Changes
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <div class="modal fade" id="cropImageModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4 border-0 shadow-lg">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold">Crop Profile Picture</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="img-container" style="max-height: 400px;">
                        <img id="imageToCrop" src="" style="max-width: 100%; display: block;">
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-outline-secondary rounded-pill" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" id="cropButton" class="btn btn-primary-custom">Crop & Apply</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="cropBannerModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content rounded-4 border-0 shadow-lg">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold">Crop Banner</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="img-container" style="max-height: 400px;">
                        <img id="bannerToCrop" src="" style="max-width: 100%; display: block;">
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-outline-secondary rounded-pill" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" id="cropBannerButton" class="btn btn-primary-custom">Crop & Apply</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.js"></script>

    <script>
        // --- Character Counter ---
        function updateCharCount() {
            const bioText = document.getElementById('userBio').value;
            const countElement = document.getElementById('charCount');
            const alertElement = document.getElementById('charLimitAlert');
            const submitBtn = document.getElementById('submitBtn');
            const charCount = bioText.length;

            countElement.textContent = charCount;

            if (charCount > 500) {
                countElement.classList.add('text-danger');
                alertElement.classList.remove('d-none');
                submitBtn.disabled = true;
                submitBtn.style.opacity = '0.5';
            } else {
                countElement.classList.remove('text-danger');
                alertElement.classList.add('d-none');
                submitBtn.disabled = false;
                submitBtn.style.opacity = '1';
            }
        }
        document.addEventListener('DOMContentLoaded', updateCharCount);

        // --- Cropper Logic ---
        let profileCropper;
        let bannerCropper;

        const profileModalEl = document.getElementById('cropImageModal');
        const profileModal = new bootstrap.Modal(profileModalEl);
        const bannerModalEl = document.getElementById('cropBannerModal');
        const bannerModal = new bootstrap.Modal(bannerModalEl);

        // Handle Profile Image Selection
        document.getElementById('profileUpload').addEventListener('change', function(e) {
            handleFileSelect(e, document.getElementById('imageToCrop'), profileModal, (img) => {
                if (profileCropper) profileCropper.destroy();

                profileModalEl.addEventListener('shown.bs.modal', function () {
                    profileCropper = new Cropper(img, {
                        aspectRatio: 1,
                        viewMode: 1,
                        dragMode: 'move',
                        autoCropArea: 0.8
                    });
                }, { once: true });
            });
        });

        // Handle Banner Image Selection
        document.getElementById('bannerUpload').addEventListener('change', function(e) {
            handleFileSelect(e, document.getElementById('bannerToCrop'), bannerModal, (img) => {
                if (bannerCropper) bannerCropper.destroy();

                bannerModalEl.addEventListener('shown.bs.modal', function () {
                    bannerCropper = new Cropper(img, {
                        aspectRatio: 16 / 5,
                        viewMode: 1,
                        dragMode: 'move',
                        autoCropArea: 1
                    });
                }, { once: true });
            });
        });

        // Crop & Apply Profile
        document.getElementById('cropButton').addEventListener('click', () => {
            if (!profileCropper) return;
            const canvas = profileCropper.getCroppedCanvas({ width: 300, height: 300 });
            document.getElementById('avatarPreview').src = canvas.toDataURL('image/png');
            document.getElementById('croppedImage').value = canvas.toDataURL('image/png');
            profileModal.hide();
            profileCropper.destroy();
            profileCropper = null;
            document.getElementById('profileUpload').value = '';
        });

        // Crop & Apply Banner
        document.getElementById('cropBannerButton').addEventListener('click', () => {
            if (!bannerCropper) return;
            const canvas = bannerCropper.getCroppedCanvas({ width: 850, height: 200 });
            document.getElementById('bannerPreview').src = canvas.toDataURL('image/png');
            document.getElementById('croppedBanner').value = canvas.toDataURL('image/png');
            bannerModal.hide();
            bannerCropper.destroy();
            bannerCropper = null;
            document.getElementById('bannerUpload').value = '';
        });

        function handleFileSelect(event, imgElement, modalInstance, callback) {
            const file = event.target.files[0];
            if (!file) return;

            if (file.size > 5 * 1024 * 1024) {
                alert("File is too large (max 5MB).");
                return;
            }

            const reader = new FileReader();
            reader.onload = (e) => {
                imgElement.src = e.target.result;
                modalInstance.show();
                callback(imgElement);
            };
            reader.readAsDataURL(file);
        }
    </script>
</body>
</html>
