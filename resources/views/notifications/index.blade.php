<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifications - BattleArt</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('assets/css/navbar.css') }}">
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/images/doro.ico') }}">
    <style>
        :root {
            --primary-bg: #8c76ec;
            --secondary-bg: #a6e7ff;
            --light-purple: #c3b4fc;
            --text-dark: #333;
        }

        body {
            background-image: linear-gradient(to bottom, #a6e7ff, #c3b4fc);
            background-repeat: no-repeat;
            background-attachment: fixed;
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            padding-top: 80px;
        }

        .notification-item a {
            text-decoration: none;
            color: inherit;
        }

        .notification-item.unread {
            background-color: #f7f7ff;
        }

        .notification-item {
            position: relative;
        }

        .notification-item .btn-close {
            position: absolute;
            top: 0.5rem;
            right: 0.5rem;
            z-index: 10;
        }
    </style>
</head>

<body>
    @include('partials.navbar')

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card p-4 shadow-lg">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h3 class="fw-bold text-dark mb-0">🔔 Notifications</h3>
                        <div class="btn-group">
                            <button id="deleteAllBtn" type="button" class="btn btn-sm btn-outline-danger">Delete All</button>
                            <a href="{{ route('notifications.read') }}" class="btn btn-sm btn-outline-secondary">Mark All as Read</a>
                        </div>
                    </div>

                    <div class="list-group list-group-flush">
                        @forelse ($notifications as $notification)
                            @php
                                $icon = '';
                                $text = '';
                                $unreadClass = $notification->is_read == 0 ? 'unread' : '';

                                // Determine link destination
                                $link_challenge_id = $notification->target_id; 
                                if ($notification->type == 'interpretation_like') {
                                    $link_challenge_id = $notification->target_parent_id;
                                }
                                // Construct the destination URL
                                // Assuming we will create a route: Route::get('/challenge/{id}', ... )
                                $destination = url('/challenge/' . $link_challenge_id);
                            @endphp

                            @switch($notification->type)
                                @case('like')
                                    @php
                                        $icon = 'fas fa-heart text-danger';
                                        $text = "<strong>" . e($notification->sender_name) . "</strong> liked your challenge: <strong>" . e($notification->challenge_name) . "</strong>";
                                    @endphp
                                    @break
                                @case('comment')
                                    @php
                                        $icon = 'fas fa-comment text-primary';
                                        $text = "<strong>" . e($notification->sender_name) . "</strong> commented on your challenge: <strong>" . e($notification->challenge_name) . "</strong>";
                                    @endphp
                                    @break
                                @case('interpretation')
                                    @php
                                        $icon = 'fas fa-paint-brush text-success';
                                        $text = "<strong>" . e($notification->sender_name) . "</strong> submitted an interpretation for your challenge: <strong>" . e($notification->challenge_name) . "</strong>";
                                    @endphp
                                    @break
                                @case('interpretation_like')
                                    @php
                                        $icon = 'fas fa-heart text-danger';
                                        $text = "<strong>" . e($notification->sender_name) . "</strong> liked your interpretation on the challenge: <strong>" . e($notification->challenge_name) . "</strong>";
                                    @endphp
                                    @break
                                @case('challenge_update')
                                    @php
                                        $icon = 'fas fa-info-circle text-info';
                                        $text = "The challenge <strong>" . e($notification->challenge_name) . "</strong> has been updated by the author.";
                                    @endphp
                                    @break
                            @endswitch

                            <div class="list-group-item notification-item {{ $unreadClass }} p-3">
                                <a href="{{ route('notifications.read', ['id' => $notification->notification_id, 'destination' => $destination]) }}" class="d-flex align-items-center">
                                    <i class="{{ $icon }} fa-lg me-3" style="width: 20px;"></i>
                                    <div class="flex-grow-1">
                                        <div class="fw-semibold">{!! $text !!}</div>
                                        <small class="text-muted">
                                            {{ \Carbon\Carbon::parse($notification->created_at)->diffForHumans() }}
                                        </small>
                                    </div>
                                </a>
                                <button class="btn-close delete-notification-btn" data-id="{{ $notification->notification_id }}" aria-label="Delete"></button>
                            </div>

                        @empty
                            <div class="text-center text-muted p-5">
                                <i class="fas fa-bell-slash fa-3x mb-3"></i>
                                <p>You have no notifications yet.</p>
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
                        <div class="modal-content rounded-4 shadow-lg">
                            <div class="modal-header border-0 pb-0">
                                <h5 class="modal-title text-${confirmVariant}">${title}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body pt-2 pb-4"><p class="text-muted">${message}</p></div>
                            <div class="modal-footer border-0 pt-0 d-flex justify-content-between">
                                <button type="button" class="btn btn-outline-secondary rounded-pill flex-grow-1 me-2" data-bs-dismiss="modal">Cancel</button>
                                <button type="button" id="confirmActionBtn" class="btn btn-${confirmVariant} rounded-pill flex-grow-1">${confirmText}</button>
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
                    showConfirmationModal('Delete All Notifications', 'Are you sure you want to permanently delete all notifications?', 'Delete All', 'danger', () => {
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
                    showConfirmationModal('Delete Notification', 'Are you sure you want to delete this notification?', 'Delete', 'danger', () => {
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