# 🔗 Cara Mengambil Data dari Tabel Pivot Many-to-Many di Laravel

## 📋 **Struktur Tabel**

```sql
-- Tabel users
CREATE TABLE users (
    id INT PRIMARY KEY,
    name VARCHAR(255),
    email VARCHAR(255)
);

-- Tabel roles  
CREATE TABLE roles (
    id INT PRIMARY KEY,
    name VARCHAR(255)
);

-- Tabel pivot role_user
CREATE TABLE role_user (
    iduser INT,          -- FK ke users.id
    idrole INT,          -- FK ke roles.id  
    status VARCHAR(50),  -- Data tambahan di pivot
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    FOREIGN KEY (iduser) REFERENCES users(id),
    FOREIGN KEY (idrole) REFERENCES roles(id)
);
```

## 🏗️ **Setup Model Relationships**

### 1. **User Model**
```php
<?php
// app/Models/User.php
class User extends Authenticatable
{
    public function roles(): BelongsToMany 
    {
        return $this->belongsToMany(Role::class, 'role_user', 'iduser', 'idrole')
                    ->using(UserRole::class)  // Custom pivot model
                    ->withPivot('status', 'created_at', 'updated_at')
                    ->withTimestamps();
    }
}
```

### 2. **Role Model**
```php
<?php
// app/Models/Role.php
class Role extends Model
{
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'role_user', 'idrole', 'iduser')
                    ->using(UserRole::class)
                    ->withPivot('status', 'created_at', 'updated_at')
                    ->withTimestamps();
    }
}
```

### 3. **UserRole Pivot Model**
```php
<?php
// app/Models/UserRole.php
use Illuminate\Database\Eloquent\Relations\Pivot;

class UserRole extends Pivot
{
    protected $table = 'role_user';
    
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'iduser');
    }
    
    public function role(): BelongsTo  
    {
        return $this->belongsTo(Role::class, 'idrole');
    }
}
```

## 🎯 **Cara Mengambil Data Pivot**

### **Method 1: Langsung dari Tabel Pivot**
```php
// Menggunakan UserRole model langsung
$userRoles = UserRole::with(['user', 'role'])->get();

foreach ($userRoles as $userRole) {
    echo "User: " . $userRole->user->name;
    echo "Role: " . $userRole->role->name;
    echo "Status: " . $userRole->status;
    echo "Assigned: " . $userRole->created_at;
}
```

### **Method 2: Dari User Model dengan Eager Loading**
```php
// Ambil users dengan roles dan data pivot
$users = User::with('roles')->get();

foreach ($users as $user) {
    echo "User: " . $user->name . "\n";
    
    foreach ($user->roles as $role) {
        echo "- Role: " . $role->name;
        echo "- Status: " . $role->pivot->status;        // ← Data pivot
        echo "- Since: " . $role->pivot->created_at;     // ← Data pivot
    }
}
```

### **Method 3: Dari Role Model dengan Eager Loading**
```php
// Ambil roles dengan users dan data pivot
$roles = Role::with('users')->get();

foreach ($roles as $role) {
    echo "Role: " . $role->name . "\n";
    
    foreach ($role->users as $user) {
        echo "- User: " . $user->name;
        echo "- Status: " . $user->pivot->status;        // ← Data pivot
        echo "- Since: " . $user->pivot->created_at;     // ← Data pivot
    }
}
```

## 🔍 **Query Lanjutan dengan Kondisi**

### **1. Filter berdasarkan data pivot**
```php
// User yang memiliki role dengan status 'active'
$activeUsers = User::whereHas('roles', function($query) {
    $query->wherePivot('status', 'active');
})->with(['roles' => function($query) {
    $query->wherePivot('status', 'active');
}])->get();
```

### **2. Filter berdasarkan nama role**
```php  
// User yang memiliki role 'Admin'
$admins = User::whereHas('roles', function($query) {
    $query->where('name', 'Admin');
})->get();
```

### **3. Count users per role**
```php
// Hitung jumlah user per role
$roleCounts = Role::withCount('users')->get();

foreach ($roleCounts as $role) {
    echo $role->name . ": " . $role->users_count . " users";
}
```

### **4. User dengan multiple roles**
```php
// User yang memiliki lebih dari 1 role
$usersWithMultipleRoles = User::has('roles', '>', 1)->get();
```

### **5. Specific pivot conditions**
```php
// Data pivot dengan kondisi tertentu
$specificPivotData = UserRole::where('status', 'active')
                           ->where('created_at', '>', now()->subDays(30))
                           ->with(['user', 'role'])
                           ->get();
```

