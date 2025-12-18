<div class="sidebar" id="sidebar">
    <!-- Sidebar Header -->
    <div class="sidebar-header">
        <a href="{{ route('pemilik.dashboard') }}" class="logo d-flex align-items-center">
            <i class="fas fa-paw me-2"></i>
            <span class="logo-text">RSHP Pemilik</span>
        </a>
    </div>

    <!-- Navigation -->
    <nav class="sidebar-nav">
        <ul class="nav flex-column">
            <!-- Dashboard -->
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('pemilik.dashboard') ? 'active' : '' }}" 
                   href="{{ route('pemilik.dashboard') }}">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            <!-- Divider -->
            <li class="nav-item">
                <hr class="sidebar-divider" style="border-color: #374151; margin: 0.5rem 1rem;">
            </li>

            <!-- Pet Section -->
            <li class="nav-item">
                <div class="nav-section-title" style="color: #9ca3af; font-size: 0.75rem; padding: 0.5rem 1rem; text-transform: uppercase; font-weight: 600;">
                    <span>Hewan Peliharaan</span>
                </div>
            </li>

            <!-- My Pets -->
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('pemilik.my-pets') ? 'active' : '' }}" 
                   href="{{ route('pemilik.my-pets') }}">
                    <i class="fas fa-shield-dog"></i>
                    <span>Pet Saya</span>
                </a>
            </li>

            <!-- Divider -->
            <li class="nav-item">
                <hr class="sidebar-divider" style="border-color: #374151; margin: 0.5rem 1rem;">
            </li>

            <!-- Future Features (Coming Soon) -->
            <li class="nav-item">
                <div class="nav-section-title" style="color: #9ca3af; font-size: 0.75rem; padding: 0.5rem 1rem; text-transform: uppercase; font-weight: 600;">
                    <span>Segera Hadir</span>
                </div>
            </li>

            <!-- Appointments (Coming Soon) -->
            <li class="nav-item">
                <a class="nav-link disabled" href="#" style="opacity: 0.5; cursor: not-allowed;">
                    <i class="fas fa-calendar-alt"></i>
                    <span>Janji Temu</span>
                </a>
            </li>

            <!-- Medical Records (Coming Soon) -->
            <li class="nav-item">
                <a class="nav-link disabled" href="#" style="opacity: 0.5; cursor: not-allowed;">
                    <i class="fas fa-file-medical"></i>
                    <span>Rekam Medis</span>
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
    margin: 0.5rem 0.25rem;
}
</style>
