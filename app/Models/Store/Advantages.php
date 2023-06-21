<?php

namespace App\Models\Store;

use Illuminate\Database\Eloquent\Model;

class Advantages extends Model
{
    protected $table = 'store_advantages';

    protected $fillable = ['store_id','title','description','image','status'];

    protected  $appends = ['store_advantage_image_path'];

    public function getStoreAdvantageImagePathAttribute()
    {
        return $this->image != null  ?  \URL::to('storage/app/public/store/advantage/').'/'. $this->image : Url('storage/default/logo-03.png');

    }
}