## ✏️ **Manipulasi Data Pivot**

### **1. Attach (Menambah relasi)**
```php
$user = User::find(1);

// Attach role dengan data pivot
$user->roles()->attach(2, [
    'status' => 'active',
    'created_at' => now(),
    'updated_at' => now()
]);
```

### **2. Detach (Menghapus relasi)**
```php
$user = User::find(1);

// Detach specific role
$user->roles()->detach(2);

// Detach all roles
$user->roles()->detach();
```

### **3. Update Pivot**
```php
$user = User::find(1);

// Update data pivot
$user->roles()->updateExistingPivot(2, [
    'status' => 'inactive',
    'updated_at' => now()
]);
```

### **4. Sync (Replace semua relasi)**
```php
$user = User::find(1);

// Sync roles dengan data pivot
$user->roles()->sync([
    1 => ['status' => 'active'],
    2 => ['status' => 'inactive'],
    3 => ['status' => 'active']
]);
```

### **5. SyncWithoutDetaching (Tambah tanpa hapus)**
```php
$user = User::find(1);

// Tambah role baru tanpa menghapus yang lama
$user->roles()->syncWithoutDetaching([
    4 => ['status' => 'active']
]);
```

## 🎨 **Contoh di Controller**

```php
<?php
// UserRoleController.php

class UserRoleController extends Controller
{
    public function index()
    {
        // Method 1: Langsung dari pivot table
        $userRoles = UserRole::with(['user', 'role'])->get();
        
        // Method 2: Dari User model
        $usersWithRoles = User::with('roles')->get();
        
        // Method 3: Dari Role model  
        $rolesWithUsers = Role::with('users')->get();
        
        return view('admin.userrole.index', compact(
            'userRoles', 'usersWithRoles', 'rolesWithUsers'
        ));
    }
    
    public function show($userId)
    {
        $user = User::with('roles')->findOrFail($userId);
        
        // Akses data pivot
        foreach ($user->roles as $role) {
            echo "Role: {$role->name}";
            echo "Status: {$role->pivot->status}";
            echo "Assigned: {$role->pivot->created_at}";
        }
        
        return view('admin.userrole.show', compact('user'));
    }
}
```

## 🎯 **Contoh di Blade Template**

```blade
{{-- resources/views/admin/userrole/index.blade.php --}}

{{-- Method 1: Tampilkan data pivot langsung --}}
@foreach($userRoles as $userRole)
<tr>
    <td>{{ $userRole->user->name }}</td>
    <td>{{ $userRole->role->name }}</td>
    <td>{{ $userRole->status }}</td>
    <td>{{ $userRole->created_at->format('d M Y') }}</td>
</tr>
@endforeach

{{-- Method 2: Tampilkan user dengan roles --}}
@foreach($usersWithRoles as $user)
<div class="user-card">
    <h5>{{ $user->name }}</h5>
    
    @foreach($user->roles as $role)
    <span class="badge bg-primary">{{ $role->name }}</span>
    <small>Status: {{ $role->pivot->status }}</small>
    <small>Since: {{ $role->pivot->created_at->format('d M Y') }}</small>
    @endforeach
</div>
@endforeach
```

## 🚀 **Tips dan Best Practices**

### ✅ **DO's:**
- Gunakan `withPivot()` untuk akses kolom tambahan di pivot table
- Gunakan `withTimestamps()` jika pivot table memiliki created_at/updated_at
- Buat custom Pivot model untuk logika kompleks
- Gunakan eager loading (`with()`) untuk menghindari N+1 query problem
- Gunakan `whereHas()` untuk filter berdasarkan relasi

### ❌ **DON'Ts:**
- Jangan lupa parameter urutan di `belongsToMany()`: (model, table, foreignKey, relatedKey)
- Jangan query pivot table langsung tanpa relasi jika tidak perlu
- Jangan lupa `withPivot()` jika ingin akses kolom tambahan
- Jangan gunakan `sync()` jika hanya ingin menambah relasi (gunakan `attach()`)

## 📊 **Performance Tips**

1. **Eager Loading**: Selalu gunakan `with()` untuk menghindari N+1 queries
2. **Selective Loading**: Hanya load kolom yang diperlukan dengan `select()`
3. **Pagination**: Gunakan `paginate()` untuk data besar
4. **Indexing**: Buat index pada foreign key di tabel pivot
5. **Caching**: Cache query yang sering digunakan

Dengan setup ini, Anda bisa dengan mudah mengambil dan manipulasi data dari tabel pivot `role_user`! 🎉