<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Comments - BattleArt Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="{{ asset('assets/css/navbar.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <style>
        :root {
            --primary-bg: #8c76ec;
            --secondary-bg: #a6e7ff;
            --light-purple: #c3b4fc;
            --dark-purple-border: #7b68ee;
            --text-dark: #333;
        }

        body {
            background-color: var(--secondary-bg);
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            padding-top: 20px;
        }

        .dashboard-container {
            max-width: 1100px;
            margin: 3rem auto;
            background: #fff;
            padding: 2.5rem;
            border-radius: 20px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.12);
        }

        h2 {
            color: var(--primary-bg);
            font-weight: bold;
            margin-bottom: 1.5rem;
            text-align: center;
        }

        .filter-section {
            display: flex;
            justify-content: flex-start;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .table-responsive {
            border-radius: 12px;
            overflow: hidden;
        }

        .table thead th {
            background-color: var(--light-purple);
            color: #fff;
            border: none;
            text-align: center;
            font-weight: 600;
        }

        .table tbody td {
            text-align: center;
            vertical-align: middle;
            color: var(--text-dark);
        }

        .table tbody tr:hover {
            background-color: #f9f9ff;
        }

        .search-bar {
            width: 280px;
        }

        /* Button & Badge Styles */
        .btn-archive {
            background-color: #6c757d;
            color: white;
            border: none;
            border-radius: 20px;
            font-size: 0.85rem;
            transition: 0.3s;
        }
        .btn-archive:hover { background-color: #5a6268; color: white; }

        .btn-restore {
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 20px;
            font-size: 0.85rem;
            transition: 0.3s;
        }
        .btn-restore:hover { background-color: #218838; color: white; }

        .status-archived {
            background-color: #6c757d;
            color: white;
            font-size: 0.7em;
            padding: 2px 6px;
            border-radius: 4px;
            margin-left: 5px;
            vertical-align: middle;
        }

        @media (max-width: 768px) {
            .dashboard-container { margin: 1.5rem; padding: 1.5rem; }
            .search-bar { width: 100%; margin-bottom: 1rem; }
        }
    </style>
</head>

<body>
    @include('partials.navbar')

    <div class="dashboard-container">
        <h2><i class="bi bi-chat-dots me-2"></i>Manage Comments</h2>

        <div class="filter-section">
            <input type="text" id="searchInput" class="form-control search-bar" placeholder="Search by user or artwork...">
        </div>

        <div class="table-responsive">
            <table class="table table-hover table-bordered" id="commentTable">
                <thead>
                    <tr>
                        <th>Artwork</th>
                        <th>Username</th>
                        <th>Comment</th>
                        <th>Date Posted</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($comments as $comment)
                        <tr style="opacity: {{ $comment->status === 'archived' ? '0.6' : '1' }}">
                            <td>{{ $comment->challenge_name }}</td>
                            <td>{{ $comment->user_userName }}</td>
                            <td style="text-align: left;">
                                {{ $comment->comment_text }}
                                @if($comment->status === 'archived')
                                    <span class="badge bg-secondary ms-1">Archived</span>
                                @endif
                            </td>
                            <td>{{ \Carbon\Carbon::parse($comment->created_at)->format('M d, Y') }}</td>
                            <td>
                                <form action="{{ route('admin.comments.archive') }}" method="POST" id="form_{{ $comment->comment_id }}">
                                    @csrf
                                    <input type="hidden" name="comment_id" value="{{ $comment->comment_id }}">

                                    @if($comment->status === 'archived')
                                        <button type="button" class="btn btn-restore btn-sm trigger-restore" data-form-id="form_{{ $comment->comment_id }}">
                                            <i class="bi bi-arrow-counterclockwise me-1"></i> Restore
                                        </button>
                                    @else
                                        <button type="button" class="btn btn-archive btn-sm trigger-archive" data-form-id="form_{{ $comment->comment_id }}">
                                            <i class="bi bi-archive me-1"></i> Archive
                                        </button>
                                    @endif
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">
                                <i class="bi bi-inbox" style="font-size: 2rem; color: #ccc;"></i><br>
                                No comments found
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div id="message-container"></div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Search Filter Logic
        document.getElementById('searchInput').addEventListener('input', function() {
            const filter = this.value.toLowerCase();
            const rows = document.querySelectorAll('#commentTable tbody tr');
            rows.forEach(row => {
                if(row.cells.length > 1) { // Skip "No results" row
                    const text = row.innerText.toLowerCase();
                    row.style.display = text.includes(filter) ? '' : 'none';
                }
            });
        });

        // Modal Logic
        function showConfirmationModal(title, message, confirmText, confirmVariant, formId) {
            const container = document.getElementById('message-container');
            const modalId = 'confirmModal';
            if (document.getElementById(modalId)) document.getElementById(modalId).remove();

            container.innerHTML = `
            <div class="modal fade" id="${modalId}" tabindex="-1">
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

            const modalElement = document.getElementById(modalId);
            const modal = new bootstrap.Modal(modalElement);
            modal.show();

            document.getElementById('confirmActionBtn').onclick = () => {
                document.getElementById(formId).submit();
            };

            modalElement.addEventListener('hidden.bs.modal', () => modalElement.remove());
        }

        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('.trigger-archive').forEach(btn => {
                btn.addEventListener('click', function() {
                    showConfirmationModal('Archive Comment', 'This comment will be hidden from the public.', 'Archive', 'secondary', this.dataset.formId);
                });
            });

            document.querySelectorAll('.trigger-restore').forEach(btn => {
                btn.addEventListener('click', function() {
                    showConfirmationModal('Restore Comment', 'This comment will be visible again.', 'Restore', 'success', this.dataset.formId);
                });
            });
        });
    </script>
</body>
</html>
