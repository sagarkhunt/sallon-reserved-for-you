<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{

    protected $fillable = [
        'store_id',
        'category_id',
        'subcategory_id',
        'service_name',
        'start_time',
        'end_time',
        'price',
        'discount',
        'start_date',
        'end_date',
        'image','status',
        'description',
        'duration_of_service',
        'discount_type',
        'is_popular'
    ];

    protected $table = 'services';

    public function CategoryData()
    {
        return $this->belongsTo('App\Models\Category', 'category_id');
    }

    public function SubCategoryData()
    {
        return $this->belongsTo('App\Models\Category', 'subcategory_id');
    }

    protected  $appends = ['service_image_path'];

    public function getServiceImagePathAttribute()
    {    
        return $this->image != null  ?  \URL::to('storage/app/public/service/').'/'. $this->image : Url('storage/default/logo-03.png'); 
        
    }

    public function storeDetaials()
    {
        return $this->belongsTo('App\Models\StoreProfile', 'store_id');
    }
}
