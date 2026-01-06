<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable, CanResetPassword;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'alamat',
        'no_HP',
        'gol_darah',
        'filename',
        'facebook',
        'instagram',
        'tgl_lahir',
        'path',
        'role',
        'reading_start_date',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
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
            'tgl_lahir' => 'date',
        ];
    }

    /**
     * Get all kunjungans for this user
     */
    public function kunjungans(): HasMany
    {
        return $this->hasMany(TblKunjungan::class, 'id_jemaat');
    }

    /**
     * Check if user has specific role
     */
    public function hasRole(string $role): bool
    {
        return $this->role === $role;
    }

    /**
     * Check if user is pengurus
     */
    public function isPengurus(): bool
    {
        return $this->hasRole('pengurus');
    }

    /**
     * Check if user is jemaat
     */
    public function isJemaat(): bool
    {
        return $this->hasRole('jemaat');
    }

    /**
     * Check if user is pendeta
     */
    public function isPendeta(): bool
    {
        return $this->hasRole('pendeta');
    }

    /**
     * Get full photo URL
     */
    public function getPhotoUrlAttribute(): ?string
    {
        return $this->filename ? asset('storage/' . $this->filename) : null;
    }

    /**
     * Scope untuk filter berdasarkan role
     */
    public function scopeByRole($query, string $role)
    {
        return $query->where('role', $role);
    }

    /**
     * Scope untuk filter jemaat yang berulang tahun bulan ini
     */
    public function scopeBirthdayThisMonth($query)
    {
        return $query->whereMonth('tgl_lahir', now()->month);
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [
            'role' => $this->role,
            'name' => $this->name,
            'email' => $this->email,
        ];
    }
}
