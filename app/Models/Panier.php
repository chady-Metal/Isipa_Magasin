<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Panier extends Model
{
    protected $table = 'paniers';
    protected $fillable = [
        'user_id'
          ];
    
          public function user()
          {
              return $this->belongsTo(User::class, 'user_id');
          }

          public function produits()
          {
              return $this->belongsToMany(Produit::class, 'produit_paniers', 'panier_id', 'produit_id')
                          ->withPivot('quantite')
                          ->withTimestamps();
          }
}
