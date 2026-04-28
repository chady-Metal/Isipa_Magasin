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

    protected function casts(): array
    {
        return [
            'date_paiement' => 'date',
            'montant' => 'decimal:2',
        ];
    }

          public function commande()
          {
              return $this->belongsTo(Commande::class, 'commande_id');
          }
    //
}
