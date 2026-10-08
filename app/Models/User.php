<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Branch;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'password',
        'status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }

    public function employee(): HasOne
    {
        return $this->hasOne(Employee::class);
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class);
    }

    public function permissions()
    {
        return Permission::query()
            ->whereHas('roles', function ($query) {
                $query->whereIn('roles.id', $this->roles()->pluck('roles.id'));
            });
    }

    public function branches(): BelongsToMany
    {
        return $this->belongsToMany(Branch::class);
    }

    public function hasRole(string $role): bool
    {
        return $this->roles()->where('name', $role)->exists();
    }

    public function hasPermission(string $permission): bool
    {
        return $this->roles()
            ->whereHas('permissions', function ($query) use ($permission) {
                $query->where('name', $permission);
            })
            ->exists();
    }

    public function hasBranchAccess(int $branchId): bool
    {
        return $this->branches()->where('branches.id', $branchId)->exists();
    }

    public function canAccessBranch(int $branchId): bool
    {
        if ($this->hasRole('super_admin')) {
            return true;
        }

        return $this->hasBranchAccess($branchId);
    }

    public function accessibleBranchIds(): array
    {
        if ($this->hasRole('super_admin')) {
            return Branch::query()
                ->pluck('id')
                ->map(fn ($id) => (int) $id)
                ->all();
        }

        return $this->branches()
            ->pluck('branches.id')
            ->map(fn ($id) => (int) $id)
            ->all();
    }

    public function registerSessions(): HasMany
    {
        return $this->hasMany(RegisterSession::class);
    }
}















// namespace App\Models;

// // use Illuminate\Contracts\Auth\MustVerifyEmail;
// // use Database\Factories\UserFactory;
// use Illuminate\Database\Eloquent\Attributes\Fillable;
// use Illuminate\Database\Eloquent\Attributes\Hidden;
// use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Illuminate\Foundation\Auth\User as Authenticatable;
// use Illuminate\Notifications\Notifiable;

// #[Fillable(['name', 'email', 'password'])]
// #[Hidden(['password', 'remember_token'])]
// class User extends Authenticatable
// {
//     /** @use HasFactory<UserFactory> */
//     use HasFactory, Notifiable;

//     /**
//      * Get the attributes that should be cast.
//      *
//      * @return array<string, string>
//      */
//     protected function casts(): array
//     {
//         return [
//             'email_verified_at' => 'datetime',
//             'password'          => 'hashed',
//         ];
//     }
// }
