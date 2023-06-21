<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentMethodInfo;
use App\Models\ServiceAppoinment;
use App\Models\StoreProfile;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(){
        $totalRevenue = PaymentMethodInfo::where('status','succeeded')->sum('total_amount');
        $totalStore = StoreProfile::count();
        $totalAppointment = ServiceAppoinment::where('status','!=','cancel')->count();
        $totalUser = User::leftjoin('role_user', 'role_user.user_id', '=', 'users.id')
            ->where('role_user.role_id', 2)->count();
        return view('Admin.dashboard',compact('totalAppointment','totalRevenue','totalStore','totalUser'));
    }
}
