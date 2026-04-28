<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable // implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * Méthode d'amorçage du modèle.
     * Gère l'attribution automatique du rôle "Client" lors de l'inscription.
     */
    protected static function booted(): void
    {
        static::creating(function (User $user) {
            // Si aucun rôle n'est spécifié (cas d'une inscription visiteur), on cherche le rôle "Client"
            if (!$user->roles_id) {
                try {
                    $clientRole = Role::where('nom', 'Client')->first();
                    if ($clientRole) {
                        $user->roles_id = $clientRole->id;
                    }
                } catch (\Exception $e) {
                    // Évite de bloquer l'application si la table roles n'est pas encore prête
                }
            }
        });
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'numeroTelephone',
        'roles_id',
        'last_login_at',
        'last_seen_at',
        'last_operation',
        'deletion_reason',
        'deleted_at'
    ];

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
            'last_login_at' => 'datetime',
            'last_seen_at' => 'datetime',
        ];
    }

    /**
     * Get the user's initials
     */
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

    public function adminActivities()
    {
        return $this->hasMany(AdminActivityLog::class, 'admin_id')->latest();
    }

    /**
     * Historique des recherches de l'utilisateur.
     */
    public function recherches()
    {
        return $this->hasMany(HistoriqueRecherche::class, 'user_id');
    }

    public function panier()
    {
        return $this->hasOne(Panier::class, 'user_id');
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'roles_id');
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'user_permissions', 'user_id', 'permission_id');
    }

    public function favoris()
    {
        return $this->belongsToMany(Produit::class, 'favoris', 'user_id', 'produit_id')->withTimestamps();
    }

    /**
     * Vérifie si l'utilisateur a un rôle administratif
     */
    public function isAdmin(): bool
    {
        return in_array($this->role?->nom, ['Super Administrateur', 'Administrateur', 'Gerant'], true);
    }

    /**
     * Vérifie si l'utilisateur est un Super Administrateur
     */
    public function isSuperAdmin(): bool
    {
        return $this->role?->nom === 'Super Administrateur';
    }

    /**
     * Vérifie si l'utilisateur est un simple client
     */
    public function isClient(): bool
    {
        return $this->role?->nom === 'Client';
    }

    /**
     * Vérifie si l'utilisateur possède une permission spécifique (via son rôle ou direct)
     */
    public function hasPermission(string $permission): bool
    {
        // Un Super Administrateur possède toutes les permissions par défaut
        if ($this->isSuperAdmin()) {
            return true;
        }

        // Vérification directe sur l'utilisateur
        if ($this->permissions()->where('nom', $permission)->exists()) {
            return true;
        }

        // Vérification via le rôle (Attributions)
        if ($this->role) {
            return $this->role->attributions()
                ->whereHas('permission', fn($q) => $q->where('nom', $permission))
                ->exists();
        }

        return false;
    }
}
