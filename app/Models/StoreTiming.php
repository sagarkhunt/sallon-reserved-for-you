<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StoreTiming extends Model
{
    protected $table = 'store_timings';

    protected $fillable = ['store_id','day','start_time','end_time','is_off'];
}
