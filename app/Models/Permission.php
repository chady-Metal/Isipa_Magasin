<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    protected $table = 'permissions';

    protected $fillable = [
        'nom',
        'description',
    ];

    public function attributions()
    {
        return $this->hasMany(Attribution::class, 'permissions_id');
    }
}
