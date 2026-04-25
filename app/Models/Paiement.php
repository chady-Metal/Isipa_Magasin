<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Paiement extends Model
{
    protected $table = 'paiements';
    protected $fillable = [           
        'montant',
        'date_paiement',
        'methode_paiement',
        'commande_id',
        'numero_compte',
        'reference_transaction'
          ];

          public function commande()
          {
              return $this->belongsTo(Commande::class, 'commande_id');
          }
    //
}
