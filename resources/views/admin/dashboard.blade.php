<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Users</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="{{ asset('assets/css/custom.css') }}" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/images/doro.ico') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/navbar.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        :root { --primary-bg: #8c76ec; --secondary-bg: #a6e7ff; --light-purple: #c3b4fc; --text-dark: #333; }
        body { background-color: var(--secondary-bg); font-family: 'Inter', sans-serif; padding-top: 20px; }
        .dashboard-container { max-width: 1200px; margin: 3rem auto; background: #fff; padding: 2.5rem; border-radius: 20px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.12); }
        h2 { color: var(--primary-bg); font-weight: bold; text-align: center; margin-bottom: 2rem; }
        .stat-card { background: linear-gradient(135deg, var(--primary-bg), #7b68ee); color: #fff; padding: 1.5rem; border-radius: 15px; flex: 1; min-width: 200px; text-align: center; }
        .stats-container { display: flex; gap: 1.5rem; margin-bottom: 2rem; flex-wrap: wrap; }

        /* Tabs Styling */
        .nav-pills .nav-link { color: var(--text-dark); font-weight: 600; margin-right: 10px; border-radius: 50px; padding: 10px 25px; }
        .nav-pills .nav-link.active { background-color: var(--primary-bg); color: #fff; }

        /* Status Badges */
        .role-badge { padding: 0.35rem 0.75rem; border-radius: 20px; font-size: 0.85rem; font-weight: 600; }
        .role-admin { background-color: #ff6b6b; color: #fff; }
        .role-user { background-color: #4ecdc4; color: #fff; }
        .status-banned { background-color: #dc3545; color: white; }
        .status-archived { background-color: #6c757d; color: white; }
        .status-active { background-color: #28a745; color: white; }
    </style>
</head>

<body>
    @include('partials.navbar')

    <div class="dashboard-container">
        <h2><i class="bi bi-speedometer2 me-2"></i>Admin Dashboard</h2>

        <div class="stats-container">
            <div class="stat-card"><h4>{{ $total_users }}</h4><p>Total Users</p></div>
            <div class="stat-card"><h4>{{ $total_admins }}</h4><p>Admins</p></div>
            <div class="stat-card"><h4>{{ $reports->count() }}</h4><p>Pending Reports</p></div>
        </div>

        <ul class="nav nav-pills mb-4 justify-content-center" id="pills-tab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="pills-users-tab" data-bs-toggle="pill" data-bs-target="#pills-users" type="button">Manage Users</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="pills-reports-tab" data-bs-toggle="pill" data-bs-target="#pills-reports" type="button">
                    Pending Reports
                    @if($reports->count() > 0)
                        <span class="badge bg-danger ms-2">{{ $reports->count() }}</span>
                    @endif
                </button>
            </li>
        </ul>

        <div class="tab-content" id="pills-tabContent">

            <div class="tab-pane fade show active" id="pills-users" role="tabpanel">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr><th>Avatar</th><th>Username</th><th>Role</th><th>Status</th><th>Joined</th><th>Action</th></tr>
                        </thead>
                        <tbody>
                            @forelse($users as $user)
                                <tr>
                                    <td><img src="{{ $user->user_profile_pic ? asset('assets/uploads/'.$user->user_profile_pic) : asset('assets/images/blank-profile-picture.png') }}" class="rounded-circle" width="45" height="45" onerror="this.src='{{ asset('assets/images/blank-profile-picture.png') }}'"></td>
                                    <td><strong>{{ $user->user_userName }}</strong></td>
                                    <td><span class="role-badge {{ $user->user_type === 'admin' ? 'role-admin' : 'role-user' }}">{{ ucfirst($user->user_type) }}</span></td>
                                    <td>
                                        @if($user->account_status === 'banned') <span class="role-badge status-banned">Banned</span>
                                        @elseif($user->account_status === 'archived') <span class="role-badge status-archived">Archived</span>
                                        @else <span class="role-badge status-active">Active</span>
                                        @endif
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($user->joined_date)->format('M d, Y') }}</td>
                                    <td><a href="{{ route('admin.users.show', $user->user_id) }}" class="btn btn-sm btn-primary rounded-pill px-3">View</a></td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="text-center py-4">No users found.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="tab-pane fade" id="pills-reports" role="tabpanel">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr><th>Reporter</th><th>Type</th><th>Reason</th><th>Target</th><th>Date</th><th>Action</th></tr>
                        </thead>
                        <tbody>
                            @forelse($reports as $report)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="{{ $report->user_profile_pic ? asset('assets/uploads/'.$report->user_profile_pic) : asset('assets/images/blank-profile-picture.png') }}" class="rounded-circle me-2" width="30" height="30">
                                            <span class="small fw-bold">{{ $report->reporter_name }}</span>
                                        </div>
                                    </td>
                                    <td><span class="badge bg-secondary">{{ ucfirst($report->type) }}</span></td>
                                    <td class="text-danger fw-bold">{{ $report->reason }} <br> <small class="text-muted fw-normal">{{ Str::limit($report->details, 30) }}</small></td>
                                    <td>
                                        @if($report->type == 'user')
                                            <a href="{{ route('admin.users.show', $report->target_id) }}" target="_blank">View User #{{ $report->target_id }}</a>
                                        @elseif($report->type == 'challenge')
                                            <a href="{{ route('challenges.show', $report->target_id) }}" target="_blank">View Art #{{ $report->target_id }}</a>
                                        @else
                                            <span>{{ ucfirst($report->type) }} ID: {{ $report->target_id }}</span>
                                        @endif
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($report->created_at)->format('M d') }}</td>
                                    <td>
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-outline-dark dropdown-toggle" type="button" data-bs-toggle="dropdown">Action</button>
                                            <ul class="dropdown-menu">
                                                <li>
                                                    <form action="{{ route('admin.reports.update') }}" method="POST">
                                                        @csrf
                                                        <input type="hidden" name="report_id" value="{{ $report->report_id }}">
                                                        <button name="status" value="resolved" class="dropdown-item text-success"><i class="bi bi-check-circle me-2"></i>Resolve</button>
                                                    </form>
                                                </li>
                                                <li>
                                                    <form action="{{ route('admin.reports.update') }}" method="POST">
                                                        @csrf
                                                        <input type="hidden" name="report_id" value="{{ $report->report_id }}">
                                                        <button name="status" value="dismissed" class="dropdown-item text-secondary"><i class="bi bi-x-circle me-2"></i>Dismiss</button>
                                                    </form>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="text-center py-5 text-muted"><i class="bi bi-check-circle fs-1 d-block mb-2"></i>No pending reports!</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
