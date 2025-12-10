<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Settings - BattleArt</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800&display=swap" rel="stylesheet">
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

        /* --- SETTINGS CONTAINER CARD --- */
        .settings-card {
            background-color: #ffffff;
            border-radius: var(--card-radius);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            border: 1px solid rgba(0,0,0,0.05);
            padding: 2.5rem;
            max-width: 850px;
            margin: 2rem auto;
        }

        /* --- HEADERS --- */
        .settings-header h1 {
            color: var(--text-dark);
            font-size: 1.75rem;
            font-weight: 800;
            letter-spacing: -0.5px;
        }

        .section-title {
            color: var(--text-dark);
            font-weight: 700;
            font-size: 1.1rem;
            padding-bottom: 0.75rem;
            border-bottom: 1px solid #f1f5f9;
            margin-bottom: 1.5rem;
        }

        /* --- FORMS --- */
        .form-label {
            font-weight: 600;
            color: var(--text-dark);
            font-size: 0.9rem;
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

        .form-control[readonly] {
            background-color: #f8f9fa;
            color: var(--text-muted);
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

        .btn-info-custom {
            background-color: #e0f2fe; /* Light Blue */
            color: #0284c7; /* Dark Blue Text */
            border: none;
            border-radius: 12px; /* Less rounded for full width buttons */
            font-weight: 600;
            transition: all 0.2s;
        }

        .btn-info-custom:hover {
            background-color: #bae6fd;
            color: #0369a1;
        }

        .btn-danger-custom {
            background-color: #fef2f2; /* Light Red */
            color: #dc2626; /* Dark Red Text */
            border: 1px solid #fee2e2;
            border-radius: 12px;
            font-weight: 600;
            transition: all 0.2s;
        }

        .btn-danger-custom:hover {
            background-color: #dc2626;
            color: white;
            border-color: #dc2626;
        }

        /* --- TOGGLE SWITCH --- */
        .form-check-input:checked {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        /* --- ALERTS --- */
        .alert {
            border-radius: 12px;
            border: none;
            box-shadow: 0 4px 6px rgba(0,0,0,0.02);
        }
    </style>
</head>
<body>

    @include('partials.navbar')

    <div class="container mb-5">
        <div class="settings-card">
            <header class="mb-5 settings-header text-center">
                <h1>User Settings</h1>
                <p class="text-muted small">Manage your account preferences and security.</p>
                <span class="badge bg-light text-muted border rounded-pill px-3">User ID: {{ $user->user_id }}</span>
            </header>

            @if (session('message'))
                <div class="alert alert-{{ session('message')['type'] }} alert-dismissible fade show mb-4" role="alert">
                    <i class="fas fa-info-circle me-2"></i> {{ session('message')['text'] }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                    <ul class="mb-0 small">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="mb-5">
                <h2 class="section-title"><i class="fas fa-user-circle me-2 text-muted"></i>Personal Information</h2>
                <form action="{{ route('settings.updateProfile') }}" method="post">
                    @csrf
                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <label for="first-name" class="form-label">First Name</label>
                            <input type="text" id="first-name" name="firstName" class="form-control" value="{{ old('firstName', $user->user_firstName) }}" required>
                        </div>
                        <div class="col-md-4">
                            <label for="middle-name" class="form-label">Middle Name</label>
                            <input type="text" id="middle-name" name="middleName" class="form-control" value="{{ old('middleName', $user->user_middleName) }}">
                        </div>
                        <div class="col-md-4">
                            <label for="last-name" class="form-label">Last Name</label>
                            <input type="text" id="last-name" name="lastName" class="form-control" value="{{ old('lastName', $user->user_lastName) }}" required>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label for="birthday" class="form-label">Date of Birth</label>
                        <input type="date" id="birthday" name="dob" class="form-control" value="{{ old('dob', $user->user_dob) }}" readonly>
                        <small class="text-muted ms-1"><i class="fas fa-lock fa-xs me-1"></i> Contact admin to change DOB.</small>
                    </div>
                    <div class="text-end">
                        <button type="submit" class="btn btn-primary-custom">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>

            <div class="mb-5">
                <h2 class="section-title"><i class="fas fa-shield-alt me-2 text-muted"></i>Account Security</h2>
                <form action="{{ route('settings.updatePassword') }}" method="post">
                    @csrf
                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <label for="current-password" class="form-label">Current Password</label>
                            <input type="password" id="current-password" name="currentPassword" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label for="new-password" class="form-label">New Password</label>
                            <input type="password" id="new-password" name="newPassword" class="form-control" minlength="6" required>
                        </div>
                        <div class="col-md-4">
                            <label for="confirm-password" class="form-label">Confirm Password</label>
                            <input type="password" id="confirm-password" name="confirmPassword" class="form-control" required>
                        </div>
                    </div>
                    <div class="text-end">
                        <button type="submit" class="btn btn-primary-custom">
                            Update Password
                        </button>
                    </div>
                </form>
            </div>

            <div class="mb-5">
                <h2 class="section-title"><i class="fas fa-bell me-2 text-muted"></i>Preferences</h2>
                <div class="d-flex justify-content-between align-items-center py-3">
                    <div>
                        <p class="mb-1 fw-bold text-dark">Email & Push Notifications</p>
                        <small class="text-muted">Receive alerts about new challenges, comments, and site updates.</small>
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" role="switch" id="notification-toggle" style="width: 3em; height: 1.5em;" onchange="toggleNotifications(event)" {{ $user->allow_notifications == 1 ? 'checked' : '' }}>
                    </div>
                </div>
            </div>

            <div class="mb-4">
                <h2 class="section-title"><i class="fas fa-info-circle me-2 text-muted"></i>App Information</h2>
                <button onclick="showAbout()" class="btn btn-info-custom w-100 py-3">
                    About BattleArt App
                </button>
            </div>

            <div>
                <h2 class="section-title text-danger" style="border-bottom-color: #fee2e2;"><i class="fas fa-exclamation-triangle me-2"></i>Danger Zone</h2>
                <button onclick="deleteAccount()" class="btn btn-danger-custom w-100 py-3">
                    Permanently Delete Account
                </button>
            </div>
        </div>
    </div>

    <form id="delete-account-form" action="{{ route('settings.delete') }}" method="POST" style="display: none;">
        @csrf
    </form>

    <div id="message-container"></div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function showMessage(title, message, isError = false) {
            const container = document.getElementById('message-container');
            const modalId = `messageModal_${Date.now()}`;
            const headerClass = isError ? 'bg-danger text-white' : 'bg-primary text-white';
            const btnClass = isError ? 'btn-danger' : 'btn-primary';

            const modalHTML = `
            <div class="modal fade" id="${modalId}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-sm modal-dialog-centered">
                    <div class="modal-content rounded-4 border-0 shadow-lg overflow-hidden">
                        <div class="modal-header ${headerClass} border-0">
                            <h5 class="modal-title fs-6 fw-bold">${title}</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body p-4 text-center">
                            <p class="text-muted mb-0">${message.replace(/\n/g, '<br>')}</p>
                        </div>
                        <div class="modal-footer border-0 p-3 pt-0">
                            <button type="button" class="btn ${btnClass} w-100 rounded-pill" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>`;
            container.innerHTML = modalHTML;
            const modalElement = document.getElementById(modalId);
            const modal = new bootstrap.Modal(modalElement);
            modal.show();
            modalElement.addEventListener('hidden.bs.modal', function() {
                modalElement.remove();
            });
        }

        function showConfirmationModal(title, message, confirmText, confirmVariant, callback) {
            const container = document.getElementById('message-container');
            const modalId = 'confirmModal';
            if (document.getElementById(modalId)) document.getElementById(modalId).remove();

            const modalHTML = `
            <div class="modal fade" id="${modalId}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content rounded-4 border-0 shadow-lg">
                        <div class="modal-body p-5 text-center">
                            <i class="fas fa-exclamation-circle text-${confirmVariant} fa-3x mb-3"></i>
                            <h4 class="fw-bold mb-2">${title}</h4>
                            <p class="text-muted mb-4">${message}</p>
                            <div class="d-flex justify-content-center gap-2">
                                <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                                <button type="button" id="confirmActionBtn" class="btn btn-${confirmVariant} rounded-pill px-4">${confirmText}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>`;
            container.innerHTML = modalHTML;
            const modalElement = document.getElementById(modalId);
            const modal = new bootstrap.Modal(modalElement);
            modal.show();
            document.getElementById('confirmActionBtn').onclick = () => {
                modal.hide();
                callback();
            };
            modalElement.addEventListener('hidden.bs.modal', function() {
                modalElement.remove();
            });
        }

        function toggleNotifications(event) {
            const isEnabled = event.target.checked;

            fetch("{{ url('/settings/notifications') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        notifications: isEnabled
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Optional: Show a small toast instead of a modal for better UX
                        console.log("Notifications updated");
                    } else {
                        showMessage("Error", "Could not save preference.", true);
                        event.target.checked = !isEnabled;
                    }
                });
        }

        function deleteAccount() {
            showConfirmationModal("Delete Account?", "This action is permanent and cannot be undone. All your data will be lost.", "Delete Permanently", "danger", () => {
                document.getElementById('delete-account-form').submit();
            });
        }

        function showAbout() {
            showMessage("About BattleArt",
                "BattleArt App Version 1.2.0\n\n© 2025 BattleArt Inc. All rights reserved.\nConnecting artists through creative challenges.",
                false);
        }
    </script>
</body>
</html>
