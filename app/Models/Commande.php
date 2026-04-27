<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Commande extends Model
{
    public const STATUT_EN_ATTENTE = 'en_attente';
    public const STATUT_CONFIRMEE = 'confirmee';
    public const STATUT_LIVREE = 'livree';
    public const STATUT_REJETEE = 'rejetee';
    public const STATUT_ANNULEE = 'annulee';

    public const STATUTS = [
        self::STATUT_EN_ATTENTE,
        self::STATUT_CONFIRMEE,
        self::STATUT_LIVREE,
        self::STATUT_REJETEE,
        self::STATUT_ANNULEE,
    ];

    protected $table = 'commandes';

    protected $fillable = [
        'date_commande',
        'statut',
        'rejection_reason',
        'tracking_code',
        'processed_by',
        'processed_at',
        'user_id',
        'adresse_livraison',
        'date_livraison',
    ];

    protected function casts(): array
    {
        return [
            'date_commande' => 'date',
            'date_livraison' => 'date',
            'processed_at' => 'datetime',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function paiement()
    {
        return $this->hasOne(Paiement::class, 'commande_id');
    }

    public function processedBy()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    public function produits()
    {
        return $this->belongsToMany(Produit::class, 'commande_produits', 'commande_id', 'produit_id')
            ->withPivot('quantite')
            ->withTimestamps();
    }

    public function statutLabel(): string
    {
        return match ($this->statut) {
            self::STATUT_EN_ATTENTE, 'en attente' => 'En attente',
            self::STATUT_CONFIRMEE, 'confirme' => 'Confirmee',
            self::STATUT_LIVREE, 'livree' => 'Livree',
            self::STATUT_REJETEE, 'rejetee' => 'Rejetee',
            self::STATUT_ANNULEE, 'annulee' => 'Annulee',
            default => ucfirst((string) $this->statut),
        };
    }

    public function statutBadgeClass(): string
    {
        return match ($this->statut) {
            self::STATUT_CONFIRMEE => 'bg-emerald-100 text-emerald-700',
            self::STATUT_LIVREE => 'bg-blue-100 text-blue-700',
            self::STATUT_REJETEE => 'bg-rose-100 text-rose-700',
            self::STATUT_ANNULEE => 'bg-slate-200 text-slate-700',
            default => 'bg-amber-100 text-amber-700',
        };
    }

    public function canBeCancelled(): bool
    {
        return $this->statut === self::STATUT_EN_ATTENTE;
    }

    public function canBeManagedByAdmin(): bool
    {
        return $this->statut === self::STATUT_EN_ATTENTE;
    }
}
