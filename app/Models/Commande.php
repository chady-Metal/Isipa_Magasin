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
        'user_id',
        'adresse_livraison',
        'date_livraison'
          ];

          public function user()
          {
              return $this->belongsTo(User::class, 'user_id');
          } 

          public function paiement()
          {
              return $this->hasOne(Paiement::class, 'commande_id');
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
                  self::STATUT_CONFIRMEE, 'confirme', 'confirmé' => 'Confirmee',
                  self::STATUT_LIVREE, 'livree', 'livrée' => 'Livree',
                  self::STATUT_REJETEE, 'rejetee', 'rejeté' => 'Rejetee',
                  self::STATUT_ANNULEE, 'annulee', 'annulée' => 'Annulee',
                  default => ucfirst((string) $this->statut),
              };
          }

          public function statutBadgeClass(): string
          {
              return match ($this->statut) {
                  self::STATUT_CONFIRMEE, 'confirme', 'confirmé' => 'bg-emerald-100 text-emerald-700',
                  self::STATUT_LIVREE, 'livree', 'livrée' => 'bg-blue-100 text-blue-700',
                  self::STATUT_REJETEE, 'rejetee', 'rejeté' => 'bg-rose-100 text-rose-700',
                  self::STATUT_ANNULEE, 'annulee', 'annulée' => 'bg-slate-200 text-slate-700',
                  default => 'bg-amber-100 text-amber-700',
              };
          }

          public function canBeCancelled(): bool
          {
              return in_array($this->statut, [self::STATUT_EN_ATTENTE, 'en attente'], true);
          }

          public function canBeManagedByAdmin(): bool
          {
              return in_array($this->statut, [self::STATUT_EN_ATTENTE], true);
          }
}
