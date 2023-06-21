<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApiSession extends Model
{
    protected $table = 'api_sessions';

    protected $fillable = ['session_id','user_id','active','login_time'];
}
