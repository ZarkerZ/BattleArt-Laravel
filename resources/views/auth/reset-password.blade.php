<form method="post" action="{{ route('password.update') }}">
    @csrf
    <input type="hidden" name="token" value="{{ $token }}">
    ```

**2. `resources/views/profile/show.blade.php`**
(This replaces both `profile.php` and `public_profile.php`. Use `$isOwnProfile` to check if you should show the "Edit" button).

```html
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $user->user_userName }} - Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="{{ asset('assets/css/custom.css') }}" rel="stylesheet">
    </head>
<body>
    @include('partials.navbar')

    <div class="profile-container">
        <img src="{{ $user->user_banner_pic ? asset('assets/uploads/' . $user->user_banner_pic) : asset('assets/images/night-road.png') }}" class="profile-banner">

        <ul class="nav nav-pills profile-tabs" id="profile-tabs">
            <li class="nav-item"><a class="nav-link active" id="profile-tab" href="#">Profile</a></li>
            @if($user->show_art)<li class="nav-item"><a class="nav-link" id="your-art-tab" href="#">Art</a></li>@endif
            @if($user->show_history)<li class="nav-item"><a class="nav-link" id="history-tab" href="#">History</a></li>@endif
            @if($user->show_comments)<li class="nav-item"><a class="nav-link" id="comments-tab" href="#">Comments</a></li>@endif
        </ul>

        <div id="profile-content">
            <div class="profile-header">
                <img src="{{ $user->user_profile_pic ? asset('assets/uploads/' . $user->user_profile_pic) : asset('assets/images/blank-profile-picture.png') }}" class="profile-avatar">
                <div class="profile-info">
                    <h3>{{ $user->user_userName }} <span class="badge bg-primary">{{ $user->user_type }}</span></h3>
                    @if($user->account_status === 'banned') <span class="badge bg-danger">Banned</span> @endif
                </div>
                
                @if($isOwnProfile)
                <div class="profile-actions">
                    <a href="{{ route('settings.index') }}" class="btn btn-profile">Edit Profile</a>
                </div>
                @endif
            </div>
            <div class="stats-grid">
                <div class="stat-item"><h6>Challenges</h6><p>{{ $challenges_count }}</p></div>
                <div class="stat-item"><h6>Interpretations</h6><p>{{ $interpretations_count }}</p></div>
                <div class="stat-item"><h6>Total Art</h6><p>{{ $total_art }}</p></div>
            </div>
            <div class="welcome-section">
                <p>{!! nl2br(e($user->user_bio)) !!}</p>
                <div class="star-rating">
                    <strong>{{ $avg_rating }}</strong> stars ({{ $rating_count }} reviews)
                </div>
            </div>
        </div>

        <div id="your-art-content" style="display:none;">
            <div class="row">
                @foreach($user_challenges as $art)
                    <div class="col-md-4 mb-4">
                        <div class="card art-card">
                            <img src="{{ asset('assets/uploads/'.$art->original_art_filename) }}" class="card-img-top">
                            <div class="card-body">
                                <h6>{{ $art->challenge_name }}</h6>
                                <a href="{{ url('challenge/'.$art->challenge_id) }}" class="btn btn-sm btn-primary">View</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        
        </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Your existing JS for tab switching
        document.querySelectorAll('.profile-tabs .nav-link').forEach(tab => {
            tab.addEventListener('click', (e) => {
                e.preventDefault();
                document.querySelectorAll('.profile-tabs .nav-link').forEach(t => t.classList.remove('active'));
                e.target.classList.add('active');
                
                const contentId = e.target.id.replace('-tab', '-content');
                ['profile-content', 'your-art-content', 'history-content', 'comments-content'].forEach(id => {
                    const el = document.getElementById(id);
                    if(el) el.style.display = (id === contentId) ? 'block' : 'none';
                });
            });
        });
    </script>
</body>
</html>