<?php

namespace App\Models\Store;

use Illuminate\Database\Eloquent\Model;

class PublicTransportation extends Model
{
    protected $table = 'public_transportations';

    protected $fillable = ['store_id','title','transportation_no','image','status'];

    protected  $appends = ['transportation_image_path'];

    public function getTransportationImagePathAttribute()
    {
        return $this->image != null  ?  \URL::to('storage/app/public/store/transportation/').'/'. $this->image : Url('storage/default/logo-03.png');

    }
}
