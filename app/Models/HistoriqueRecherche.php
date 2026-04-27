<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HistoriqueRecherche extends Model
{
    protected $table = 'historiques_recherche';

    protected $fillable = [
        'user_id',
        'terme_recherche',
        'categorie',
        'resultats',
    ];
}
