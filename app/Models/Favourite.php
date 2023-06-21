<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Favourite extends Model
{
    protected $table = 'favourites';
    protected $fillable = ['user_id','store_id'];
    
    public function StoreDetail()
    {
        return $this->belongsTo('App\Models\StoreProfile','store_id');    
    }
}
