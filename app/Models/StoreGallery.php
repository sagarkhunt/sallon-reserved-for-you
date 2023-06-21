<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StoreGallery extends Model
{
    protected $table = 'store_galleries';

    protected $fillable = ['store_id','file','file_type'];   

    protected  $appends = ['store_gallery_image_path'];

    public function getStoreGalleryImagePathAttribute()
    {    
        return $this->file != null  ?  \URL::to('storage/app/public/store/gallery/').'/'. $this->file : Url('storage/default/logo-03.png'); 
        
    }
}
