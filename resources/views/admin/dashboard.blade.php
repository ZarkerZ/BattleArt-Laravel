<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - BattleArt</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <link href="{{ asset('assets/css/navbar.css') }}" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/images/doro.ico') }}">

    <style>
        :root {
            /* --- DESIGN SYSTEM VARIABLES --- */
            --primary-color: #5a4bda;       /* Deep Royal Purple */
            --primary-hover: #4335b8;
            --secondary-color: #f8f9fa;     /* Clean Light Gray Background */
            --text-dark: #1e293b;           /* Slate Dark */
            --text-muted: #64748b;          /* Slate Gray */
            --card-radius: 16px;

            /* Navbar Fix Variables */
            --primary-bg: #5a4bda;
        }

        body {
            background-color: var(--secondary-color);
            font-family: 'Inter', sans-serif;
            color: var(--text-dark);
            padding-top: 80px; /* Space for fixed navbar */
            min-height: 100vh;
        }

        /* --- FORCE NAVBAR PURPLE --- */
        .custom-navbar {
            background-color: var(--primary-color) !important;
            backdrop-filter: none;
            -webkit-backdrop-filter: none;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        /* --- DASHBOARD CONTAINER --- */
        .dashboard-container {
            max-width: 1200px;
            margin: 2rem auto;
            background: #fff;
            padding: 2.5rem;
            border-radius: var(--card-radius);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            border: 1px solid rgba(0,0,0,0.05);
        }

        /* --- STAT CARDS --- */
        .stat-card {
            background: #fff;
            padding: 1.5rem;
            border-radius: 12px;
            text-align: center;
            border: 1px solid #e2e8f0;
            box-shadow: 0 4px 6px rgba(0,0,0,0.02);
            transition: all 0.3s ease;
            height: 100%;
        }

        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(90, 75, 218, 0.1);
            border-color: var(--primary-color);
        }

        .stat-card h4 {
            font-size: 2.5rem;
            font-weight: 800;
            color: var(--primary-color);
            margin-bottom: 0.5rem;
        }

        .stat-card p {
            color: var(--text-muted);
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.85rem;
            margin-bottom: 0;
            letter-spacing: 0.5px;
        }

        /* --- TABS --- */
        .nav-pills .nav-link {
            color: var(--text-muted);
            font-weight: 600;
            border-radius: 50px;
            padding: 0.6rem 1.5rem;
            margin-right: 0.5rem;
            transition: all 0.2s ease;
        }

        .nav-pills .nav-link.active {
            background-color: var(--primary-color);
            color: #fff;
            box-shadow: 0 4px 10px rgba(90, 75, 218, 0.3);
        }

        .nav-pills .nav-link:hover:not(.active) {
            background-color: #f1f5f9;
            color: var(--primary-color);
        }

        /* --- TABLE STYLING --- */
        .table-hover tbody tr:hover {
            background-color: #f8f9fa;
        }

        .table th {
            font-weight: 600;
            color: var(--text-muted);
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.5px;
            border-bottom: 2px solid #f1f5f9;
            padding-bottom: 1rem;
        }

        .table td {
            vertical-align: middle;
            padding: 1rem 0.5rem;
            border-bottom: 1px solid #f1f5f9;
            color: var(--text-dark);
        }

        /* --- BADGES --- */
        .role-badge {
            padding: 0.4em 0.8em;
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
        }
        .role-admin { background-color: #e0e7ff; color: var(--primary-color); }
        .role-user { background-color: #f1f5f9; color: var(--text-muted); }

        .status-badge {
            padding: 0.4em 0.8em;
            border-radius: 50px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        .status-active { background-color: #d1fae5; color: #059669; }
        .status-banned { background-color: #fee2e2; color: #dc2626; }
        .status-archived { background-color: #f3f4f6; color: #6b7280; }

        /* --- BUTTONS --- */
        .btn-action {
            border-radius: 50px;
            font-size: 0.85rem;
            font-weight: 600;
            padding: 0.4rem 1rem;
        }
    </style>
</head>

<body>
    @include('partials.navbar')

    <div class="dashboard-container">
        <div class="text-center mb-5">
            <h2 class="fw-bold mb-2" style="color: var(--text-dark);">
                <i class="fas fa-tachometer-alt me-2" style="color: var(--primary-color);"></i> Admin Dashboard
            </h2>
            <p class="text-muted">Manage users, view statistics, and handle reports.</p>
        </div>

        <div class="row g-4 mb-5">
            <div class="col-md-4">
                <div class="stat-card">
                    <h4>{{ $total_users }}</h4>
                    <p>Total Users</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card">
                    <h4>{{ $total_admins }}</h4>
                    <p>Administrators</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card">
                    <h4 class="{{ $reports->count() > 0 ? 'text-danger' : '' }}">{{ $reports->count() }}</h4>
                    <p>Pending Reports</p>
                </div>
            </div>
        </div>

        <ul class="nav nav-pills mb-4 justify-content-center" id="pills-tab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="pills-users-tab" data-bs-toggle="pill" data-bs-target="#pills-users" type="button">
                    <i class="fas fa-users me-2"></i> Manage Users
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="pills-reports-tab" data-bs-toggle="pill" data-bs-target="#pills-reports" type="button">
                    <i class="fas fa-flag me-2"></i> Pending Reports
                    @if($reports->count() > 0)
                        <span class="badge bg-danger ms-2 rounded-pill">{{ $reports->count() }}</span>
                    @endif
                </button>
            </li>
        </ul>

        <div class="tab-content" id="pills-tabContent">

            <div class="tab-pane fade show active" id="pills-users" role="tabpanel">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th class="ps-3">User</th>
                                <th>Role</th>
                                <th>Status</th>
                                <th>Joined</th>
                                <th class="text-end pe-3">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($users as $user)
                                <tr>
                                    <td class="ps-3">
                                        <div class="d-flex align-items-center">
                                            <img src="{{ $user->user_profile_pic ? asset('assets/uploads/'.$user->user_profile_pic) : asset('assets/images/blank-profile-picture.png') }}"
                                                 class="rounded-circle me-3 border shadow-sm" width="40" height="40"
                                                 style="object-fit: cover;"
                                                 onerror="this.src='{{ asset('assets/images/blank-profile-picture.png') }}';">
                                            <div>
                                                <div class="fw-bold text-dark">{{ $user->user_userName }}</div>
                                                <div class="small text-muted">{{ $user->user_email }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="role-badge {{ $user->user_type === 'admin' ? 'role-admin' : 'role-user' }}">
                                            {{ ucfirst($user->user_type) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($user->account_status === 'banned')
                                            <span class="status-badge status-banned">Banned</span>
                                        @elseif($user->account_status === 'archived')
                                            <span class="status-badge status-archived">Archived</span>
                                        @else
                                            <span class="status-badge status-active">Active</span>
                                        @endif
                                    </td>
                                    <td class="text-muted small">
                                        {{ \Carbon\Carbon::parse($user->joined_date)->format('M d, Y') }}
                                    </td>
                                    <td class="text-end pe-3">
                                        <a href="{{ route('admin.users.show', $user->user_id) }}" class="btn btn-primary btn-action">
                                            Manage
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="text-center py-5 text-muted">No users found.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="tab-pane fade" id="pills-reports" role="tabpanel">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th class="ps-3">Reporter</th>
                                <th>Target</th>
                                <th>Reason</th>
                                <th>Date</th>
                                <th class="text-end pe-3">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($reports as $report)
                                <tr>
                                    <td class="ps-3">
                                        <div class="d-flex align-items-center">
                                            <img src="{{ $report->user_profile_pic ? asset('assets/uploads/'.$report->user_profile_pic) : asset('assets/images/blank-profile-picture.png') }}"
                                                 class="rounded-circle me-2 border" width="32" height="32"
                                                 style="object-fit: cover;"
                                                 onerror="this.src='{{ asset('assets/images/blank-profile-picture.png') }}';">
                                            <span class="small fw-bold text-dark">{{ $report->reporter_name }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        @if($report->type == 'user')
                                            <a href="{{ route('admin.users.show', $report->target_id) }}" class="text-decoration-none fw-semibold" target="_blank">
                                                <i class="fas fa-user me-1"></i> User #{{ $report->target_id }}
                                            </a>
                                        @elseif($report->type == 'challenge')
                                            <a href="{{ route('challenges.show', $report->target_id) }}" class="text-decoration-none fw-semibold" target="_blank">
                                                <i class="fas fa-palette me-1"></i> Art #{{ $report->target_id }}
                                            </a>
                                        @else
                                            <span class="badge bg-secondary">{{ ucfirst($report->type) }} #{{ $report->target_id }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="text-danger fw-bold d-block">{{ $report->reason }}</span>
                                        <small class="text-muted">{{ Str::limit($report->details, 40) }}</small>
                                    </td>
                                    <td class="text-muted small">
                                        {{ \Carbon\Carbon::parse($report->created_at)->format('M d') }}
                                    </td>
                                    <td class="text-end pe-3">
                                        <div class="dropdown">
                                            <button class="btn btn-outline-secondary btn-sm rounded-pill dropdown-toggle px-3" type="button" data-bs-toggle="dropdown">
                                                Action
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end border-0 shadow-sm">
                                                <li>
                                                    <form action="{{ route('admin.reports.update') }}" method="POST">
                                                        @csrf
                                                        <input type="hidden" name="report_id" value="{{ $report->report_id }}">
                                                        <button name="status" value="resolved" class="dropdown-item text-success fw-semibold">
                                                            <i class="fas fa-check me-2"></i> Resolve
                                                        </button>
                                                    </form>
                                                </li>
                                                <li>
                                                    <form action="{{ route('admin.reports.update') }}" method="POST">
                                                        @csrf
                                                        <input type="hidden" name="report_id" value="{{ $report->report_id }}">
                                                        <button name="status" value="dismissed" class="dropdown-item text-muted">
                                                            <i class="fas fa-times me-2"></i> Dismiss
                                                        </button>
                                                    </form>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5 text-muted">
                                        <i class="fas fa-check-circle fa-3x mb-3 text-success opacity-50"></i>
                                        <p class="mb-0">All clear! No pending reports.</p>
                                    </td>
                                </tr>
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
