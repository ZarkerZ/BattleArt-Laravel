<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage User - {{ $user->user_userName }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="{{ asset('assets/css/navbar.css') }}" rel="stylesheet">
</head>
<body style="background-color: #a6e7ff; padding-top: 30px;">
    @include('partials.navbar')

    <div class="container mt-4">
        <div class="card p-4">
            <div class="d-flex align-items-center mb-4">
                <img src="{{ $user->user_profile_pic ? asset('assets/uploads/'.$user->user_profile_pic) : asset('assets/images/blank-profile-picture.png') }}" class="rounded-circle me-3" width="100" height="100">
                <div>
                    <h2 class="mb-1">{{ $user->user_userName }}</h2>
                    <p class="text-muted mb-2">{{ $user->user_email }}</p>
                    <span class="badge {{ $user->account_status === 'active' ? 'bg-success' : 'bg-danger' }}">{{ ucfirst($user->account_status) }}</span>
                </div>
                <div class="ms-auto">
                    <form action="{{ route('admin.users.status', $user->user_id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?');">
                        @csrf
                        @if($user->account_status === 'active')
                            <button name="action" value="ban" class="btn btn-warning"><i class="bi bi-slash-circle"></i> Ban</button>
                        @else
                            <button name="action" value="unban" class="btn btn-success"><i class="bi bi-check-circle"></i> Unban</button>
                        @endif

                        @if($user->user_id != $admin_user_id)
                            <button name="action" value="delete" class="btn btn-danger ms-2"><i class="bi bi-trash"></i> Delete</button>
                        @endif
                    </form>
                </div>
            </div>

            <div class="row g-3 mb-4 text-center">
                <div class="col"><div class="p-3 bg-light rounded"><h3>{{ $artwork_count }}</h3> Artworks</div></div>
                <div class="col"><div class="p-3 bg-light rounded"><h3>{{ $interpretation_count }}</h3> Interpretations</div></div>
                <div class="col"><div class="p-3 bg-light rounded"><h3>{{ $comment_count }}</h3> Comments</div></div>
                <div class="col"><div class="p-3 bg-light rounded"><h3>{{ $like_count }}</h3> Likes Given</div></div>
            </div>

            <h4>Recent Activity</h4>
            <div class="card">
                <div class="card-body p-0">
                    @forelse($activities as $act)
                        <div class="p-3 border-bottom">
                            <strong>{{ ucfirst($act->type) }}</strong>: {{ Str::limit($act->title, 80) }}
                            <br><small class="text-muted">{{ \Carbon\Carbon::parse($act->date)->diffForHumans() }}</small>
                        </div>
                    @empty
                        <div class="p-3 text-center text-muted">No recent activity.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
