<aside class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <div class="logo">
            <i class="fas fa-graduation-cap"></i>
            <span>RMS</span>
        </div>
        <button class="sidebar-toggle d-lg-none" id="sidebarToggle">
            <i class="fas fa-times"></i>
        </button>
    </div>
    
    <div class="sidebar-body">
        <ul class="nav flex-column">
            @if(auth()->user()->isAdmin())
                <li class="nav-item">
                    <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <i class="fas fa-home"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.students') }}" class="nav-link {{ request()->routeIs('admin.students') ? 'active' : '' }}">
                        <i class="fas fa-user-graduate"></i>
                        <span>Students</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.teachers') }}" class="nav-link {{ request()->routeIs('admin.teachers') ? 'active' : '' }}">
                        <i class="fas fa-chalkboard-teacher"></i>
                        <span>Teachers</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.classes') }}" class="nav-link {{ request()->routeIs('admin.classes') ? 'active' : '' }}">
                        <i class="fas fa-building"></i>
                        <span>Classes</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.subjects') }}" class="nav-link {{ request()->routeIs('admin.subjects') ? 'active' : '' }}">
                        <i class="fas fa-book"></i>
                        <span>Subjects</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.results') }}" class="nav-link {{ request()->routeIs('admin.results') ? 'active' : '' }}">
                        <i class="fas fa-chart-bar"></i>
                        <span>Results</span>
                    </a>
                </li>
            @elseif(auth()->user()->isTeacher())
                <li class="nav-item">
                    <a href="{{ route('teacher.dashboard') }}" class="nav-link {{ request()->routeIs('teacher.dashboard') ? 'active' : '' }}">
                        <i class="fas fa-home"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('teacher.subjects') }}" class="nav-link {{ request()->routeIs('teacher.subjects') ? 'active' : '' }}">
                        <i class="fas fa-book"></i>
                        <span>My Subjects</span>
                    </a>
                </li>
            @elseif(auth()->user()->isStudent())
                <li class="nav-item">
                    <a href="{{ route('student.dashboard') }}" class="nav-link {{ request()->routeIs('student.dashboard') ? 'active' : '' }}">
                        <i class="fas fa-home"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('student.results') }}" class="nav-link {{ request()->routeIs('student.results') ? 'active' : '' }}">
                        <i class="fas fa-chart-line"></i>
                        <span>My Results</span>
                    </a>
                </li>
            @endif
        </ul>
    </div>
    
    <div class="sidebar-footer">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-logout w-100">
                <i class="fas fa-sign-out-alt me-2"></i>Logout
            </button>
        </form>
    </div>
</aside>