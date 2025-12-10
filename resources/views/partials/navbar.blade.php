<nav class="custom-navbar navbar navbar-expand-md navbar-dark">
    <div class="container-fluid" style="max-width: 1000px;">
        <a class="navbar-brand d-flex align-items-center" href="{{ url('/') }}">
            <i class="fas fa-house me-1"></i>
            BattleArt
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar" aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="mainNavbar">
            <ul class="navbar-nav ms-auto align-items-center">

                @auth
                    {{-- ADMIN MENU --}}
                    @if(Auth::user()->user_type === 'admin')
                        <li class="nav-item">
                            <a href="{{ url('/admin/dashboard') }}" class="nav-link">
                                <i class="fa fa-users" aria-hidden="true"></i> Users
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ url('/admin/artworks') }}" class="nav-link">
                                <i class="fa fa-book" aria-hidden="true"></i> Artworks
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ url('/admin/comments') }}" class="nav-link">
                                <i class="fa fa-comments" aria-hidden="true"></i> Comments
                            </a>
                        </li>

                        <li class="nav-item position-relative">
                            <a class="nav-link" href="{{ url('/notifications') }}">
                                <i class="fa fa-inbox" aria-hidden="true"></i> Inbox
                                @if(isset($unread_count) && $unread_count > 0)
                                    <span class="notification-badge position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                        {{ $unread_count }}<span class="visually-hidden">unread messages</span>
                                    </span>
                                @endif
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ url('/admin/profile') }}" class="nav-link">
                                <i class="fa fa-user-circle" aria-hidden="true"></i> Profile
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('/settings') }}">
                                <i class="fas fa-cog me-1"></i> Settings
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('logout') }}" class="nav-link text-danger"
                               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="fa fa-sign-out-alt" aria-hidden="true"></i> Logout
                            </a>
                        </li>

                    {{-- REGULAR USER MENU --}}
                    @else
                        <li class="nav-item position-relative">
                            <a class="nav-link" href="{{ url('/notifications') }}">
                                <i class="fas fa-inbox me-1"></i> Inbox
                                @if(isset($unread_count) && $unread_count > 0)
                                    <span class="notification-badge position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                        {{ $unread_count }}<span class="visually-hidden">unread messages</span>
                                    </span>
                                @endif
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('/profile') }}">
                                <i class="fas fa-user-circle me-1"></i> Profile
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('/settings') }}">
                                <i class="fas fa-cog me-1"></i> Settings
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('logout') }}"
                               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="fas fa-sign-out-alt me-1"></i> Logout
                            </a>
                        </li>
                    @endif

                    {{-- HIDDEN LOGOUT FORM (Laravel Security Requirement) --}}
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>

                @else
                    {{-- GUEST MENU --}}
                    <li class="nav-item"><a class="nav-link" href="{{ route('login') }}"><i class="fas fa-sign-in-alt me-1"></i> Sign In</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('register') }}"><i class="fas fa-user-plus me-1"></i> Sign Up</a></li>
                @endauth

            </ul>
        </div>
    </div>
</nav>
