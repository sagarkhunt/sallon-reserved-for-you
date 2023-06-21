<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Withdrwal extends Model
{
    protected $table = 'withdrwals';

    protected $fillable = ['user_id','store_id','transaction_id','amount','date','status'];

}
