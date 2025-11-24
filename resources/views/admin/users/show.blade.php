<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage User - {{ $user->user_userName }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="{{ asset('assets/css/navbar.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        :root { --primary-bg: #8c76ec; --secondary-bg: #a6e7ff; --light-purple: #c3b4fc; }
        body { background-color: var(--secondary-bg); padding-top: 30px; font-family: 'Inter', sans-serif; }
        .user-stats { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 2rem; }
        .stat-card { background: var(--light-purple); padding: 1.5rem; border-radius: 10px; text-align: center; }
        .stat-card h3 { font-size: 2rem; color: var(--primary-bg); margin-bottom: 0.5rem; }
        .stat-card p { margin-bottom: 0; }
        .activity-item { padding: 1rem; border-bottom: 1px solid #eee; }
        .activity-item:last-child { border-bottom: none; }

        /* Status Badges */
        .status-badge { padding: 0.5rem 1rem; border-radius: 20px; font-weight: 600; }
        .status-active { background-color: #28a745; color: white; }
        .status-banned { background-color: #dc3545; color: white; }
        .status-archived { background-color: #6c757d; color: white; }
    </style>
</head>
<body>
    @include('partials.navbar')

    <div class="container mt-4">
        <div class="card">
            <div class="card-body p-4">
                <div class="d-flex flex-wrap align-items-center mb-4">
                    <img src="{{ $user->user_profile_pic ? asset('assets/uploads/'.$user->user_profile_pic) : asset('assets/images/blank-profile-picture.png') }}"
                         alt="User Avatar" class="rounded-circle me-3" width="100" height="100" style="object-fit: cover;"
                         onerror="this.onerror=null; this.src='{{ asset('assets/images/blank-profile-picture.png') }}';">

                    <div>
                        <h2 class="mb-1">{{ $user->user_userName }}</h2>
                        <p class="text-muted mb-2"><i class="bi bi-envelope"></i> {{ $user->user_email }}</p>

                        @if($user->account_status === 'banned')
                            <span class="status-badge status-banned">Banned</span>
                        @elseif($user->account_status === 'archived')
                            <span class="status-badge status-archived">Archived</span>
                        @else
                            <span class="status-badge status-active">Active</span>
                        @endif
                    </div>

                    <div class="ms-auto mt-3 mt-md-0">
                        <form action="{{ route('admin.users.status', $user->user_id) }}" method="POST" class="d-inline" id="statusForm">
                            @csrf

                            @if($user->account_status === 'banned')
                                <button type="button" id="unbanUserBtn" class="btn btn-success me-2"><i class="bi bi-check-circle"></i> Unban User</button>
                            @elseif($user->account_status === 'archived')
                                <button type="button" id="restoreUserBtn" class="btn btn-primary me-2"><i class="bi bi-box-arrow-up"></i> Restore User</button>
                            @else
                                <button type="button" id="banUserBtn" class="btn btn-warning me-2"><i class="bi bi-slash-circle"></i> Ban User</button>
                            @endif

                            @if($user->user_id != $admin_user_id && $user->account_status !== 'archived')
                                <button type="button" id="archiveUserBtn" class="btn btn-secondary">
                                    <i class="bi bi-archive"></i> Archive User
                                </button>
                            @endif

                            <input type="hidden" name="action" id="formActionInput">
                        </form>
                    </div>
                </div>

                <div class="user-stats">
                    <div class="stat-card"><h3>{{ $artwork_count }}</h3><p>Artworks</p></div>
                    <div class="stat-card"><h3>{{ $interpretation_count }}</h3><p>Interpretations</p></div>
                    <div class="stat-card"><h3>{{ $comment_count }}</h3><p>Comments</p></div>
                    <div class="stat-card"><h3>{{ $like_count }}</h3><p>Likes Given</p></div>
                </div>

                <h4 class="mb-3">Recent Activity</h4>
                <div class="card"><div class="card-body p-0">
                    @forelse($activities as $activity)
                        <div class="activity-item"><div class="d-flex align-items-center">
                            @php
                                $icon = 'chat-dots';
                                if ($activity->type === 'artwork') $icon = 'palette';
                                elseif ($activity->type === 'interpretation') $icon = 'brush';
                            @endphp
                            <i class="bi bi-{{ $icon }} me-2 fs-4" style="color: var(--primary-bg);"></i>
                            <div>
                                <strong>{{ ucfirst($activity->type) }}</strong>: {{ Str::limit($activity->title, 100) }}
                                <br><small class="text-muted">{{ \Carbon\Carbon::parse($activity->date)->diffForHumans() }}</small>
                            </div>
                        </div></div>
                    @empty
                        <p class="text-center py-4 text-muted">No recent activity</p>
                    @endforelse
                </div></div>
            </div>
        </div>
    </div>

    <div id="message-container"></div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function showConfirmationModal(title, message, confirmText, confirmVariant, actionValue) {
            const container = document.getElementById('message-container');
            container.innerHTML = `
            <div class="modal fade" id="confirmModal" tabindex="-1">
                <div class="modal-dialog modal-sm modal-dialog-centered">
                    <div class="modal-content rounded-4 shadow-lg">
                        <div class="modal-header border-0 pb-0"><h5 class="modal-title text-${confirmVariant}">${title}</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                        <div class="modal-body pt-2 pb-4"><p class="text-muted">${message}</p></div>
                        <div class="modal-footer border-0 pt-0 d-flex justify-content-between">
                            <button type="button" class="btn btn-outline-secondary rounded-pill flex-grow-1 me-2" data-bs-dismiss="modal">Cancel</button>
                            <button type="button" id="confirmActionBtn" class="btn btn-${confirmVariant} rounded-pill flex-grow-1">${confirmText}</button>
                        </div>
                    </div>
                </div>
            </div>`;
            const modal = new bootstrap.Modal(document.getElementById('confirmModal'));
            modal.show();

            document.getElementById('confirmActionBtn').onclick = () => {
                document.getElementById('formActionInput').value = actionValue;
                document.getElementById('statusForm').submit();
            };
        }

        document.addEventListener('DOMContentLoaded', () => {
            const banBtn = document.getElementById('banUserBtn');
            const unbanBtn = document.getElementById('unbanUserBtn');
            const restoreBtn = document.getElementById('restoreUserBtn');
            const archiveBtn = document.getElementById('archiveUserBtn');

            if (banBtn) {
                banBtn.addEventListener('click', () => {
                    showConfirmationModal('Ban User', 'User will be unable to login.', 'Ban User', 'warning', 'ban');
                });
            }
            if (unbanBtn) {
                unbanBtn.addEventListener('click', () => {
                    showConfirmationModal('Unban User', 'Restore access for this user?', 'Unban', 'success', 'unban');
                });
            }
            if (restoreBtn) {
                restoreBtn.addEventListener('click', () => {
                    showConfirmationModal('Restore User', 'User will be visible to the public again.', 'Restore', 'primary', 'restore');
                });
            }
            if (archiveBtn) {
                archiveBtn.addEventListener('click', () => {
                    showConfirmationModal('Archive User', 'User data remains but is hidden from public.', 'Archive', 'secondary', 'archive');
                });
            }
        });
    </script>
</body>
</html>
