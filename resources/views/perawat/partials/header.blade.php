<div class="topbar">
    <div class="topbar-left">
        {{-- button toggle --}}
        <button class="sidebar-toggle" id="sidebar-toggle" type="button" title="Toggle Sidebar">
            <i class="fas fa-bars"></i>
        </button>
    </div>

    <div class="topbar-right">

        <!-- User Profile -->
        <div class="dropdown user-dropdown">
            <button class="dropdown-toggle" type="button" data-bs-toggle="dropdown">
                <div class="user-avatar">
                    <i class="fas fa-user"></i>
                </div>
                <span class="ms-2 d-none d-md-inline">
                    {{ Auth::user()->name ?? 'Perawat User' }}
                </span>
            </button>
            <ul class="dropdown-menu dropdown-menu-end shadow">
                <li>
                    <div class="dropdown-item-text">
                        <div class="fw-bold">{{ Auth::user()->name ?? 'Resepsionis User' }}</div>
                        <div class="small text-muted">{{ Auth::user()->email ?? 'resepsionis@rshp.com' }}</div>
                    </div>
                </li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <a class="dropdown-item" href="#">
                        <i class="fas fa-user-circle me-2"></i>Profile
                    </a>
                </li>
                <li>
                    <a class="dropdown-item" href="#">
                        <i class="fas fa-cog me-2"></i>Settings
                    </a>
                </li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('logout-form-header').submit();">
                        <i class="fas fa-sign-out-alt me-2"></i>Logout
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>

<!-- Mobile Sidebar Toggle (visible on small screens) -->
<button class="btn btn-primary d-md-none position-fixed" 
        id="mobile-sidebar-toggle" 
        style="top: 10px; left: 10px; z-index: 1100;">
    <i class="fas fa-bars"></i>
</button>

<!-- Logout Form for Header (hidden) -->
<form id="logout-form-header" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
</form>

<style>
.topbar {
    position: sticky;
    top: 0;
    z-index: 999;
    background: white;
    border-bottom: 1px solid #e5e7eb;
    padding: 1rem 1.5rem;
}

.sidebar-toggle {
    background: none;
    border: none;
    font-size: 1.2rem;
    color: #111827;
    cursor: pointer;
    transition: color 0.2s ease;
}

.sidebar-toggle:hover {
    color: #2563eb;
}

.sidebar-toggle:focus {
    outline: none;
    box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.2);
    border-radius: 4px;
}

.sidebar-toggle:active {
    transform: scale(0.95);
}

.user-dropdown .dropdown-toggle {
    background: none;
    border: none;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: #111827;
}

.user-dropdown .dropdown-toggle::after {
    display: none;
}

.user-avatar {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: linear-gradient(135deg, #2563eb, #1e40af);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 0.85rem;
}

.dropdown-menu {
    border: 1px solid #e5e7eb;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
    border-radius: 8px;
}

.dropdown-item {
    padding: 0.5rem 1rem;
    transition: background-color 0.2s ease;
}

.dropdown-item:hover {
    background-color: #f8fafc;
}

.badge-sm {
    font-size: 0.6rem;
    padding: 0.2rem 0.4rem;
}

@media (max-width: 768px) {
    .topbar {
        padding: 0.75rem 1rem;
    }
    
    .topbar-left {
        flex: 1;
    }
    
    .breadcrumb {
        margin-bottom: 0;
        font-size: 0.875rem;
    }
}
</style>