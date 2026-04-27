<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $table = 'roles';

    protected $fillable = [
        'nom',
        'description',
    ];

    public function attributions()
    {
        return $this->hasMany(Attribution::class, 'role_id');
    }

    public function users()
    {
        return $this->hasMany(User::class, 'roles_id');
    }
}
