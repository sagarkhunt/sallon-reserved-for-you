<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentMethodInfo;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index(){
        $data = PaymentMethodInfo::orderBy('id','DESC')->get();

        return view('Admin.Payment.index',compact('data'));
    }
}
