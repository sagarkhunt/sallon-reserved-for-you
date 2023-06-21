<?php

namespace App\Http\Controllers\ServiceProvider;

use App\Http\Controllers\Controller;
use App\Models\PaymentMethodInfo;
use App\Models\Service;
use App\Models\ServiceAppoinment;
use App\Models\StoreEmp;
use App\Models\StoreProfile;
use App\Models\StoreRatingReview;
use Illuminate\Http\Request;
use Auth;

class DashboardController extends Controller
{
    public function index(){
        $store_id = session('store_id');

        if(empty($store_id)){
            $getStore = StoreProfile::where('user_id', Auth::user()->id)->pluck('id')->all();
        } else{
            $getStore = StoreProfile::where('user_id', Auth::user()->id)->where('id',$store_id)->pluck('id')->all();
        }

        $activeAppointment = ServiceAppoinment::whereIn('store_id',$getStore)->where('status','running')->count();
        $pendingAppointment = ServiceAppoinment::whereIn('store_id',$getStore)->where('status','booked')->count();
        $completedAppointment = ServiceAppoinment::whereIn('store_id',$getStore)->where('status','completed')->count();
        $canceledAppointment = ServiceAppoinment::whereIn('store_id',$getStore)->where('status','cancel')->count();
        $totalService = Service::whereIn('store_id',$getStore)->count();
        $totalEmp = StoreEmp::whereIn('store_id',$getStore)->get();
        $totalReview = StoreRatingReview::whereIn('store_id',$getStore)->count();
        $totalCustomer = ServiceAppoinment::whereIn('store_id',$getStore)->count();


        $date = \Carbon\Carbon::now()->format('Y-m-d');
        $month = \Carbon\Carbon::now()->format('m');
        $year = \Carbon\Carbon::now()->format('Y');

        $todayEarning = ServiceAppoinment::whereIn('store_id',$getStore)->whereDate('appo_date',$date)->where('status','completed')->sum('price');


        $tAppo = ServiceAppoinment::whereIn('store_id',$getStore)->whereNotIn('status',['completed','cancel','pending'])->whereDate('appo_date',$date)->get();
        $todayAppointment = [];
        foreach ($tAppo as $row){
            $row->payment_method = PaymentMethodInfo::where('appoinment_id',$row->id)->value('payment_method');
            if(!empty($row->user_id)){
                $row->customer = $row->userDetails->first_name.' '.$row->userDetails->last_name;
            } else {
                $row->customer = $row->first_name.' '.$row->last_name;
            }

            $todayAppointment[$row->appo_time][] = $row;
        }
        ksort($todayAppointment);

        /**
         * Day Chart Data
         */
        $appointmentDay = ServiceAppoinment::whereIn('store_id',$getStore)->whereNotIn('status',['cancel','pending'])->whereDate('appo_date',$date)->get();
        $dayas = [];
        foreach ($appointmentDay as $row){
            $row->time = \Carbon\Carbon::parse($row->appo_time)->format('H');
            $dayas[$row->time][] = $row;
        }
        ksort($dayas);
        $dayData = [];
        for($i = 1;$i<=24;$i++){
            if(array_key_exists(date('H', mktime($i, 0, 0, 0, 0)),$dayas)){
                $dayData[] = array('time'=>$i,'count'=>count($dayas[date('H', mktime($i, 0, 0, 0, 0))]),'month'=>$i);
            } else {
                $dayData[] = array('time'=>$i,'count'=>0,'month'=>$i );
            }
        }

        /**
         * Month Chart Data
         */
        $appointmentMonth = ServiceAppoinment::whereIn('store_id',$getStore)->whereNotIn('status',['cancel','pending'])->whereMonth('appo_date','=',$month)->get();
        $monthas = [];
        foreach ($appointmentMonth as $row){
            $row->days = \Carbon\Carbon::parse($row->appo_date)->format('d');
            $monthas[$row->days][] = $row;
        }
        ksort($monthas);
        $monthData = [];
        for($i = 1;$i<=31;$i++){
            if(array_key_exists(date('d', mktime(0, 0, 0, 0, $i)),$monthas)){
                $monthData[] = array('time'=>$i,'count'=>count($monthas[date('d', mktime(0, 0, 0, 0, $i))]),'month'=>$i.'-'.$month);
            } else {
                $monthData[] = array('time'=>$i,'count'=>0,'month'=>$i.'-'.$month );
            }
        }


        /**
         * Year Chart Data
         */
        $appointmentMonth = ServiceAppoinment::whereIn('store_id',$getStore)->whereNotIn('status',['cancel','pending'])->whereYear('appo_date','=',$year)->get();
        $yearas = [];
        foreach ($appointmentMonth as $row){
            $row->month = \Carbon\Carbon::parse($row->appo_date)->format('m');
            $yearas[$row->month][] = $row;
        }
        ksort($yearas);
        $yearData = [];

        for($i=1;$i<=12;$i++){
            if(array_key_exists(date('m', mktime(0, 0, 0, $i, 10)),$yearas)){
                $yearData[] = array('time'=>$i,'count'=>count($yearas[date('m', mktime(0, 0, 0, $i, 10))]),'month'=>date('F', mktime(0, 0, 0, $i, 10)) );
            } else {
                $yearData[] = array('time'=>$i,'count'=>0,'month'=>date('F', mktime(0, 0, 0, $i, 10)) );
            }
        }

        return view('ServiceProvider.dashboard',compact('activeAppointment','pendingAppointment','completedAppointment','canceledAppointment','totalCustomer','totalCustomer','totalEmp',
        'totalService','totalReview','todayAppointment','dayData','monthData','yearData','todayEarning'));
    }

    public function setSession(Request $request){
        $id = $request['id'];

        $request->session()->put('store_id',$id);


        return true;
    }
}
