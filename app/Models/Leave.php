<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Leave extends Model
{
    protected $table ='leave_requests';

      public function User(){
        return $this->hasMany(User::class,'id','employee_id');
    }
}
