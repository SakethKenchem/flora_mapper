<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $table = 'users';
    protected $primaryKey = 'user_id';

    protected $fillable = [
        'role_id',
        'full_name',
        'email',
        'password',
        'phone_number',
        'institution',
        'account_status',
    ];

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

    /**
     * Get the role associated with the user.
     */
    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id', 'role_id');
    }

    /**
     * Check if user has a specific role name.
     */
    public function hasRole(string $roleName): bool
    {
        return $this->role && $this->role->role_name === strtoupper($roleName);
    }

    /**
     * Helper checks
     */
    public function isAdmin(): bool
    {
        return $this->hasRole('SYSTEM_ADMINISTRATOR');
    }

    public function isResearcher(): bool
    {
        return $this->hasRole('RESEARCHER');
    }

    public function isPublic(): bool
    {
        return $this->hasRole('GENERAL_PUBLIC');
    }
}
