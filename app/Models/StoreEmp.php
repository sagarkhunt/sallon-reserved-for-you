<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StoreEmp extends Model
{
    protected $table = 'store_emps';

    protected $fillable = ['store_id','emp_name','country','image','status'];

    protected  $appends = ['emp_image_path'];

    public function getEmpImagePathAttribute()
    {

        return $this->image != null  ?  \URL::to('storage/app/public/employeeImage/').'/'. $this->image : Url('storage/default/logo-03.png');

    }
    //emp time slote
    public function EmpTimeSlot()
    {
        return $this->hasMany('App\Models\StoreEmpTimeslot','store_emp_id');
    }

    //category
    public function EmpCategory()
    {
        return $this->hasMany('App\Models\StoreEmpCategory','store_emp_id','id')->select('id','store_emp_id','category_type','category_id');
    }

    public function EmpService()
    {
        return $this->hasMany('App\Models\StoreEmpService','store_emp_id','id')->select('id','store_emp_id','service_id');
    }

    //store data
    public function storeDetaials()
    {
        return $this->belongsTo('App\Models\StoreProfile', 'store_id')->select('id','user_id','store_name','store_start_time','store_end_time','store_district');
    }

    public function EmpDayTiming()
    {
        return $this->belongsTo('App\Models\StoreEmpTimeslot','store_emp_id');

    }
}
