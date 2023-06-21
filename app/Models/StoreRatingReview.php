<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StoreRatingReview extends Model
{
    protected $table = 'store_rating_reviews';

    protected $fillable = ['user_id','store_id','service_rate','ambiente','preie_leistungs_rate','wartezeit','atmosphare','write_comment','total_avg_rating'];

    public function userDetails()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }
}
