<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StoreEmpService extends Model
{
    protected $table = 'store_emp_services';

    protected $fillable = ['store_emp_id','service_id','service_name'];

     //Emp Service 
     public function EmpServiceDetails()
     {
         return $this->belongsTo('App\Models\Service','service_id')->select('id','service_name','price','image');
     }
 }

