<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StoreEmpCategory extends Model
{
    protected $table = 'store_emp_categories';

    protected $fillable = ['store_emp_id','category_type','category_id'];

    public function EmpCategoryDetails()
     {
         return $this->belongsTo('App\Models\Category','category_id')->select('id','name');
     }

}
