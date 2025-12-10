<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Comments - BattleArt Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <link href="{{ asset('assets/css/navbar.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">

    <style>
        :root {
            /* --- DESIGN SYSTEM VARIABLES --- */
            --primary-color: #5a4bda;       /* Deep Royal Purple */
            --primary-hover: #4335b8;
            --secondary-color: #f8f9fa;     /* Clean Light Gray Background */
            --text-dark: #1e293b;           /* Slate Dark */
            --text-muted: #64748b;          /* Slate Gray */
            --card-radius: 16px;
            --primary-bg: #5a4bda;
        }

        body {
            background-color: var(--secondary-color);
            font-family: 'Inter', sans-serif;
            color: var(--text-dark);
            padding-top: 80px;
            min-height: 100vh;
        }

        .custom-navbar {
            background-color: var(--primary-color) !important;
            backdrop-filter: none;
            -webkit-backdrop-filter: none;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .dashboard-container {
            max-width: 1200px;
            margin: 2rem auto;
            background: #fff;
            padding: 2.5rem;
            border-radius: var(--card-radius);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            border: 1px solid rgba(0,0,0,0.05);
        }

        /* --- FILTERS --- */
        .filter-section {
            background: #f8f9fa;
            padding: 1.5rem;
            border-radius: 12px;
            margin-bottom: 2rem;
            border: 1px solid #e2e8f0;
        }

        .form-control {
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            padding: 0.7rem 1rem;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(90, 75, 218, 0.1);
        }

        /* --- BADGES & BUTTONS --- */
        .badge-archived {
            background-color: #e2e8f0;
            color: var(--text-muted);
            font-size: 0.7em;
            padding: 0.3em 0.6em;
            border-radius: 4px;
            margin-left: 5px;
            vertical-align: middle;
            text-transform: uppercase;
            font-weight: 700;
        }

        .btn-archive {
            background-color: #f1f5f9;
            color: var(--text-muted);
            border: 1px solid #e2e8f0;
            border-radius: 50px;
            padding: 0.4rem 1rem;
            font-size: 0.85rem;
            font-weight: 600;
            transition: all 0.2s;
        }
        .btn-archive:hover {
            background-color: #e2e8f0;
            color: var(--text-dark);
        }

        .btn-restore {
            background-color: #d1fae5;
            color: #059669;
            border: 1px solid #a7f3d0;
            border-radius: 50px;
            padding: 0.4rem 1rem;
            font-size: 0.85rem;
            font-weight: 600;
            transition: all 0.2s;
        }
        .btn-restore:hover {
            background-color: #10b981;
            color: white;
            border-color: #10b981;
        }

        /* --- RESPONSIVE TABLE STYLING --- */
        .table-responsive {
            border-radius: 12px;
            overflow: hidden;
            border: 1px solid #f1f5f9;
        }

        .table th {
            background-color: #f8f9fa;
            color: var(--text-muted);
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.5px;
            border-bottom: 2px solid #e2e8f0;
            padding: 1rem;
            text-align: left;
        }

        .table td {
            vertical-align: middle;
            padding: 1rem;
            border-bottom: 1px solid #f1f5f9;
            color: var(--text-dark);
            text-align: left;
        }

        /* --- MOBILE STACKED TABLE (THE FIX) --- */
        @media (max-width: 991px) {
            .dashboard-container {
                padding: 1.5rem; /* Reduce padding on mobile */
                margin: 1rem;
            }

            .table-responsive {
                border: none;
                overflow: visible;
            }

            /* Hide Table Headers */
            .table thead {
                display: none;
            }

            /* Make rows behave like cards */
            .table tbody, .table tr, .table td {
                display: block;
                width: 100%;
            }

            .table tr {
                background: #fff;
                border: 1px solid #e2e8f0;
                border-radius: 12px;
                margin-bottom: 1rem;
                padding: 1rem;
                box-shadow: 0 4px 6px rgba(0,0,0,0.02);
            }

            /* Style Cells */
            .table td {
                padding: 0.5rem 0;
                border-bottom: 1px solid #f8f9fa;
                text-align: left !important; /* Force left align */
                display: flex;
                flex-direction: column;
                align-items: flex-start;
            }

            .table td:last-child {
                border-bottom: none;
                padding-top: 1rem;
                align-items: center; /* Center action button */
            }

            /* Add Labels via Data Attribute */
            .table td::before {
                content: attr(data-label);
                font-size: 0.7rem;
                font-weight: 700;
                text-transform: uppercase;
                color: var(--text-muted);
                margin-bottom: 0.25rem;
                display: block;
            }
        }
    </style>
</head>

<body>
    @include('partials.navbar')

    <div class="dashboard-container">
        <div class="text-center mb-5">
            <h2 class="fw-bold mb-2" style="color: var(--text-dark);">
                <i class="bi bi-chat-dots me-2" style="color: var(--primary-color);"></i> Manage Comments
            </h2>
            <p class="text-muted">Review and moderate user discussions.</p>
        </div>

        <div class="filter-section">
            <div class="input-group" style="max-width: 100%;">
                <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                <input type="text" id="searchInput" class="form-control border-start-0 ps-0" placeholder="Search by user, artwork or comment content...">
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover mb-0" id="commentTable">
                <thead>
                    <tr>
                        <th style="width: 20%;">Artwork</th>
                        <th style="width: 20%;">User</th>
                        <th style="width: 30%;">Comment</th>
                        <th style="width: 15%;">Date Posted</th>
                        <th style="width: 15%; text-align: right;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($comments as $comment)
                        <tr style="opacity: {{ $comment->status === 'archived' ? '0.6' : '1' }}">

                            <td data-label="Artwork">
                                <span class="fw-semibold text-primary">{{ Str::limit($comment->challenge_name, 30) }}</span>
                            </td>

                            <td data-label="User">
                                <div class="d-flex align-items-center">
                                    <span class="fw-semibold text-dark">{{ $comment->user_userName }}</span>
                                </div>
                            </td>

                            <td data-label="Comment">
                                <span class="text-muted fst-italic">"{{ Str::limit($comment->comment_text, 100) }}"</span>
                                @if($comment->status === 'archived')
                                    <span class="badge-archived d-inline-block mt-1">Archived</span>
                                @endif
                            </td>

                            <td data-label="Date Posted">
                                <span class="text-muted small">{{ \Carbon\Carbon::parse($comment->created_at)->format('M d, Y') }}</span>
                            </td>

                            <td data-label="Action" style="text-align: right;">
                                <form action="{{ route('admin.comments.archive') }}" method="POST" id="form_{{ $comment->comment_id }}" style="width: 100%;">
                                    @csrf
                                    <input type="hidden" name="comment_id" value="{{ $comment->comment_id }}">

                                    @if($comment->status === 'archived')
                                        <button type="button" class="btn btn-restore btn-sm trigger-restore w-100" data-form-id="form_{{ $comment->comment_id }}">
                                            <i class="bi bi-arrow-counterclockwise me-1"></i> Restore
                                        </button>
                                    @else
                                        <button type="button" class="btn btn-archive btn-sm trigger-archive w-100" data-form-id="form_{{ $comment->comment_id }}">
                                            <i class="bi bi-archive me-1"></i> Archive
                                        </button>
                                    @endif
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
                                <i class="bi bi-chat-square-text display-4 mb-3 d-block opacity-50"></i>
                                No comments found to moderate.
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
                // Skip the "No comments found" row if it exists (it only has 1 cell spanning 5 cols)
                if(row.cells.length > 1) {
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
                    <div class="modal-content rounded-4 border-0 shadow-lg">
                        <div class="modal-header border-0 pb-0 justify-content-center">
                            <div class="bg-light rounded-circle p-3 mb-2 mt-2">
                                <i class="bi bi-exclamation-circle fs-2 text-${confirmVariant === 'secondary' ? 'secondary' : 'success'}"></i>
                            </div>
                        </div>
                        <div class="modal-body text-center pt-0 pb-4">
                            <h5 class="fw-bold mb-2">${title}</h5>
                            <p class="text-muted mb-0 small">${message}</p>
                        </div>
                        <div class="modal-footer border-0 pt-0 px-4 pb-4 flex-nowrap">
                            <button type="button" class="btn btn-light rounded-pill w-100 me-2" data-bs-dismiss="modal">Cancel</button>
                            <button type="button" id="confirmActionBtn" class="btn btn-${confirmVariant === 'secondary' ? 'secondary' : 'success'} rounded-pill w-100 text-white">${confirmText}</button>
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
                    showConfirmationModal('Archive Comment', 'This comment will be hidden from public view.', 'Archive', 'secondary', this.dataset.formId);
                });
            });

            document.querySelectorAll('.trigger-restore').forEach(btn => {
                btn.addEventListener('click', function() {
                    showConfirmationModal('Restore Comment', 'This comment will be visible to everyone again.', 'Restore', 'success', this.dataset.formId);
                });
            });
        });
    </script>
</body>
</html>
