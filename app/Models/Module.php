<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    protected $fillable = ['role_id','modules'];
    public function role(){
        return $this->hasOne(Role::class, 'id','role_id');
    }
}
