<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plans extends Model
{
    protected $table = 'plans';

    protected $fillable = ['plan_name','plan_description','price','recruitment_fee','tax_fee','is_management_tool',
        'is_profile_design','is_booking_system','is_ads_newsletter','is_social_media_platforms','status','plan_actual_name','slug','slug_actual_plan'];

}
