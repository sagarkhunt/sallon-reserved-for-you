<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MyWallet extends Model
{
    protected $table  = 'my_wallets';
    protected $fillable = ['user_id','store_id','wallet_amt','date','time'];
}
