<nav class="navbar-top">
    <!-- Left: Hamburger + Page Title -->
    <div class="navbar-left">
        <button class="hamburger-btn" id="hamburgerBtn">
            <i class="fas fa-bars"></i>
        </button>
        <div>
            <div class="page-title-nav">@yield('page-title', 'Dashboard')</div>
        </div>
    </div>

    <!-- Right: Dark mode + User -->
    <div class="navbar-right">
        <button id="darkModeToggle" class="dark-mode-btn" title="Toggle Dark Mode">
            <i class="fas fa-moon"></i>
        </button>

        <div class="dropdown">
            <button class="user-avatar-btn dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=6366f1&color=fff"
                    alt="{{ auth()->user()->name }}">
                <div class="text-start">
                    <div class="user-name">{{ auth()->user()->name }}</div>
                    <div class="user-role">{{ ucfirst(auth()->user()->role?->name ?? 'User') }}</div>
                </div>
                <i class="fas fa-chevron-down ms-1 text-muted" style="font-size:11px"></i>
            </button>
            <ul class="dropdown-menu dropdown-menu-end shadow border-0" style="min-width:190px;border-radius:12px;">
                <li>
                    <div class="px-3 py-2 border-bottom">
                        <div class="fw-semibold" style="font-size:13px">{{ auth()->user()->name }}</div>
                        <div class="text-muted" style="font-size:12px">{{ auth()->user()->email }}</div>
                    </div>
                </li>
                <li>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="dropdown-item text-danger py-2">
                            <i class="fas fa-sign-out-alt me-2"></i>Logout
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Sidebar overlay for mobile -->
<div class="sidebar-overlay" id="sidebarOverlay"></div>