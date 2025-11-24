<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>BattleArt - Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="{{ asset('assets/css/custom.css') }}" rel="stylesheet">
</head>

<body>
    <div class="container-fluid vh-100">
        <div class="row h-100 justify-content-center align-items-center">
            <div class="col-12 col-sm-10 col-md-8 col-lg-6">
                <div class="card shadow-lg border-0">
                    <div class="card-body p-5">

                        <div class="text-center mb-4">
                            <h1 class="brand-title fw-bold text-primary mb-2">BattleArt</h1>
                            <p class="subtitle text-muted">Join the community and start your art battle journey</p>
                        </div>

                        <form action="{{ route('register') }}" method="post">
                            @csrf <ul class="nav nav-tabs nav-fill mb-3" id="registerTabs" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link {{ old('user_type', 'user') == 'user' ? 'active' : '' }}" 
                                            id="user-tab" 
                                            data-bs-toggle="tab" 
                                            data-bs-target="#user-pane" 
                                            type="button" 
                                            role="tab" 
                                            aria-controls="user-pane" 
                                            aria-selected="{{ old('user_type', 'user') == 'user' ? 'true' : 'false' }}">
                                        <i class="bi bi-person me-2"></i>Register as User
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link {{ old('user_type') == 'admin' ? 'active' : '' }}" 
                                            id="admin-tab" 
                                            data-bs-toggle="tab" 
                                            data-bs-target="#admin-pane" 
                                            type="button" 
                                            role="tab" 
                                            aria-controls="admin-pane" 
                                            aria-selected="{{ old('user_type') == 'admin' ? 'true' : 'false' }}">
                                        <i class="bi bi-person-badge me-2"></i>Register as Admin
                                    </button>
                                </li>
                            </ul>
                            
                            <input type="hidden" name="user_type" id="userTypeInput" value="{{ old('user_type', 'user') }}">

                            <div class="mb-3">
                                <label for="userName" class="form-label">Username</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-at" aria-hidden="true"></i></span>
                                    <input type="text" name="userName" 
                                           class="form-control @error('userName') is-invalid @enderror" 
                                           id="userName" 
                                           value="{{ old('userName') }}" 
                                           placeholder="Enter username">
                                </div>
                                @error('userName')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <label for="firstName" class="form-label">First Name</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-person" aria-hidden="true"></i></span>
                                        <input type="text" name="firstName" 
                                               class="form-control @error('firstName') is-invalid @enderror" 
                                               value="{{ old('firstName') }}" 
                                               id="firstName" 
                                               placeholder="Enter first name">
                                    </div>
                                    @error('firstName')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="middleName" class="form-label">Middle Name</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-person-plus" aria-hidden="true"></i></span>
                                        <input type="text" name="middleName" 
                                               class="form-control" 
                                               id="middleName" 
                                               value="{{ old('middleName') }}" 
                                               placeholder="Enter middle name">
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="lastName" class="form-label">Last Name</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-person-fill" aria-hidden="true"></i></span>
                                    <input type="text" name="lastName" 
                                           class="form-control @error('lastName') is-invalid @enderror" 
                                           id="lastName" 
                                           value="{{ old('lastName') }}" 
                                           placeholder="Enter last name">
                                </div>
                                @error('lastName')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="dob" class="form-label">Date of Birth</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-calendar-event"></i></span>
                                    <input type="date" name="dob" 
                                           class="form-control @error('dob') is-invalid @enderror" 
                                           id="dob" 
                                           value="{{ old('dob') }}">
                                </div>
                                @error('dob')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-envelope" aria-hidden="true"></i></span>
                                    <input type="email" name="email" 
                                           class="form-control @error('email') is-invalid @enderror" 
                                           id="email" 
                                           value="{{ old('email') }}" 
                                           placeholder="Enter email">
                                </div>
                                @error('email')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="password" class="form-label">Password</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-lock" aria-hidden="true"></i></span>
                                        <input type="password" name="password" 
                                               class="form-control @error('password') is-invalid @enderror" 
                                               id="password" 
                                               placeholder="Enter password">
                                        <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                            <i class="bi bi-eye" aria-hidden="true"></i>
                                        </button>
                                    </div>
                                    @error('password')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="confirmPassword" class="form-label">Confirm Password</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-lock-fill" aria-hidden="true"></i></span>
                                        <input type="password" name="confirmPassword" 
                                               class="form-control @error('confirmPassword') is-invalid @enderror" 
                                               id="confirmPassword" 
                                               placeholder="Re-enter password">
                                        <button class="btn btn-outline-secondary" type="button" id="toggleConfirmPassword">
                                            <i class="bi bi-eye" aria-hidden="true"></i>
                                        </button>
                                    </div>
                                    @error('confirmPassword')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="tab-content pt-2" id="registerTabsContent">
                                <div class="tab-pane fade {{ old('user_type', 'user') == 'user' ? 'show active' : '' }}" 
                                     id="user-pane" role="tabpanel" aria-labelledby="user-tab">
                                </div>
                                
                                <div class="tab-pane fade {{ old('user_type') == 'admin' ? 'show active' : '' }}" 
                                     id="admin-pane" role="tabpanel" aria-labelledby="admin-tab">
                                    <div class="mb-3">
                                        <label for="admin_code" class="form-label">Admin Code</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="bi bi-key-fill"></i></span>
                                            <input type="password" name="admin_code" 
                                                   class="form-control @error('admin_code') is-invalid @enderror" 
                                                   id="admin_code" 
                                                   placeholder="Enter secret admin code">
                                        </div>
                                        @error('admin_code')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="d-grid mt-4 mb-3">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="bi bi-person-plus-fill me-2" aria-hidden="true"></i>
                                    Create Account
                                </button>
                            </div>

                            <div class="text-center">
                                <p class="mb-0">Already have an account?
                                    <a href="{{ route('login') }}" class="signup-link">
                                        Sign in here
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.getElementById('togglePassword').addEventListener('click', function() {
            const password = document.getElementById('password');
            const icon = this.querySelector('i');

            if (password.type === 'password') {
                password.type = 'text';
                icon.classList.remove('bi-eye');
                icon.classList.add('bi-eye-slash');
            } else {
                password.type = 'password';
                icon.classList.remove('bi-eye-slash');
                icon.classList.add('bi-eye');
            }
        });

        document.getElementById('toggleConfirmPassword').addEventListener('click', function() {
            const confirmPassword = document.getElementById('confirmPassword');
            const icon = this.querySelector('i');

            if (confirmPassword.type === 'password') {
                confirmPassword.type = 'text';
                icon.classList.remove('bi-eye');
                icon.classList.add('bi-eye-slash');
            } else {
                confirmPassword.type = 'password';
                icon.classList.remove('bi-eye-slash');
                icon.classList.add('bi-eye');
            }
        });

        const userTab = document.getElementById('user-tab');
        const adminTab = document.getElementById('admin-tab');
        const userTypeInput = document.getElementById('userTypeInput');

        if(userTab) {
            userTab.addEventListener('click', function() {
                userTypeInput.value = 'user';
            });
        }
        
        if(adminTab) {
            adminTab.addEventListener('click', function() {
                userTypeInput.value = 'admin';
            });
        }
    </script>
</body>
</html>