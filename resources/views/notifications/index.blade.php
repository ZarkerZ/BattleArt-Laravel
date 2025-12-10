<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifications - BattleArt</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
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

        /* --- NOTIFICATION CARD --- */
        .notification-card {
            background-color: #ffffff;
            border-radius: var(--card-radius);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            border: 1px solid rgba(0,0,0,0.05);
            padding: 2rem;
            max-width: 800px;
            margin: 0 auto;
        }

        /* --- NOTIFICATION LIST ITEM --- */
        .notification-item {
            position: relative;
            border-bottom: 1px solid #f1f5f9;
            transition: background-color 0.2s;
            padding: 1.25rem 1rem;
            border-radius: 8px; /* Slight radius for hover effect */
        }

        .notification-item:hover {
            background-color: #f8f9fa;
        }

        .notification-item a {
            text-decoration: none;
            color: inherit;
            display: block; /* Make whole area clickable */
        }

        /* Unread State */
        .notification-item.unread {
            background-color: #f0f7ff; /* Very light blue tint */
            border-left: 4px solid var(--primary-color);
        }

        .notification-item .btn-close {
            position: absolute;
            top: 1rem;
            right: 1rem;
            z-index: 10;
            opacity: 0.3;
            transition: opacity 0.2s;
        }

        .notification-item:hover .btn-close {
            opacity: 0.8;
        }

        /* --- BUTTONS --- */
        .btn-outline-secondary-custom {
            border: 1px solid #e2e8f0;
            color: var(--text-muted);
            font-size: 0.85rem;
            font-weight: 600;
            border-radius: 50px;
            padding: 0.4rem 1rem;
            transition: all 0.2s;
        }

        .btn-outline-secondary-custom:hover {
            background-color: #f1f5f9;
            color: var(--text-dark);
            border-color: #cbd5e1;
        }

        .btn-outline-danger-custom {
            border: 1px solid #fee2e2;
            color: #ef4444;
            font-size: 0.85rem;
            font-weight: 600;
            border-radius: 50px;
            padding: 0.4rem 1rem;
            transition: all 0.2s;
        }

        .btn-outline-danger-custom:hover {
            background-color: #fef2f2;
            color: #dc2626;
            border-color: #fca5a5;
        }

        /* --- ICON STYLES --- */
        .notification-icon-wrapper {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #f8f9fa;
            margin-right: 1rem;
            flex-shrink: 0;
        }
    </style>
</head>

