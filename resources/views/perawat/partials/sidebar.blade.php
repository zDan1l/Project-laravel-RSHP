<div class="sidebar" id="sidebar">
    <!-- Sidebar Header -->
    <div class="sidebar-header">
        <a href="{{ route('resepsionis.dashboard') }}" class="logo d-flex align-items-center">
            <i class="fas fa-paw me-2"></i>
            <span class="logo-text">RSHP Resepsionis</span>
        </a>
    </div>

    <!-- Navigation -->
    <nav class="sidebar-nav">
        <ul class="nav flex-column">
            <!-- Dashboard -->
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('resepsionis.dashboard') ? 'active' : '' }}" 
                   href="{{ route('resepsionis.dashboard') }}">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            <!-- Divider -->
            <li class="nav-item">
                <hr class="sidebar-divider" style="border-color: #374151; margin: 0.5rem 1rem;">
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
                <a class="nav-link {{ request()->routeIs('resepsionis.pemilik.*') ? 'active' : '' }}" 
                   href="{{ route('resepsionis.pemilik.index') }}">
                    <i class="fas fa-user"></i>
                    <span>Pemilik</span>
                </a>
            </li>

            <!-- Pet -->
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('resepsionis.pet.*') ? 'active' : '' }}" 
                   href="{{ route('resepsionis.pet.index') }}">
                    <i class="fas fa-paw"></i>
                    <span>Pet</span>
                </a>
            </li>

            <!-- Jenis Hewan -->
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('resepsionis.jenis-hewan.*') ? 'active' : '' }}" 
                   href="{{ route('resepsionis.jenis-hewan.index') }}">
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
                <a class="nav-link {{ request()->routeIs('resepsionis.kategori.*') ? 'active' : '' }}" 
                   href="{{ route('resepsionis.kategori.index') }}">
                    <i class="fas fa-tags"></i>
                    <span>Kategori</span>
                </a>
            </li>

            <!-- Kategori Klinis -->
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('resepsionis.kategoriklinis.*') ? 'active' : '' }}" 
                   href="{{ route('resepsionis.kategoriklinis.index') }}">
                    <i class="fas fa-stethoscope"></i>
                    <span>Kategori Klinis</span>
                </a>
            </li>

            <!-- Kode Tindakan Terapi -->
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('resepsionis.kodentindakan.*') ? 'active' : '' }}" 
                   href="{{ route('resepsionis.kodentindakan.index') }}">
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