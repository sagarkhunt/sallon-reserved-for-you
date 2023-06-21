<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contactus extends Model
{
    protected $table = 'contactuses';

    protected $fillable = ['user_id','name','email','message'];
}
