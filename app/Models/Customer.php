<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Auth;


class Customer extends Authenticatable
{
    protected $guard = 'customers';

    protected $fillable = ['username','email','password','company_name','website','address1','address2','state','zip_code','full_name','mobile_number','city','city','country','fax'];

    public function permissions(){
        return $this->hasOne(CustomerPermissions::class,'customer_id','id');
    }

    public function sectionCheck($value){
        $sections = explode(";", \Illuminate\Support\Facades\Auth::guard('customer')->user()->permissions->permissions);
        if(in_array($value, $sections)) {
            return true;
        } else {
            return false;
        }
    }
}
