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
                    {{ Auth::user()->nama ?? 'Pemilik' }}
                </span>
            </button>
            <ul class="dropdown-menu dropdown-menu-end shadow">
                <li>
                    <div class="dropdown-item-text">
                        <div class="fw-bold">{{ Auth::user()->nama ?? 'Pemilik' }}</div>
                        <div class="small text-muted">{{ Auth::user()->email ?? 'pemilik@rshp.com' }}</div>
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
                        <i class="fas fa-cog me-2"></i>Pengaturan
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
<button class="btn btn-primary d-md-none position-fixed bottom-0 end-0 m-3 rounded-circle shadow" 
        id="mobile-sidebar-toggle" 
        style="width: 50px; height: 50px; z-index: 1001;">
    <i class="fas fa-bars"></i>
</button>

<!-- Logout Form -->
<form id="logout-form-header" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
</form>

<style>
.topbar {
    background: white;
    border-bottom: 1px solid #e5e7eb;
    padding: 1rem 1.5rem;
    position: sticky;
    top: 0;
    z-index: 999;
}

.dropdown-menu {
    border: 1px solid #e5e7eb;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
}

.dropdown-item {
    padding: 0.5rem 1rem;
}

.dropdown-item:hover {
    background-color: #f3f4f6;
}
</style>
