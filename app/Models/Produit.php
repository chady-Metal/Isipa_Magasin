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
        'categorie_id'
          ];    

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
}
