<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StoreProfile extends Model
{
    protected $table = 'store_profiles';

    protected $fillable = ['user_id', 'store_name', 'store_start_time', 'store_end_time', 'store_contact_number',
        'store_district', 'store_link_id', 'store_description', 'store_profile', 'store_banner', 'store_status',
        'store_address', 'payment_method', 'cancellation_deadline', 'appointment_contingent', 'zipcode', 'cancellation_day', 'category_id','is_value','slug','latitude','longitude','is_recommended','store_active_actual_plan','store_active_plan'];

    public function userDara()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }

    public function serviceDetails()
    {
        return $this->hasMany('App\Models\Service', 'store_id','id');
    }

    public function storeCategory()
    {
        return $this->hasMany('App\Models\StoreCategory', 'store_id','id')->select('id','store_id','category_id');
    }

    public function storeGallery()
    {
        return $this->hasMany('App\Models\StoreGallery', 'store_id','id');
    }

    protected  $appends = ['store_banner_image_path','store_profile_image_path'];

    public function getStoreBannerImagePathAttribute()
    {
        return $this->store_banner != null  ?  \URL::to('storage/app/public/store/banner/').'/'. $this->store_banner : Url('storage/default/logo-03.png');

    }

    public function getStoreProfileImagePathAttribute()
    {
        return $this->store_profile != null  ?  \URL::to('storage/app/public/store/').'/'. $this->store_profile : Url('storage/default/logo-03.png');

    }

    public function storeRated()
    {
        return $this->hasMany('App\Models\StoreRatingReview','store_id','id');
    }

    public function storeFavourite()
    {
        return $this->belongsTo('App\Models\Favourite','id','store_id');
    }

    public function SubCategoryData()
    {
        return $this->belongsTo('App\Models\Category', 'subcategory_id');
    }

    public function storeExpert()
    {
        return $this->hasMany('App\Models\StoreEmp', 'store_id','id');
    }
    public function storeOpeningHours()
    {
        return $this->hasMany('App\Models\StoreTiming', 'store_id','id');
    }

    public function userStoreRatedFlag()
    {
        return $this->belongsTo('App\Models\StoreRatingReview', 'id','store_id');
    }
}