<body>
    @include('partials.navbar')

    <div class="container py-5">
        <div class="notification-card">
            <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
                <h3 class="fw-bold text-dark mb-0">
                    <i class="fas fa-bell me-2" style="color: var(--primary-color);"></i> Notifications
                </h3>
                <div class="d-flex gap-2">
                    <button id="deleteAllBtn" type="button" class="btn btn-outline-danger-custom">Delete All</button>
                    <a href="{{ route('notifications.read') }}" class="btn btn-outline-secondary-custom">Mark All as Read</a>
                </div>
            </div>

            <div class="list-group list-group-flush">
                @forelse ($notifications as $notification)
                    @php
                        $iconClass = '';
                        $iconColor = '';
                        $text = '';
                        $unreadClass = $notification->is_read == 0 ? 'unread' : '';

                        // Determine link destination logic (unchanged)
                        $link_challenge_id = $notification->target_id;
                        if ($notification->type == 'interpretation_like') {
                            $link_challenge_id = $notification->target_parent_id;
                        }
                        $destination = url('/challenge/' . $link_challenge_id);
                    @endphp

                    @switch($notification->type)
                        @case('like')
                            @php
                                $iconClass = 'fas fa-heart';
                                $iconColor = 'text-danger';
                                $text = "<strong>" . e($notification->sender_name) . "</strong> liked your challenge: <span class='text-primary'>" . e($notification->challenge_name) . "</span>";
                            @endphp
                            @break
                        @case('comment')
                            @php
                                $iconClass = 'fas fa-comment';
                                $iconColor = 'text-primary';
                                $text = "<strong>" . e($notification->sender_name) . "</strong> commented on your challenge: <span class='text-primary'>" . e($notification->challenge_name) . "</span>";
                            @endphp
                            @break
                        @case('interpretation')
                            @php
                                $iconClass = 'fas fa-paint-brush';
                                $iconColor = 'text-success';
                                $text = "<strong>" . e($notification->sender_name) . "</strong> submitted an interpretation for your challenge: <span class='text-primary'>" . e($notification->challenge_name) . "</span>";
                            @endphp
                            @break
                        @case('interpretation_like')
                            @php
                                $iconClass = 'fas fa-heart';
                                $iconColor = 'text-danger';
                                $text = "<strong>" . e($notification->sender_name) . "</strong> liked your interpretation on the challenge: <span class='text-primary'>" . e($notification->challenge_name) . "</span>";
                            @endphp
                            @break
                        @case('challenge_update')
                            @php
                                $iconClass = 'fas fa-info-circle';
                                $iconColor = 'text-info';
                                $text = "The challenge <span class='text-primary'>" . e($notification->challenge_name) . "</span> has been updated by the author.";
                            @endphp
                            @break
                    @endswitch

                    <div class="notification-item {{ $unreadClass }}">
                        <a href="{{ route('notifications.read', ['id' => $notification->notification_id, 'destination' => $destination]) }}" class="d-flex align-items-start pe-4">

                            <div class="notification-icon-wrapper">
                                <i class="{{ $iconClass }} {{ $iconColor }} fa-lg"></i>
                            </div>

                            <div class="flex-grow-1 pt-1">
                                <div class="text-dark mb-1" style="font-size: 0.95rem; line-height: 1.4;">
                                    {!! $text !!}
                                </div>
                                <small class="text-muted fw-semibold" style="font-size: 0.8rem;">
                                    <i class="far fa-clock me-1"></i> {{ \Carbon\Carbon::parse($notification->created_at)->diffForHumans() }}
                                </small>
                            </div>
                        </a>
                        <button class="btn-close delete-notification-btn" data-id="{{ $notification->notification_id }}" aria-label="Delete"></button>
                    </div>

                @empty
                    <div class="text-center text-muted py-5">
                        <div class="mb-3 p-3 rounded-circle bg-light d-inline-block">
                            <i class="fas fa-bell-slash fa-2x text-secondary"></i>
                        </div>
                        <p class="mb-0">You have no notifications yet.</p>
                    </div>
                @endforelse
            </div>

            <form id="deleteForm" action="{{ route('notifications.delete') }}" method="post" style="display: none;">
                @csrf
                <input type="hidden" name="action" id="deleteAction">
                <input type="hidden" name="notification_id" id="deleteNotificationId">
            </form>
        </div>
    </div>

    <div id="message-container"></div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function showConfirmationModal(title, message, confirmText, confirmVariant, callback) {
            const container = document.getElementById('message-container');
            const modalId = 'confirmModal';
            if (document.getElementById(modalId)) document.getElementById(modalId).remove();

            container.innerHTML = `
                <div class="modal fade" id="${modalId}" tabindex="-1">
                    <div class="modal-dialog modal-sm modal-dialog-centered">
                        <div class="modal-content rounded-4 shadow-lg border-0">
                            <div class="modal-header border-0 pb-0">
                                <h5 class="modal-title text-${confirmVariant} fw-bold">${title}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body pt-2 pb-4 text-center">
                                <p class="text-muted mb-0">${message}</p>
                            </div>
                            <div class="modal-footer border-0 pt-0 px-4 pb-4 d-grid gap-2 d-flex justify-content-center">
                                <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                                <button type="button" id="confirmActionBtn" class="btn btn-${confirmVariant} rounded-pill px-4">${confirmText}</button>
                            </div>
                        </div>
                    </div>
                </div>`;

            const modalElement = document.getElementById(modalId);
            const modal = new bootstrap.Modal(modalElement);
            modal.show();

            document.getElementById('confirmActionBtn').onclick = () => {
                modal.hide();
                callback();
            };
            modalElement.addEventListener('hidden.bs.modal', () => modalElement.remove());
        }

        document.addEventListener('DOMContentLoaded', () => {
            const deleteForm = document.getElementById('deleteForm');
            const deleteActionInput = document.getElementById('deleteAction');
            const deleteIdInput = document.getElementById('deleteNotificationId');

            // Handle "Delete All" button
            const deleteAllBtn = document.getElementById('deleteAllBtn');
            if (deleteAllBtn) {
                deleteAllBtn.addEventListener('click', () => {
                    showConfirmationModal('Delete All?', 'This will permanently remove all your notifications.', 'Delete All', 'danger', () => {
                        deleteActionInput.value = 'all';
                        deleteForm.submit();
                    });
                });
            }

            // Handle individual delete buttons
            document.querySelectorAll('.delete-notification-btn').forEach(button => {
                button.addEventListener('click', (e) => {
                    e.stopPropagation();
                    const notificationId = e.target.dataset.id;
                    showConfirmationModal('Delete?', 'Remove this notification?', 'Delete', 'danger', () => {
                        deleteActionInput.value = 'single';
                        deleteIdInput.value = notificationId;
                        deleteForm.submit();
                    });
                });
            });
        });
    </script>
</body>
</html>
