<div class="sidebar" id="sidebar">
    <!-- Sidebar Header -->
    <div class="sidebar-header">
        <a href="{{ route('dokter.dashboard') }}" class="logo d-flex align-items-center">
            <i class="fas fa-paw me-2"></i>
            <span class="logo-text">RSHP Dokter</span>
        </a>
    </div>

    <!-- Navigation -->
    <nav class="sidebar-nav">
        <ul class="nav flex-column">
            <!-- Dashboard -->
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('dokter.dashboard') ? 'active' : '' }}" 
                   href="{{ route('dokter.dashboard') }}">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            <!-- Divider -->
            <li class="nav-item">
                <hr class="sidebar-divider" style="border-color: #374151; margin: 0.5rem 1rem;">
            </li>

            <!-- Antrian Section -->
            <li class="nav-item">
                <div class="nav-section-title" style="color: #9ca3af; font-size: 0.75rem; padding: 0.5rem 1rem; text-transform: uppercase; font-weight: 600;">
                    <span>Antrian</span>
                </div>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('dokter.antrian.*') ? 'active' : '' }}" 
                   href="{{ route('dokter.antrian.index') }}">
                    <i class="fas fa-users"></i>
                    <span>Antrian Pasien</span>
                </a>
            </li>

            <!-- Divider -->
            <li class="nav-item">
                <hr class="sidebar-divider" style="border-color: #374151; margin: 0.5rem 1rem;">
            </li>

            <!-- Medis Section -->
            <li class="nav-item">
                <div class="nav-section-title" style="color: #9ca3af; font-size: 0.75rem; padding: 0.5rem 1rem; text-transform: uppercase; font-weight: 600;">
                    <span>Medis</span>
                </div>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('dokter.rekam-medis.*') ? 'active' : '' }}" 
                   href="{{ route('dokter.rekam-medis.index') }}">
                    <i class="fas fa-file-medical"></i>
                    <span>Rekam Medis</span>
                </a>
            </li>

            <!-- Divider -->
            <li class="nav-item">
                <hr class="sidebar-divider" style="border-color: #374151; margin: 0.5rem 1rem;">
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