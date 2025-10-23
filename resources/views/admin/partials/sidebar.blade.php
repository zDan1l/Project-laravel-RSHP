<div class="sidebar" id="sidebar">
    <!-- Sidebar Header -->
    <div class="sidebar-header">
        <a href="{{ route('admin.dashboard') }}" class="logo d-flex align-items-center">
            <i class="fas fa-paw me-2"></i>
            <span class="logo-text">RSHP Admin</span>
        </a>
    </div>

    <!-- Navigation -->
    <nav class="sidebar-nav">
        <ul class="nav flex-column">
            <!-- Dashboard -->
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" 
                   href="{{ route('admin.dashboard') }}">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            <!-- Divider -->
            <li class="nav-item">
                <hr class="sidebar-divider" style="border-color: #374151; margin: 0.5rem 1rem;">
            </li>

            <!-- Data Master -->
            <li class="nav-item">
                <div class="nav-section-title" style="color: #9ca3af; font-size: 0.75rem; padding: 0.5rem 1rem; text-transform: uppercase; font-weight: 600;">
                    <span>Data Master</span>
                </div>
            </li>

            <!-- Users Management -->
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.user.*') ? 'active' : '' }}" 
                   href="{{ route('admin.user.index') }}">
                    <i class="fas fa-users"></i>
                    <span>Users</span>
                </a>
            </li>

            <!-- Roles Management -->
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.role.*') ? 'active' : '' }}" 
                   href="{{ route('admin.role.index') }}">
                    <i class="fas fa-user-tag"></i>
                    <span>Roles</span>
                </a>
            </li>

            <!-- User Roles -->
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.userrole.*') ? 'active' : '' }}" 
                   href="{{ route('admin.userrole.index') }}">
                    <i class="fas fa-user-shield"></i>
                    <span>User Roles</span>
                </a>
            </li>

            <!-- Divider -->
            <li class="nav-item">
                <hr class="sidebar-divider" style="border-color: #374151; margin: 0.5rem 1rem;">
            </li>

            <!-- Pet Management -->
            <li class="nav-item">
                <div class="nav-section-title" style="color: #9ca3af; font-size: 0.75rem; padding: 0.5rem 1rem; text-transform: uppercase; font-weight: 600;">
                    <span>Pet Management</span>
                </div>
            </li>

            <!-- Pemilik -->
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.pemilik.*') ? 'active' : '' }}" 
                   href="{{ route('admin.pemilik.index') }}">
                    <i class="fas fa-user"></i>
                    <span>Pemilik</span>
                </a>
            </li>

            <!-- Pet -->
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.pet.*') ? 'active' : '' }}" 
                   href="{{ route('admin.pet.index') }}">
                    <i class="fas fa-paw"></i>
                    <span>Pet</span>
                </a>
            </li>

            <!-- Jenis Hewan -->
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.jenishewan.*') ? 'active' : '' }}" 
                   href="{{ route('admin.jenishewan.index') }}">
                    <i class="fas fa-dog"></i>
                    <span>Jenis Hewan</span>
                </a>
            </li>

            <!-- Divider -->
            <li class="nav-item">
                <hr class="sidebar-divider" style="border-color: #374151; margin: 0.5rem 1rem;">
            </li>

            <!-- Clinical Data -->
            <li class="nav-item">
                <div class="nav-section-title" style="color: #9ca3af; font-size: 0.75rem; padding: 0.5rem 1rem; text-transform: uppercase; font-weight: 600;">
                    <span>Clinical Data</span>
                </div>
            </li>

            <!-- Kategori -->
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.kategori.*') ? 'active' : '' }}" 
                   href="{{ route('admin.kategori.index') }}">
                    <i class="fas fa-tags"></i>
                    <span>Kategori</span>
                </a>
            </li>

            <!-- Kategori Klinis -->
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.kategoriklinis.*') ? 'active' : '' }}" 
                   href="{{ route('admin.kategoriklinis.index') }}">
                    <i class="fas fa-stethoscope"></i>
                    <span>Kategori Klinis</span>
                </a>
            </li>

            <!-- Kode Tindakan Terapi -->
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.kodentindakan.*') ? 'active' : '' }}" 
                   href="{{ route('admin.kodentindakan.index') }}">
                    <i class="fas fa-code"></i>
                    <span>Kode Tindakan</span>
                </a>
            </li>

            <!-- Divider -->
            <li class="nav-item">
                <hr class="sidebar-divider" style="border-color: #374151; margin: 0.5rem 1rem;">
            </li>

            <!-- System -->
            <li class="nav-item">
                <div class="nav-section-title" style="color: #9ca3af; font-size: 0.75rem; padding: 0.5rem 1rem; text-transform: uppercase; font-weight: 600;">
                    <span>System</span>
                </div>
            </li>

            <!-- Settings -->
            <li class="nav-item">
                <a class="nav-link" href="#">
                    <i class="fas fa-cog"></i>
                    <span>Settings</span>
                </a>
            </li>

            <!-- Logout -->
            <li class="nav-item">
                <a class="nav-link" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Logout</span>
                </a>
            </li>
        </ul>
    </nav>
</div>

<style>
.sidebar-divider {
    border: 0;
    height: 1px;
    background: #374151;
    margin: 0.5rem 1rem;
}

.nav-section-title {
    color: #9ca3af;
    font-size: 0.75rem;
    padding: 0.5rem 1rem;
    text-transform: uppercase;
    font-weight: 600;
    letter-spacing: 0.025em;
}

.sidebar.collapsed .nav-section-title {
    display: none;
}

.sidebar.collapsed .sidebar-divider {
    margin: 0.25rem 0.5rem;
}

/* Logo styling */
.logo {
    color: white !important;
    text-decoration: none !important;
    transition: all 0.3s ease;
}

.logo:hover {
    color: #e5e7eb !important;
}

.sidebar.collapsed .logo-text {
    display: none;
}

.sidebar.collapsed .sidebar-header {
    text-align: center;
    padding: 1rem 0.5rem;
}

.sidebar.collapsed .logo {
    justify-content: center;
}

.sidebar.collapsed .logo .me-2 {
    margin-right: 0 !important;
}
</style>