<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - BattleArt</title>
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

        .back-link {
            color: var(--text-muted);
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 600;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
        }

        .back-link:hover {
            color: var(--primary-color);
            transform: translateX(-3px);
        }

        /* --- ALERTS --- */
        .alert-success {
            background-color: #f0fdf4;
            border-color: #bbf7d0;
            color: #166534;
            border-radius: 12px;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
    <div class="container d-flex flex-column align-items-center">
        <div class="auth-card">
            <div class="text-center mb-4">
                <h1 class="brand-title mb-2">BattleArt</h1>
                <p class="subtitle">Reset your password to regain access.</p>
            </div>

            @if (session('message'))
                <div class="alert alert-success mb-4 shadow-sm">
                    <i class="fas fa-check-circle me-2"></i> {{ session('message') }}
                </div>
            @endif

            <form action="{{ route('password.email') }}" method="post">
                @csrf
                <div class="mb-4">
                    <label class="form-label">Email Address</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                        <input type="email" name="email" class="form-control" placeholder="Enter your email" required>
                    </div>
                </div>

                <div class="d-grid mb-4">
                    <button type="submit" class="btn btn-primary-custom">
                        Send Reset Link <i class="fas fa-paper-plane ms-2"></i>
                    </button>
                </div>
            </form>

            <div class="text-center">
                <a href="{{ route('login') }}" class="back-link">
                    <i class="fas fa-arrow-left me-2"></i> Back to Login
                </a>
            </div>
        </div>
    </div>
</body>
</html>
