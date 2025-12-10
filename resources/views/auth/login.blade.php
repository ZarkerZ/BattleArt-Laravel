<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BattleArt - Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            /* --- DESIGN SYSTEM VARIABLES --- */
            --primary-color: #5a4bda;       /* Deep Royal Purple */
            --primary-hover: #4335b8;
            --secondary-color: #f8f9fa;     /* Clean Light Gray Background */
            --text-dark: #1e293b;           /* Slate Dark */
            --text-muted: #64748b;          /* Slate Gray */
            --card-radius: 16px;
        }

        body {
            /* Using the gradient background consistent with auth pages if desired,
               otherwise use var(--secondary-color) for clean look.
               Here I use a subtle gradient based on your palette. */
            background: linear-gradient(135deg, #f8f9fa 0%, #eef2ff 100%);
            font-family: 'Inter', sans-serif;
            color: var(--text-dark);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* --- AUTH CARD --- */
        .auth-card {
            background-color: #ffffff;
            border-radius: var(--card-radius);
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
            border: 1px solid rgba(0,0,0,0.02);
            padding: 2.5rem;
            width: 100%;
            max-width: 450px;
        }

        /* --- TYPOGRAPHY --- */
        .brand-title {
            color: var(--primary-color);
            font-weight: 800;
            letter-spacing: -0.5px;
        }

        .subtitle {
            font-size: 0.95rem;
            color: var(--text-muted);
        }

        /* --- FORM ELEMENTS --- */
        .form-label {
            font-weight: 600;
            font-size: 0.9rem;
            color: var(--text-dark);
            margin-bottom: 0.5rem;
        }

        .input-group-text {
            background-color: #f8f9fa;
            border: 1px solid #e2e8f0;
            border-right: none;
            border-top-left-radius: 12px;
            border-bottom-left-radius: 12px;
            color: var(--text-muted);
        }

        .form-control {
            border: 1px solid #e2e8f0;
            border-left: none;
            border-top-right-radius: 12px;
            border-bottom-right-radius: 12px;
            padding: 0.7rem 1rem 0.7rem 0; /* Adjust padding since icon is on left */
            font-size: 0.95rem;
            color: var(--text-dark);
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(90, 75, 218, 0.1);
            border-left: 1px solid var(--primary-color); /* Fix border on focus */
        }

        /* Fix for input group focus border */
        .input-group:focus-within .input-group-text {
            border-color: var(--primary-color);
        }

        /* --- BUTTONS --- */
        .btn-primary-custom {
            background-color: var(--primary-color);
            color: white;
            border: none;
            border-radius: 50px;
            padding: 0.8rem 2rem;
            font-weight: 600;
            box-shadow: 0 4px 15px rgba(90, 75, 218, 0.3);
            transition: all 0.3s ease;
            width: 100%;
        }

        .btn-primary-custom:hover {
            background-color: var(--primary-hover);
            transform: translateY(-2px);
            color: white;
        }

        .forgot-password-link {
            font-size: 0.9rem;
            color: var(--text-muted);
            text-decoration: none;
            transition: color 0.2s;
        }

        .forgot-password-link:hover {
            color: var(--primary-color);
        }

        .signup-link {
            font-weight: 700;
            color: var(--primary-color);
            text-decoration: none;
        }

        .signup-link:hover {
            text-decoration: underline;
        }

        .back-home-link {
            color: var(--text-muted);
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 600;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
        }

        .back-home-link:hover {
            color: var(--primary-color);
            transform: translateX(-3px);
        }

        /* --- ALERTS --- */
        .alert {
            border-radius: 12px;
            font-size: 0.9rem;
            border: none;
        }
        .alert-danger {
            background-color: #fef2f2;
            color: #ef4444;
        }
        .alert-success {
            background-color: #f0fdf4;
            color: #16a34a;
        }
    </style>
</head>

<body>
    <div class="container d-flex flex-column align-items-center">
        <div class="auth-card">
            <div class="text-center mb-4">
                <h1 class="brand-title mb-2">BattleArt</h1>
                <p class="subtitle">Sign in to join art battles & reimagine originals</p>
            </div>

            {{-- General Error Alert (Only show if NOT banned to avoid double message with modal) --}}
            @if ($errors->any() && !session('is_banned'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    {{ $errors->first() }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            {{-- Success Message --}}
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="mb-3">
                    <label for="email" class="form-label">Email Address</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fas fa-envelope"></i>
                        </span>
                        <input type="email"
                               class="form-control @error('email') is-invalid @enderror"
                               id="email"
                               name="email"
                               placeholder="Enter your email"
                               value="{{ old('email') }}"
                               required>
                    </div>
                </div>

                <div class="mb-4">
                    <label for="password" class="form-label">Password</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fas fa-lock"></i>
                        </span>
                        <input type="password"
                               class="form-control"
                               id="password"
                               name="password"
                               placeholder="Enter your password"
                               required
                               style="border-right: none; border-radius: 0;">
                        <button class="btn btn-outline-secondary" type="button" id="togglePassword" onclick="togglePasswordVisibility()" style="border-top-right-radius: 12px; border-bottom-right-radius: 12px; border-left: none; border-color: #e2e8f0; background: white; color: #94a3b8;">
                            <i class="fas fa-eye" id="toggleIcon"></i>
                        </button>
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="rememberMe" name="rememberMe" {{ old('rememberMe') ? 'checked' : '' }} style="cursor: pointer;">
                        <label class="form-check-label small text-muted" for="rememberMe" style="cursor: pointer;">
                            Remember me
                        </label>
                    </div>
                    <a href="{{ url('/forgot-password') }}" class="forgot-password-link">
                        Forgot Password?
                    </a>
                </div>

                <div class="d-grid mb-4">
                    <button type="submit" class="btn btn-primary-custom">
                        Sign In <i class="fas fa-arrow-right ms-2"></i>
                    </button>
                </div>

                <div class="text-center">
                    <p class="mb-0 text-muted small">Don't have an account?
                        <a href="{{ route('register') }}" class="signup-link ms-1">
                            Sign up here
                        </a>
                    </p>
                </div>
            </form>
        </div>

        <div class="mt-4">
            <a href="{{ url('/') }}" class="back-home-link">
                <i class="fas fa-arrow-left me-2"></i> Back to Home
            </a>
        </div>
    </div>

    <div class="modal fade" id="bannedModal" tabindex="-1" aria-labelledby="bannedModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4 border-0 shadow-lg">
                <div class="modal-header text-white border-0" style="background-color: #ef4444;">
                    <h5 class="modal-title fw-bold" id="bannedModalLabel">
                        <i class="fas fa-ban me-2"></i> Account Banned
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4 text-center">
                    <p class="text-muted mb-2">Your account has been banned due to a violation of our terms of service.</p>
                    <p class="small text-muted mb-0">If you believe this is an error, please contact support.</p>
                </div>
                <div class="modal-footer border-0 justify-content-center pb-4 pt-0">
                    <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function togglePasswordVisibility() {
            const passwordField = document.getElementById('password');
            const toggleIcon = document.getElementById('toggleIcon');
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                toggleIcon.className = 'fas fa-eye-slash';
            } else {
                passwordField.type = 'password';
                toggleIcon.className = 'fas fa-eye';
            }
        }

        // Logic to show modal if session 'is_banned' is set
        @if (session('is_banned'))
            document.addEventListener('DOMContentLoaded', (event) => {
                var bannedModal = new bootstrap.Modal(document.getElementById('bannedModal'));
                bannedModal.show();
            });
        @endif
    </script>
</body>
</html>
