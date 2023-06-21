<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    protected $table = 'payment_methods';

    protected $fillable = ['user_id','payment_method','card_number','card_holder_name','card_holder_email','postal_code','ex_date','ex_year','payment_token'];
}
