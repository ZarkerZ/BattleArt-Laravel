<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>BattleArt - Creative Community</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <link href="{{ asset('assets/css/navbar.css') }}" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/images/doro.ico') }}">

    <style>
        :root {
            /* Professional Palette */
            --primary-color: #5a4bda;       /* Deep Royal Purple */
            --primary-hover: #4335b8;       /* Darker Purple for hover */
            --secondary-color: #f8f9fa;     /* Clean Light Gray Background */
            --accent-color: #7b68ee;        /* Soft Purple for highlights */
            --text-dark: #1e293b;           /* Slate Dark for text */
            --text-muted: #64748b;          /* Slate Gray for muted text */
            --border-radius: 16px;
        }

        body {
            background-color: var(--secondary-color);
            font-family: 'Inter', sans-serif;
            color: var(--text-dark);
            margin: 0;
            padding: 0;
            overflow-x: hidden;
            /* Add padding-top if navbar is fixed */
            padding-top: 70px;
        }

        /* --- FIXED NAVBAR STYLING --- */
        /* Explicitly setting background color to fix transparency */
        .custom-navbar {
            background-color: var(--primary-color) !important; /* Force purple background */
            backdrop-filter: none; /* Remove blur if it causes transparency issues */
            -webkit-backdrop-filter: none;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1); /* Optional: adds shadow for depth */
        }

        /* --- HERO SECTION --- */
        .main-banner-area {
            position: relative;
            /* Use asset() for Laravel or correct relative path */
            background-image: url('{{ asset("assets/images/Background.png") }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            color: #fff;
            padding: 6rem 0 4rem;
            margin-bottom: 3rem;
            /* Overlay for better text readability */
            box-shadow: inset 0 0 0 2000px rgba(30, 20, 60, 0.6);
            margin-top: -70px; /* Counteract body padding if hero should touch top */
        }

        .hero-section {
            position: relative;
            z-index: 2;
            text-align: center;
            padding: 0 1rem;
        }

        .hero-section h1 {
            font-size: 3.5rem;
            font-weight: 800;
            margin-bottom: 1rem;
            letter-spacing: -1px;
            text-shadow: 0 2px 10px rgba(0,0,0,0.3);
        }

        .hero-section p {
            font-size: 1.25rem;
            margin-bottom: 2rem;
            max-width: 700px;
            margin-left: auto;
            margin-right: auto;
            opacity: 0.95;
            line-height: 1.6;
        }

        .btn-hero {
            background-color: var(--primary-color);
            color: white;
            padding: 0.8rem 2.5rem;
            border-radius: 50px;
            font-weight: 600;
            font-size: 1.1rem;
            border: none;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(90, 75, 218, 0.4);
            text-decoration: none; /* Ensure link looks like button */
            display: inline-block;
        }

        .btn-hero:hover {
            background-color: var(--primary-hover);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(90, 75, 218, 0.6);
            color: white;
        }

        /* --- SEARCH BAR --- */
        .search-container {
            margin-top: 3rem;
            position: relative;
            z-index: 2;
        }

        .search-input-group {
            max-width: 600px;
            margin: 0 auto;
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
            border-radius: 50px;
            overflow: hidden;
            display: flex; /* Ensure flex layout for input group */
        }

        .search-input {
            border: none;
            padding: 1rem 1.5rem;
            font-size: 1rem;
            flex: 1; /* Take remaining space */
        }

        .search-input:focus {
            outline: none; /* Remove default outline */
            box-shadow: inset 0 0 0 2px var(--primary-color);
        }

        /* --- SEARCH BUTTON FIX --- */
        .search-icon-btn {
            background-color: white; /* Keep background white */
            border: none;
            padding: 0 1.5rem;
            color: var(--primary-color); /* Purple icon */
            transition: all 0.2s ease; /* Smooth transition */
            cursor: pointer;
        }

        .search-icon-btn:hover {
            background-color: #f0f0f0; /* Light gray background on hover instead of disappearing */
            color: var(--primary-hover); /* Darker purple icon */
        }

        /* --- SECTION TITLES --- */
        .section-title {
            color: var(--text-dark);
            font-size: 2rem;
            font-weight: 800;
            text-align: center;
            margin-bottom: 3rem;
            position: relative;
            display: inline-block;
        }

        .section-header {
            text-align: center;
            margin-bottom: 3rem;
        }

        /* --- TRENDING CARDS --- */
        .placeholder-card {
            background: #fff;
            border-radius: var(--border-radius);
            padding: 1rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            height: 100%;
            border: 1px solid rgba(0,0,0,0.05);
            display: flex;
            flex-direction: column;
        }

        .placeholder-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
        }

        .trending-card-image {
            width: 100%;
            height: 220px;
            object-fit: cover;
            border-radius: 12px;
            margin-bottom: 1rem;
        }

        .trending-card-title {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 0.25rem;
            text-decoration: none;
            display: block;
        }

        .author-link {
            color: var(--text-muted);
            font-size: 0.9rem;
            text-decoration: none;
            margin-bottom: 1rem;
            display: block;
        }

        .author-link:hover {
            color: var(--primary-color);
        }

        .social-metrics {
            display: flex;
            justify-content: center;
            gap: 1.5rem;
            margin-bottom: 1.5rem;
            color: var(--text-muted);
            font-size: 0.9rem;
        }

        .btn-card-action {
            width: 100%;
            border-radius: 50px;
            padding: 0.6rem;
            font-weight: 600;
            font-size: 0.9rem;
        }

        /* --- FEATURES --- */
        .feature-card {
            background: #fff;
            padding: 2rem;
            border-radius: var(--border-radius);
            text-align: center;
            height: 100%;
            transition: all 0.3s ease;
            border: 1px solid rgba(0,0,0,0.03);
            box-shadow: 0 4px 6px rgba(0,0,0,0.02);
        }

        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.08);
            border-color: var(--accent-color);
        }

        .feature-card h5 {
            color: var(--text-dark);
            font-weight: 700;
            margin-bottom: 1rem;
            font-size: 1.25rem;
        }

        .feature-card p {
            color: var(--text-muted);
            line-height: 1.6;
            font-size: 0.95rem;
            margin: 0;
        }

        /* --- ABOUT SECTION --- */
        .about-section {
            background-color: #fff;
            padding: 5rem 0;
            margin-top: 5rem;
            border-top: 1px solid #eee;
        }

        .about-intro {
            font-size: 1.2rem;
            color: var(--text-dark);
            max-width: 800px;
            margin: 0 auto 3rem;
            text-align: center;
            line-height: 1.8;
        }

        .about-mission {
            background: linear-gradient(135deg, #f8f9fa 0%, #eef2ff 100%);
            padding: 2rem;
            border-radius: var(--border-radius);
            text-align: center;
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--primary-color);
            margin-bottom: 4rem;
            border: 1px solid #e0e7ff;
        }

        /* How It Works Steps */
        .step-card {
            text-align: center;
            padding: 1rem;
        }

        .step-number {
            width: 50px;
            height: 50px;
            background-color: var(--primary-color);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            font-weight: bold;
            margin: 0 auto 1.5rem;
            box-shadow: 0 4px 10px rgba(90, 75, 218, 0.3);
        }

        .step-title {
            font-weight: 700;
            margin-bottom: 0.5rem;
            color: var(--text-dark);
        }

        /* Values Grid */
        .values-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-top: 2rem;
        }

        .value-item {
            background: #f8f9fa;
            padding: 1.5rem;
            border-radius: 12px;
            border-left: 4px solid var(--primary-color);
        }

        .value-item strong {
            display: block;
            color: var(--text-dark);
            font-size: 1.1rem;
            margin-bottom: 0.25rem;
        }

        .value-item span {
            color: var(--text-muted);
            font-size: 0.95rem;
        }

        /* --- FOOTER --- */
        .footer-custom {
            background-color: #1e293b; /* Dark Slate */
            color: #fff;
            padding: 3rem 0;
            margin-top: 0;
            text-align: center;
        }

        .footer-custom a {
            color: #cbd5e1;
            text-decoration: none;
            margin: 0 1rem;
            font-size: 0.9rem;
            transition: color 0.2s;
        }

        .footer-custom a:hover {
            color: #fff;
        }

        .copyright {
            color: #94a3b8;
            font-size: 0.9rem;
            margin-bottom: 1rem;
        }

        /* --- MOBILE RESPONSIVENESS --- */
        @media (max-width: 768px) {
            .hero-section h1 { font-size: 2.5rem; }
            .hero-section p { font-size: 1.1rem; }
            .main-banner-area { padding: 4rem 0 3rem; }
            .search-input-group { width: 90%; }
            .section-header { margin-bottom: 2rem; }
            /* Ensure padding on mobile body for fixed navbar */
            body { padding-top: 60px; }
        }
    </style>
