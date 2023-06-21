<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StoreEmpTimeslot extends Model
{
    protected $table = 'store_emp_timeslots';

    protected $fillable = ['store_emp_id','day','start_time','end_time','is_off'];
}
