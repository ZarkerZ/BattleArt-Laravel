<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage User - {{ $user->user_userName }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <link href="{{ asset('assets/css/navbar.css') }}" rel="stylesheet">

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

        /* --- CARDS --- */
        .admin-card {
            background-color: #ffffff;
            border-radius: var(--card-radius);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            border: 1px solid rgba(0,0,0,0.05);
            padding: 2.5rem;
        }

        /* --- STAT BOXES --- */
        .stat-box {
            background-color: #f8f9fa;
            border-radius: 12px;
            padding: 1.5rem;
            text-align: center;
            height: 100%;
            border: 1px solid #e2e8f0;
            transition: all 0.3s ease;
        }

        .stat-box:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            border-color: var(--primary-color);
        }

        .stat-value {
            font-size: 2rem;
            font-weight: 800;
            color: var(--primary-color);
            display: block;
            margin-bottom: 0.25rem;
        }

        .stat-label {
            color: var(--text-muted);
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.8rem;
            letter-spacing: 0.5px;
        }

        /* --- BUTTONS --- */
        .btn-warning-custom {
            background-color: #fbbf24;
            color: #fff;
            border: none;
            border-radius: 50px;
            padding: 0.5rem 1.5rem;
            font-weight: 600;
        }
        .btn-warning-custom:hover { background-color: #d97706; color: #fff; }

        .btn-success-custom {
            background-color: #10b981;
            color: #fff;
            border: none;
            border-radius: 50px;
            padding: 0.5rem 1.5rem;
            font-weight: 600;
        }
        .btn-success-custom:hover { background-color: #059669; color: #fff; }

        /* Secondary Button for Archive */
        .btn-secondary-custom {
            background-color: #64748b; /* Slate 500 */
            color: #fff;
            border: none;
            border-radius: 50px;
            padding: 0.5rem 1.5rem;
            font-weight: 600;
        }
        .btn-secondary-custom:hover { background-color: #475569; color: #fff; }

        /* Primary Button for Restore */
        .btn-primary-custom {
            background-color: var(--primary-color);
            color: #fff;
            border: none;
            border-radius: 50px;
            padding: 0.5rem 1.5rem;
            font-weight: 600;
        }
        .btn-primary-custom:hover { background-color: var(--primary-hover); color: #fff; }

        /* --- ACTIVITY LIST --- */
        .activity-item {
            padding: 1rem 0;
            border-bottom: 1px solid #f1f5f9;
        }
        .activity-item:last-child { border-bottom: none; }

        .activity-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
    </style>
</head>
<body>
    @include('partials.navbar')

    <div class="container my-5">
        <div class="admin-card mb-4">
            <div class="d-flex flex-column flex-md-row align-items-center mb-4">
                <img src="{{ $user->user_profile_pic ? asset('assets/uploads/'.$user->user_profile_pic) : asset('assets/images/blank-profile-picture.png') }}"
                     class="rounded-circle me-md-4 mb-3 mb-md-0 shadow-sm border"
                     width="100" height="100"
                     style="object-fit: cover;"
                     onerror="this.src='{{ asset('assets/images/blank-profile-picture.png') }}';">

                <div class="text-center text-md-start">
                    <h2 class="mb-1 fw-bold text-dark">{{ $user->user_userName }}</h2>
                    <p class="text-muted mb-2">{{ $user->user_email }}</p>
                    <span class="badge rounded-pill
                        @if($user->account_status === 'active') bg-success
                        @elseif($user->account_status === 'banned') bg-danger
                        @else bg-secondary @endif
                        px-3 py-2">
                        {{ ucfirst($user->account_status) }}
                    </span>
                </div>

                <div class="ms-md-auto mt-3 mt-md-0">
                    <form id="statusForm" action="{{ route('admin.users.status', $user->user_id) }}" method="POST" class="d-inline">
                        @csrf
                        <input type="hidden" name="action" id="formActionInput">

                        @if($user->account_status === 'active')
                            <button type="button" id="banUserBtn" class="btn btn-warning-custom">
                                <i class="fas fa-ban me-2"></i> Ban
                            </button>
                        @elseif($user->account_status === 'banned')
                            <button type="button" id="unbanUserBtn" class="btn btn-success-custom">
                                <i class="fas fa-check-circle me-2"></i> Unban
                            </button>
                        @endif

                        @if($user->account_status === 'archived')
                            <button type="button" id="restoreUserBtn" class="btn btn-primary-custom ms-2">
                                <i class="fas fa-box-open me-2"></i> Restore
                            </button>
                        @elseif($user->user_id != $admin_user_id)
                            <button type="button" id="archiveUserBtn" class="btn btn-secondary-custom ms-2">
                                <i class="fas fa-archive me-2"></i> Archive
                            </button>
                        @endif
                    </form>
                </div>
            </div>

            <div class="row g-4 mb-5">
                <div class="col-6 col-md-3">
                    <div class="stat-box">
                        <span class="stat-value">{{ $artwork_count }}</span>
                        <span class="stat-label">Artworks</span>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="stat-box">
                        <span class="stat-value">{{ $interpretation_count }}</span>
                        <span class="stat-label">Entries</span>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="stat-box">
                        <span class="stat-value">{{ $comment_count }}</span>
                        <span class="stat-label">Comments</span>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="stat-box">
                        <span class="stat-value">{{ $like_count }}</span>
                        <span class="stat-label">Likes Given</span>
                    </div>
                </div>
            </div>

            <h4 class="fw-bold mb-3 text-dark border-bottom pb-2">Recent Activity</h4>
            <div class="activity-list">
                @forelse($activities as $act)
                    @php
                        $iconClass = 'fas fa-history'; // Default
                        $iconColor = 'text-muted';

                        if ($act->type === 'artwork' || $act->type === 'challenge') {
                            $iconClass = 'fas fa-palette';
                            $iconColor = 'text-primary';
                        } elseif ($act->type === 'interpretation') {
                            $iconClass = 'fas fa-paint-brush';
                            $iconColor = 'text-success';
                        } elseif ($act->type === 'comment') {
                            $iconClass = 'fas fa-comment';
                            $iconColor = 'text-info';
                        } elseif ($act->type === 'like') {
                            $iconClass = 'fas fa-heart';
                            $iconColor = 'text-danger';
                        }
                    @endphp

                    <div class="activity-item">
                        <div class="d-flex align-items-center">
                            <div class="activity-icon me-3">
                                <i class="{{ $iconClass }} {{ $iconColor }} fs-5"></i>
                            </div>

                            <div class="flex-grow-1">
                                <span class="fw-bold text-dark">{{ ucfirst($act->type) }}:</span>
                                <span class="text-muted">{{ Str::limit($act->title, 80) }}</span>
                            </div>

                            <small class="text-muted ms-3" style="white-space: nowrap;">
                                {{ \Carbon\Carbon::parse($act->date)->diffForHumans() }}
                            </small>
                        </div>
                    </div>
                @empty
                    <div class="text-center text-muted py-4">
                        <i class="fas fa-history fa-2x mb-2 opacity-50"></i>
                        <p>No recent activity found.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <div id="message-container"></div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function showConfirmationModal(title, message, confirmText, confirmVariant, actionValue) {
            const container = document.getElementById('message-container');

            const modalHTML = `
            <div class="modal fade" id="confirmModal" tabindex="-1">
                <div class="modal-dialog modal-sm modal-dialog-centered">
                    <div class="modal-content rounded-4 border-0 shadow-lg">
                        <div class="modal-header border-0 pb-0">
                            <h5 class="modal-title fw-bold text-${confirmVariant}">${title}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body pt-2 pb-4 text-center">
                            <p class="text-muted mb-0">${message}</p>
                        </div>
                        <div class="modal-footer border-0 pt-0 justify-content-center pb-4">
                            <button type="button" class="btn btn-light rounded-pill px-4 me-2" data-bs-dismiss="modal">Cancel</button>
                            <button type="button" id="confirmActionBtn" class="btn btn-${confirmVariant} rounded-pill px-4 text-white">${confirmText}</button>
                        </div>
                    </div>
                </div>
            </div>`;

            container.innerHTML = modalHTML;

            const modalElement = document.getElementById('confirmModal');
            const modal = new bootstrap.Modal(modalElement);
            modal.show();

            document.getElementById('confirmActionBtn').onclick = () => {
                document.getElementById('formActionInput').value = actionValue;
                document.getElementById('statusForm').submit();
                modal.hide();
            };
        }

        document.addEventListener('DOMContentLoaded', () => {
            const banBtn = document.getElementById('banUserBtn');
            const unbanBtn = document.getElementById('unbanUserBtn');
            const archiveBtn = document.getElementById('archiveUserBtn');
            const restoreBtn = document.getElementById('restoreUserBtn');

            if (banBtn) {
                banBtn.addEventListener('click', () => {
                    showConfirmationModal('Ban User', 'This user will lose access to their account.', 'Ban', 'warning', 'ban');
                });
            }

            if (unbanBtn) {
                unbanBtn.addEventListener('click', () => {
                    showConfirmationModal('Unban User', 'Restore access for this user?', 'Unban', 'success', 'unban');
                });
            }

            if (archiveBtn) {
                archiveBtn.addEventListener('click', () => {
                    showConfirmationModal('Archive User', 'This user will be hidden from public view but not deleted.', 'Archive', 'secondary', 'archive');
                });
            }

            if (restoreBtn) {
                restoreBtn.addEventListener('click', () => {
                    showConfirmationModal('Restore User', 'Make this user visible again?', 'Restore', 'primary', 'restore');
                });
            }
        });
    </script>
</body>
</html>
