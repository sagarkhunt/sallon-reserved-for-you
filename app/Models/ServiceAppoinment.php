<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceAppoinment extends Model
{
    protected $table = 'service_appoinments';

    protected $fillable = ['user_id','store_id','store_emp_id','service_id','service_name','appo_date','appo_time','is_postponed','cancel_reson','order_id','price','first_name','last_name','email','phone_number','appointment_type','status'];

    public function employeeDetails()    {
        return $this->belongsTo('App\Models\StoreEmp', 'store_emp_id');
    }

    public function serviceDetails()    {
        return $this->belongsTo('App\Models\Service', 'service_id');
    }

    public function storeDetails()    {
        return $this->belongsTo('App\Models\StoreProfile', 'store_id');
    }

    public function userDetails()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }

    public function orderInfo()
    {
        return $this->belongsTo('App\Models\PaymentMethodInfo', 'id','appoinment_id');
    }

    public function orderExpert()
    {
        return $this->belongsTo('App\Models\StoreEmp', 'store_emp_id','id');
    }

    public function orderServiceDetails()
    {
        return $this->belongsTo('App\Models\Service','service_id','id')->select('id','service_name','image','price');
    }
}
