<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'numeroTelephone',
        'password',
        'roles_id',
        'last_login_at',
        'last_seen_at',
        'deleted_at',
        'last_operation',
        'deletion_reason',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'last_login_at' => 'datetime',
            'last_seen_at' => 'datetime',
            'deleted_at' => 'datetime',
        ];
    }

    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->map(fn (string $name) => Str::of($name)->substr(0, 1))
            ->implode('');
    }

    public function commandes()
    {
        return $this->hasMany(Commande::class, 'user_id');
    }

    public function panier()
    {
        return $this->hasOne(Panier::class, 'user_id');
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'roles_id');
    }

    public function reclamations()
    {
        return $this->hasMany(Reclamation::class, 'user_id');
    }

    public function favoris()
    {
        return $this->belongsToMany(Produit::class, 'favoris', 'user_id', 'produit_id')->withTimestamps();
    }

    public function avis()
    {
        return $this->hasMany(Avis::class, 'user_id');
    }

    public function recherches()
    {
        return $this->hasMany(HistoriqueRecherche::class, 'user_id');
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'user_permissions', 'user_id', 'permission_id')->withTimestamps();
    }

    public function sentAdminMessages()
    {
        return $this->hasMany(AdminMessage::class, 'sender_id');
    }

    public function receivedAdminMessages()
    {
        return $this->hasMany(AdminMessage::class, 'recipient_id');
    }

    public function adminActivities()
    {
        return $this->hasMany(AdminActivityLog::class, 'admin_id');
    }

    public function isClient(): bool
    {
        return strtolower((string) optional($this->role)->nom) === 'client';
    }

    public function isSuperAdmin(): bool
    {
        return strtolower((string) optional($this->role)->nom) === 'super administrateur';
    }

    public function isAdmin(): bool
    {
        return in_array(strtolower((string) optional($this->role)->nom), [
            'super administrateur',
            'administrateur',
            'gerant',
        ], true);
    }

    public function hasPermission(string $permission): bool
    {
        if ($this->isSuperAdmin()) {
            return true;
        }

        $rolePermissions = $this->role?->attributions?->pluck('permission.nom')->filter()->all() ?? [];
        $userPermissions = $this->permissions->pluck('nom')->all();

        return in_array($permission, array_unique(array_merge($rolePermissions, $userPermissions)), true);
    }
}
