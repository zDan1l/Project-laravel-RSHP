# RSHP Admin Dashboard

Dashboard admin untuk sistem manajemen Rumah Sakit Hewan Pendidikan (RSHP) dengan desain yang profesional dan responsif.

## Struktur File

### Layout Utama
- `resources/views/layouts/admin.blade.php` - Template utama dashboard dengan CSS dan JS lengkap
- `resources/views/admin/partials/sidebar.blade.php` - Komponen sidebar dengan navigasi
- `resources/views/admin/partials/header.blade.php` - Komponen header dengan breadcrumb dan user menu

### Halaman Dashboard
- `resources/views/admin/dashboard/index.blade.php` - Halaman utama dashboard dengan statistik dan charts

### Contoh Halaman Menu
- `resources/views/admin/user/index.blade.php` - Halaman manajemen user
- `resources/views/admin/pet/index.blade.php` - Halaman manajemen pet

## Fitur Dashboard

### 1. Sidebar Navigation
- **Collapsible sidebar** - Dapat diperkecil/diperbesar
- **Responsive design** - Otomatis tersembunyi di mobile
- **Active states** - Menu aktif ter-highlight otomatis
- **Grouped menus** - Menu dikelompokkan berdasarkan fungsi:
  - Data Master (Users, Roles, User Roles)
  - Pet Management (Pemilik, Pet, Jenis Hewan)
  - Clinical Data (Kategori, Kategori Klinis, Kode Tindakan)
  - System (Settings, Logout)

### 2. Top Header
- **Sidebar toggle button**
- **Breadcrumb navigation**
- **Notifications dropdown** dengan badge counter
- **User profile dropdown** dengan logout

### 3. Dashboard Content
- **Welcome card** dengan pesan selamat datang
- **Statistics cards** dengan angka-angka penting
- **Recent activities table** menampilkan aktivitas terbaru
- **Quick actions** untuk akses cepat fungsi umum
- **System status** monitoring kesehatan sistem
- **Charts** untuk visualisasi data (menggunakan Chart.js)

### 4. Responsive Design
- **Mobile-first approach**
- **Bootstrap 5** framework
- **Breakpoints** yang sesuai untuk semua device
- **Touch-friendly** navigation untuk mobile

## Warna dan Tema

Dashboard menggunakan skema warna profesional:
- **Primary**: `#2563eb` (Blue)
- **Secondary**: `#1e40af` (Dark Blue)  
- **Success**: `#10b981` (Green)
- **Warning**: `#f59e0b` (Orange)
- **Danger**: `#ef4444` (Red)
- **Sidebar**: `#1f2937` (Dark Gray)
- **Background**: `#f8fafc` (Light Gray)

## Cara Menggunakan

### 1. Extend Layout
Untuk membuat halaman admin baru, extend dari layout utama:

```php
@extends('layouts.admin')

@section('title', 'Page Title')
@section('page-title', 'Page Title')

@section('content')
    <!-- Content here -->
@endsection
```

### 2. Menambah Menu Sidebar
Edit file `resources/views/admin/partials/sidebar.blade.php` dan tambahkan item menu:

```html
<li class="nav-item">
    <a class="nav-link {{ request()->routeIs('admin.newmenu.*') ? 'active' : '' }}" 
       href="{{ route('admin.newmenu.index') }}">
        <i class="fas fa-icon"></i>
        <span>Menu Name</span>
    </a>
</li>
```

### 3. Menambah Routes
Edit file `routes/web.php` dan tambahkan route baru:

```php
Route::get('/new-menu', function () {
    return view('admin.newmenu.index');
})->name('newmenu.index');
```

### 4. Custom Styles dan Scripts
Gunakan `@push('styles')` dan `@push('scripts')` untuk menambah CSS/JS khusus:

```php
@push('styles')
<style>
    /* Custom CSS */
</style>
@endpush

@push('scripts')
<script>
    // Custom JavaScript
</script>
@endpush
```

## Komponen Yang Tersedia

### 1. Statistics Cards
```html
<div class="card stat-card h-100">
    <div class="card-body">
        <div class="stat-number">150</div>
        <div class="stat-label">Total Users</div>
    </div>
</div>
```

### 2. Data Tables
Semua tabel menggunakan Bootstrap table dengan:
- Responsive design
- Hover effects
- Action buttons
- Pagination

### 3. Filter Forms
Form pencarian dan filter dengan:
- Bootstrap form controls
- Responsive grid layout
- Reset functionality

### 4. Modals
Modal konfirmasi untuk aksi delete dan lainnya sudah disediakan.

## Akses Dashboard

Dashboard dapat diakses melalui URL:
- `/admin/dashboard` - Halaman utama dashboard
- `/admin/users` - Manajemen users
- `/admin/pets` - Manajemen pets
- Dan URL lainnya sesuai routing

## Dependencies

Dashboard menggunakan library eksternal:
- **Bootstrap 5** - UI Framework
- **Font Awesome 6** - Icons
- **Chart.js** - Charts dan grafik
- **Inter Font** - Typography

## Browser Support

Dashboard telah ditest dan compatible dengan:
- Chrome 90+
- Firefox 88+
- Safari 14+
- Edge 90+

## Tips Pengembangan

1. **Konsistensi**: Gunakan komponen yang sudah ada untuk menjaga konsistensi UI
2. **Performance**: Minimalkan penggunaan JavaScript custom, manfaatkan Bootstrap
3. **Accessibility**: Pastikan semua elemen dapat diakses via keyboard
4. **Mobile**: Selalu test di device mobile untuk memastikan responsivitas
5. **Colors**: Gunakan CSS variables yang sudah didefinisikan untuk konsistensi warna