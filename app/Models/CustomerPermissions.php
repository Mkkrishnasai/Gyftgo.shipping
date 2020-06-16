<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerPermissions extends Model
{
    protected $fillable = ['customer_id', 'permissions'];
}