</head>
<body>
    @include('partials.navbar')

    <div class="main-banner-area">
        <div class="container hero-section">
            <h1>Unleash Your Creative Power</h1>
            <p>Join the ultimate community for digital artists. Post originals, accept challenges, and see your characters reimagined in new styles.</p>

            <a href="{{ Auth::check() ? route('challenges.index') : route('login') }}" class="btn btn-hero">
                Start Creating
            </a>

            <div class="search-container">
                <form action="{{ route('search') }}" method="GET">
                    <div class="input-group search-input-group">
                        <input type="text" name="query" class="form-control search-input" placeholder="Search for art, challenges, or artists..." required>
                        <button class="btn search-icon-btn" type="submit">
                            <i class="fas fa-search fa-lg"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="container py-5">
        <div class="section-header">
            <h2 class="section-title">Trending Challenges</h2>
            <p class="text-muted">Discover what's popular in the community this week</p>
        </div>

        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4 justify-content-center">
            @forelse($trending_challenges as $challenge)
                <div class="col">
                    <div class="placeholder-card">
                        <a href="{{ route('challenges.show', $challenge->challenge_id) }}">
                            <img src="{{ asset('assets/uploads/' . $challenge->original_art_filename) }}" class="trending-card-image shadow-sm">
                        </a>

                        <div class="mt-2 text-center">
                            <a href="{{ route('challenges.show', $challenge->challenge_id) }}" class="trending-card-title d-block">
                                {{ Str::limit($challenge->challenge_name, 20) }}
                            </a>
                            <a href="{{ route('profile.show', $challenge->user_id) }}" class="author-link">
                                by {{ $challenge->user_userName }}
                            </a>
                        </div>

                        <div class="social-metrics">
                            <span><i class="fas fa-heart text-danger me-1"></i> {{ $challenge->like_count }}</span>
                            <span><i class="fas fa-comment text-primary me-1"></i> {{ $challenge->comment_count }}</span>
                        </div>

                        <div class="mt-auto">
                            @if(Auth::id() == $challenge->user_id)
                                <a href="{{ route('challenges.show', $challenge->challenge_id) }}" class="btn btn-outline-primary btn-card-action">View</a>
                            @else
                                <a href="{{ Auth::check() ? route('interpretations.create', ['challenge_id' => $challenge->challenge_id]) : route('login') }}"
                                   class="btn btn-primary btn-card-action" style="background-color: var(--primary-color); border: none; color: white;">
                                   Challenge!
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <p class="text-center text-muted py-5">No trending challenges found yet. Be the first!</p>
                </div>
            @endforelse
        </div>
    </div>

    <div class="container py-5">
        <div class="section-header">
            <h2 class="section-title">Why Join BattleArt?</h2>
        </div>
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
            <div class="col">
                <div class="feature-card">
                    <i class="fas fa-palette fa-2x mb-3" style="color: var(--primary-color);"></i>
                    <h5>Share Originals</h5>
                    <p>Post your unique character designs and concepts for the world to see.</p>
                </div>
            </div>
            <div class="col">
                <div class="feature-card">
                    <i class="fas fa-random fa-2x mb-3" style="color: var(--primary-color);"></i>
                    <h5>Reimagine Art</h5>
                    <p>Challenge others to draw your characters in their own unique style.</p>
                </div>
            </div>
            <div class="col">
                <div class="feature-card">
                    <i class="fas fa-users fa-2x mb-3" style="color: var(--primary-color);"></i>
                    <h5>Community Feedback</h5>
                    <p>Get constructive feedback and grow alongside other talented artists.</p>
                </div>
            </div>
            <div class="col">
                <div class="feature-card">
                    <i class="fas fa-globe fa-2x mb-3" style="color: var(--primary-color);"></i>
                    <h5>Global Exposure</h5>
                    <p>Connect with artists from around the world and expand your audience.</p>
                </div>
            </div>
            <div class="col">
                <div class="feature-card">
                    <i class="fas fa-trophy fa-2x mb-3" style="color: var(--primary-color);"></i>
                    <h5>Skill Growth</h5>
                    <p>Participating in challenges pushes you out of your comfort zone.</p>
                </div>
            </div>
            <div class="col">
                <div class="feature-card">
                    <i class="fas fa-shield-alt fa-2x mb-3" style="color: var(--primary-color);"></i>
                    <h5>Safe Platform</h5>
                    <p>A supportive environment focused on creativity and respect.</p>
                </div>
            </div>
        </div>
    </div>

    <section class="about-section">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title" style="margin-bottom: 1rem;">About BattleArt</h2>
            </div>

            <div class="about-content">
                <p class="about-intro">
                    BattleArt is a premier community for artists who love to challenge themselves.
                    We believe that reinterpreting art is the ultimate form of flattery and the fastest way to learn.
                </p>

                <div class="about-mission">
                    "Inspire growth through creative collaboration."
                </div>

                <div class="row mt-5">
                    <div class="col-md-4">
                        <div class="step-card">
                            <div class="step-number">1</div>
                            <div class="step-title">Post</div>
                            <p class="text-muted small">Share your original character or concept art.</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="step-card">
                            <div class="step-number">2</div>
                            <div class="step-title">Challenge</div>
                            <p class="text-muted small">Invite others to redraw it in their style.</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="step-card">
                            <div class="step-number">3</div>
                            <div class="step-title">Grow</div>
                            <p class="text-muted small">Compare styles, learn techniques, and improve.</p>
                        </div>
                    </div>
                </div>

                <div class="values-grid">
                    <div class="value-item">
                        <strong>Respect</strong>
                        <span>Honor every artist's journey and perspective.</span>
                    </div>
                    <div class="value-item">
                        <strong>Originality</strong>
                        <span>Celebrate unique voices and creative expression.</span>
                    </div>
                    <div class="value-item">
                        <strong>Attribution</strong>
                        <span>Always credit the original creator.</span>
                    </div>
                    <div class="value-item">
                        <strong>Support</strong>
                        <span>Provide constructive and helpful feedback.</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer class="footer-custom">
        <div class="container">
            <p class="copyright">&copy; 2025 BattleArt. All rights reserved.</p>
            <div>
                <a href="#">Privacy Policy</a>
                <a href="#">Terms of Service</a>
                <a href="#">Contact Us</a>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
