<?php

namespace App\Models\Store;

use Illuminate\Database\Eloquent\Model;

class Parking extends Model
{
    protected $table = 'parkings';

    protected $fillable = ['store_id','parking_name','status'];
}
