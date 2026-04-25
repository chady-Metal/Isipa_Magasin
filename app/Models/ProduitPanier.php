<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProduitPanier extends Model
{
    protected $table = 'produit_paniers';
    protected $fillable = [
        'panier_id',
        'produit_id',
        'quantite'        ];
        public function panier()
        {
            return $this->belongsTo(Panier::class, 'panier_id');
}       
        public function produit()
        {
            return $this->belongsTo(Produit::class, 'produit_id');
        }
}

      