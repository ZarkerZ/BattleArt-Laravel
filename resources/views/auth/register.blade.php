<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BattleArt - Register</title>
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
            background: linear-gradient(135deg, #f8f9fa 0%, #eef2ff 100%);
            font-family: 'Inter', sans-serif;
            color: var(--text-dark);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 0;
        }

        /* --- AUTH CARD --- */
        .auth-card {
            background-color: #ffffff;
            border-radius: var(--card-radius);
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
            border: 1px solid rgba(0,0,0,0.02);
            padding: 2.5rem;
            width: 100%;
            max-width: 600px;
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

        /* --- TABS --- */
        .nav-tabs {
            border-bottom: 2px solid #f1f5f9;
            margin-bottom: 1.5rem;
        }

        .nav-link {
            color: var(--text-muted);
            font-weight: 600;
            border: none;
            background: transparent;
            padding-bottom: 1rem;
            transition: all 0.2s;
        }

        .nav-link.active {
            color: var(--primary-color);
            background: transparent;
            border-bottom: 3px solid var(--primary-color);
        }

        .nav-link:hover {
            color: var(--primary-hover);
            border-color: transparent;
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
            padding: 0.7rem 1rem 0.7rem 0;
            font-size: 0.95rem;
            color: var(--text-dark);
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(90, 75, 218, 0.1);
            border-left: 1px solid var(--primary-color);
        }

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
    </style>
</head>

<body>
    <div class="container d-flex flex-column align-items-center">
        <div class="auth-card">
            <div class="text-center mb-4">
                <h1 class="brand-title mb-2">BattleArt</h1>
                <p class="subtitle">Join the community and start your art battle journey</p>
            </div>

            <form action="{{ route('register') }}" method="post">
                @csrf

                <ul class="nav nav-tabs nav-fill" id="registerTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link {{ old('user_type', 'user') == 'user' ? 'active' : '' }}"
                                id="user-tab"
                                data-bs-toggle="tab"
                                data-bs-target="#user-pane"
                                type="button"
                                role="tab">
                            <i class="fas fa-user me-2"></i> User
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link {{ old('user_type') == 'admin' ? 'active' : '' }}"
                                id="admin-tab"
                                data-bs-toggle="tab"
                                data-bs-target="#admin-pane"
                                type="button"
                                role="tab">
                            <i class="fas fa-user-shield me-2"></i> Admin
                        </button>
                    </li>
                </ul>

                <input type="hidden" name="user_type" id="userTypeInput" value="{{ old('user_type', 'user') }}">

                <div class="mb-3">
                    <label for="userName" class="form-label">Username</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-at"></i></span>
                        <input type="text" name="userName"
                               class="form-control @error('userName') is-invalid @enderror"
                               id="userName"
                               value="{{ old('userName') }}"
                               placeholder="Enter username">
                    </div>
                    @error('userName')
                        <small class="text-danger ps-1">{{ $message }}</small>
                    @enderror
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label for="firstName" class="form-label">First Name</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                            <input type="text" name="firstName"
                                   class="form-control @error('firstName') is-invalid @enderror"
                                   value="{{ old('firstName') }}"
                                   id="firstName"
                                   placeholder="First Name">
                        </div>
                        @error('firstName')
                            <small class="text-danger ps-1">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="middleName" class="form-label">Middle Name</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-user-plus"></i></span>
                            <input type="text" name="middleName"
                                   class="form-control"
                                   id="middleName"
                                   value="{{ old('middleName') }}"
                                   placeholder="Optional">
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="lastName" class="form-label">Last Name</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-user-tag"></i></span>
                        <input type="text" name="lastName"
                               class="form-control @error('lastName') is-invalid @enderror"
                               id="lastName"
                               value="{{ old('lastName') }}"
                               placeholder="Last Name">
                    </div>
                    @error('lastName')
                        <small class="text-danger ps-1">{{ $message }}</small>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="dob" class="form-label">Date of Birth</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                        <input type="date" name="dob"
                               class="form-control @error('dob') is-invalid @enderror"
                               id="dob"
                               value="{{ old('dob') }}">
                    </div>
                    @error('dob')
                        <small class="text-danger ps-1">{{ $message }}</small>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email Address</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                        <input type="email" name="email"
                               class="form-control @error('email') is-invalid @enderror"
                               id="email"
                               value="{{ old('email') }}"
                               placeholder="name@example.com">
                    </div>
                    @error('email')
                        <small class="text-danger ps-1">{{ $message }}</small>
                    @enderror
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label for="password" class="form-label">Password</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-lock"></i></span>
                            <input type="password" name="password"
                                   class="form-control @error('password') is-invalid @enderror"
                                   id="password"
                                   placeholder="********"
                                   style="border-right: none; border-radius: 0;">
                            <button class="btn btn-outline-secondary" type="button" id="togglePassword"
                                    style="border-top-right-radius: 12px; border-bottom-right-radius: 12px; border-left: none; border-color: #e2e8f0; background: white; color: #94a3b8;">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        @error('password')
                            <small class="text-danger ps-1">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="confirmPassword" class="form-label">Confirm Password</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-lock"></i></span>
                            <input type="password" name="confirmPassword"
                                   class="form-control @error('confirmPassword') is-invalid @enderror"
                                   id="confirmPassword"
                                   placeholder="********"
                                   style="border-right: none; border-radius: 0;">
                            <button class="btn btn-outline-secondary" type="button" id="toggleConfirmPassword"
                                    style="border-top-right-radius: 12px; border-bottom-right-radius: 12px; border-left: none; border-color: #e2e8f0; background: white; color: #94a3b8;">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        @error('confirmPassword')
                            <small class="text-danger ps-1">{{ $message }}</small>
                        @enderror
                    </div>
                </div>

                <div class="tab-content">
                    <div class="tab-pane fade {{ old('user_type', 'user') == 'user' ? 'show active' : '' }}" id="user-pane"></div>

                    <div class="tab-pane fade {{ old('user_type') == 'admin' ? 'show active' : '' }}" id="admin-pane">
                        <div class="mb-4 p-3 bg-light rounded-3 border">
                            <label for="admin_code" class="form-label">Admin Code</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-key"></i></span>
                                <input type="password" name="admin_code"
                                       class="form-control @error('admin_code') is-invalid @enderror"
                                       id="admin_code"
                                       placeholder="Enter secret admin code">
                            </div>
                            @error('admin_code')
                                <small class="text-danger ps-1">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="d-grid mb-4">
                    <button type="submit" class="btn btn-primary-custom">
                        Create Account <i class="fas fa-arrow-right ms-2"></i>
                    </button>
                </div>

                <div class="text-center">
                    <p class="mb-0 text-muted small">Already have an account?
                        <a href="{{ route('login') }}" class="signup-link ms-1">
                            Sign in here
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Password Visibility Toggle
        function setupPasswordToggle(inputId, toggleId) {
            const input = document.getElementById(inputId);
            const toggleBtn = document.getElementById(toggleId);
            const icon = toggleBtn.querySelector('i');

            toggleBtn.addEventListener('click', function() {
                if (input.type === 'password') {
                    input.type = 'text';
                    icon.classList.replace('fa-eye', 'fa-eye-slash');
                } else {
                    input.type = 'password';
                    icon.classList.replace('fa-eye-slash', 'fa-eye');
                }
            });
        }

        setupPasswordToggle('password', 'togglePassword');
        setupPasswordToggle('confirmPassword', 'toggleConfirmPassword');

        // Tab Logic
        const userTab = document.getElementById('user-tab');
        const adminTab = document.getElementById('admin-tab');
        const userTypeInput = document.getElementById('userTypeInput');

        if(userTab) {
            userTab.addEventListener('click', () => userTypeInput.value = 'user');
        }

        if(adminTab) {
            adminTab.addEventListener('click', () => userTypeInput.value = 'admin');
        }
    </script>
</body>
</html>
