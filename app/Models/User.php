<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\UserRole;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Menggunakan tabel dan primary key custom
    protected $table = 'user';
    protected $primaryKey = 'iduser';
    public $timestamps = false;

    public function userRole(): HasMany 
    {
        return $this->hasMany(UserRole::class, 'iduser', 'iduser');
    }

    public function pemilik(): HasMany
    {
        return $this->hasMany(Pemilik::class, 'iduser', 'iduser');
    }

    /**
     * Relasi ke RekamMedis sebagai dokter pemeriksa
     */
    public function rekamMedisAsDokter(): HasMany
    {
        return $this->hasMany(RekamMedis::class, 'dokter_pemeriksa', 'iduser');
    }

    /**
     * Check if this User is being used by other entities
     * Excluding UserRole (akan di-cascade delete)
     * 
     * @return bool
     */
    public function isUsedByOtherEntities(): bool
    {
        // Cek Pemilik
        if ($this->pemilik()->exists()) {
            return true;
        }

        // Cek RekamMedis sebagai dokter
        if ($this->rekamMedisAsDokter()->exists()) {
            return true;
        }

        return false;
    }

    /**
     * Get usage details for error message
     * 
     * @return array
     */
    public function getUsageDetails(): array
    {
        $details = [];
        
        $pemilikCount = $this->pemilik()->count();
        if ($pemilikCount > 0) {
            $details[] = "$pemilikCount data pemilik";
        }
        
        $rekamMedisCount = $this->rekamMedisAsDokter()->count();
        if ($rekamMedisCount > 0) {
            $details[] = "$rekamMedisCount rekam medis sebagai dokter";
        }
        
        return $details;
    }

    /**
     * Get active UserRole for this user
     * 
     * @return UserRole|null
     */
    public function getActiveRole()
    {
        return $this->userRole()->where('status', 1)->first();
    }
}
