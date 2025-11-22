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

            <!-- Registrasi Section -->
            <li class="nav-item">
                <div class="nav-section-title" style="color: #9ca3af; font-size: 0.75rem; padding: 0.5rem 1rem; text-transform: uppercase; font-weight: 600;">
                    <span>Registrasi</span>
                </div>
            </li>

            <!-- Pemilik -->
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('resepsionis.pemilik.*') ? 'active' : '' }}" 
                   href="{{ route('resepsionis.pemilik.index') }}">
                    <i class="fas fa-user"></i>
                    <span>Data Pemilik</span>
                </a>
            </li>

            <!-- Pet -->
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('resepsionis.pet.*') ? 'active' : '' }}" 
                   href="{{ route('resepsionis.pet.index') }}">
                    <i class="fas fa-shield-dog"></i>
                    <span>Data Pet</span>
                </a>
            </li>

            <!-- Divider -->
            <li class="nav-item">
                <hr class="sidebar-divider" style="border-color: #374151; margin: 0.5rem 1rem;">
            </li>

            <!-- Temu Dokter Section -->
            <li class="nav-item">
                <div class="nav-section-title" style="color: #9ca3af; font-size: 0.75rem; padding: 0.5rem 1rem; text-transform: uppercase; font-weight: 600;">
                    <span>Jadwal & Temu Dokter</span>
                </div>
            </li>

            <!-- Temu Dokter -->
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('resepsionis.temu-dokter.*') ? 'active' : '' }}" 
                   href="{{ route('resepsionis.temu-dokter.index') }}">
                    <i class="fas fa-calendar-alt"></i>
                    <span>Jadwal Temu Dokter</span>
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
</style>
