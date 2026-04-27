<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produit extends Model
{
    protected $table = 'produits';

    protected $fillable = [
        'nom',
        'prix',
        'stock',
        'description',
        'image',
        'date_fabrication',
        'statut',
        'categorie_id',
        'is_featured',
        'promotion_percentage',
        'promotion_title',
        'promotion_description',
    ];

    protected function casts(): array
    {
        return [
            'prix' => 'decimal:2',
            'promotion_percentage' => 'decimal:2',
            'date_fabrication' => 'date',
            'is_featured' => 'boolean',
        ];
    }

    public function categorie()
    {
        return $this->belongsTo(Categorie::class, 'categorie_id');
    }

    public function commandes()
    {
        return $this->belongsToMany(Commande::class, 'commande_produits', 'produit_id', 'commande_id')
            ->withPivot('quantite')
            ->withTimestamps();
    }

    public function paniers()
    {
        return $this->belongsToMany(Panier::class, 'produit_paniers', 'produit_id', 'panier_id')
            ->withPivot('quantite')
            ->withTimestamps();
    }

    public function reclamations()
    {
        return $this->hasMany(Reclamation::class, 'produit_id');
    }

    public function favoris()
    {
        return $this->belongsToMany(User::class, 'favoris', 'produit_id', 'user_id')->withTimestamps();
    }

    public function avis()
    {
        return $this->hasMany(Avis::class, 'produit_id');
    }

    public function prixPromo(): float
    {
        if (! $this->promotion_percentage) {
            return (float) $this->prix;
        }

        return (float) $this->prix - (((float) $this->promotion_percentage / 100) * (float) $this->prix);
    }
}
