<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attribution extends Model
{
    protected $table = 'attributions';
    protected $fillable = [
        'role_id',
        'permissions_id'
          ];

            public function role()
            {
                return $this->belongsTo(Role::class, 'role_id');
            }
            public function permission()
            {
                return $this->belongsTo(Permission::class, 'permissions_id');
            }
    
}
