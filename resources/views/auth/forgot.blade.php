<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Forgot Password</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('assets/css/custom.css') }}" rel="stylesheet">
</head>
<body>
    <div class="container vh-100 d-flex justify-content-center align-items-center">
        <div class="card shadow-lg p-5 col-md-5">
            <h1 class="text-center text-primary fw-bold mb-2">BattleArt</h1>
            <p class="text-muted text-center mb-4">Reset your password</p>

            @if (session('message'))
                <div class="alert alert-success">{{ session('message') }}</div>
            @endif

            <form action="{{ route('password.email') }}" method="post">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Email Address</label>
                    <input type="email" name="email" class="form-control" required>
                </div>
                <div class="d-grid mb-3">
                    <button type="submit" class="btn btn-primary">Send Reset Link</button>
                </div>
            </form>
            <div class="text-center">
                <a href="{{ route('login') }}">Back to Login</a>
            </div>
        </div>
    </div>
</body>
</html>