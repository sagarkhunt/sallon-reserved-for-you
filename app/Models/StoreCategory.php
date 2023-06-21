<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StoreCategory extends Model
{
    protected $table = 'store_categories';

    protected $fillable = ['store_id','category_id'];

    public function CategoryData()
    {
        return $this->belongsTo('App\Models\Category', 'category_id');
    }

    public function CategoryWiseService()
    {
        return $this->hasMany('App\Models\Service', 'subcategory_id','category_id');
    }

    
}
