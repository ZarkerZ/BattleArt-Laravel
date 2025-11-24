<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BattleArt - Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="{{ asset('assets/css/custom.css') }}" rel="stylesheet">
</head>

<body>
    <div class="container-fluid vh-100">
        <div class="row h-100 justify-content-center align-items-center">
            <div class="col-12 col-sm-8 col-md-6 col-lg-4">
                <div class="card shadow-lg border-0">
                    <div class="card-body p-5">

                        <div class="text-center mb-4">
                            <h1 class="brand-title fw-bold text-primary mb-2">BattleArt</h1>
                            <p class="subtitle text-muted">Sign in to join art battles & reimagine originals</p>
                        </div>

                        {{-- General Error Alert (Only show if NOT banned to avoid double message with modal) --}}
                        @if ($errors->any() && !session('is_banned'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                                {{ $errors->first() }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        {{-- Success Message (e.g. after registration) --}}
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="bi bi-check-circle-fill me-2"></i>
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('login') }}">
                            @csrf

                            <div class="mb-3">
                                <label for="email" class="form-label">Email address</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="bi bi-envelope" aria-hidden="true"></i>
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

                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="bi bi-lock" aria-hidden="true"></i>
                                    </span>
                                    <input type="password" 
                                           class="form-control" 
                                           id="password" 
                                           name="password" 
                                           placeholder="Enter your password" 
                                           required>
                                    <button class="btn btn-outline-secondary" type="button" id="togglePassword" onclick="togglePasswordVisibility()">
                                        <i class="bi bi-eye" id="toggleIcon"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="rememberMe" name="rememberMe" {{ old('rememberMe') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="rememberMe">
                                        Remember me
                                    </label>
                                </div>
                                {{-- We will add the forgot password route later --}}
                                <a href="{{ url('/forgot-password') }}" class="forgot-password-link">
                                    Forgot Password?
                                </a>
                            </div>

                            <div class="d-grid mb-3">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="bi bi-box-arrow-in-right me-2" aria-hidden="true"></i>
                                    Sign In
                                </button>
                            </div>

                            <div class="text-center">
                                <p class="mb-0">Don't have an account?
                                    <a href="{{ route('register') }}" class="signup-link">
                                        Sign up here
                                    </a>
                                </p>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="text-center mt-3">
                    <a href="{{ url('/') }}" class="back-home-link">
                        <i class="bi bi-arrow-left me-1" aria-hidden="true"></i>
                        Back to Home
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="bannedModal" tabindex="-1" aria-labelledby="bannedModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="bannedModalLabel"><i class="bi bi-slash-circle-fill me-2"></i>Account Banned</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Your account has been banned due to a violation of our terms of service.</p>
                    <p>If you believe this is an error, please contact support for assistance.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
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
                toggleIcon.className = 'bi bi-eye-slash';
            } else {
                passwordField.type = 'password';
                toggleIcon.className = 'bi bi-eye';
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