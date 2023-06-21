<?php

namespace App\Http\Controllers\Api\ServiceProvider\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use App\Models\ApiSession;
use App\Models\ServiceAppoinment;
use App\Models\Service;
use App\Models\StoreProfile;
use App\Models\StoreEmp;
use Validator;
use URL;
use Mail;
use File;
use Exception;
use Carbon\Carbon;


class DashboardController extends Controller
{
    public function dashboard(Request $request)
    {
        try {
                $data = request()->all();
                
                // $store = StoreProfile::where('user_id',$request['user']['user_id'])->pluck('id');        
                $store = $data['store_id']; 
                
                $ServiceAppoinment = ServiceAppoinment::where('store_id',$store)->get();
                // activeactive
                $activeAppoinment = $ServiceAppoinment->where('store_id',$store)->where('status','running')->count();        
                //get pending
                $pendingAppoinment = $ServiceAppoinment->where('store_id',$store)->where('status','booked')->count();
                
                //total service
                $totalService = Service::where('store_id',$store)->count();
                
                //today Service appoinment
                $todayDate = Carbon::now()->format('Y-m-d');
                
                $todayAppoinment = ServiceAppoinment::with('employeeDetails','serviceDetails','orderInfo')->where('store_id',$store)->where('appo_date',$todayDate)->get();             
                
                $todatEarning = 0;
                foreach ($todayAppoinment as $row) {
                    $row['emp_name']                = @$row['employeeDetails']['emp_name'];
                    $row['emp_image']               = @$row['employeeDetails']['emp_image_path'];
                    // $row['order_id']                = @$row['orderInfo']['order_id'];
                    $row['service_name']            = $row['serviceDetails']['service_name'];
                    $row['price']                   = $row['serviceDetails']['price'];
                    $row['service_image_path']      = $row['serviceDetails']['service_image_path'];
                    $todatEarning                  += @$row['orderInfo']['total_amount'];
                    unset($row->employeeDetails,$row->serviceDetails,$row->orderInfo);
                }
                //get total stylish            
                $totalStylish = StoreEmp::where('store_id',$store)->get();
                $totalEmployee = $totalStylish->count();

                //set data object
                $data = [
                    'activeAppoinment'  => $activeAppoinment,
                    'pendingAppoinment' => $pendingAppoinment,
                    'totalService'      => $totalService,
                    'totalEmployee'     => $totalEmployee,                
                    'totalEarning'      => number_format($todatEarning ,2),
                    'todayAppoinment'   => $todayAppoinment->isEmpty() ? NULL : $todayAppoinment,
                    'Statistics'        => NULL,
                    'Stylish'           => $totalStylish
                    
                ];
                return response()->json(['ResponseCode' => 1, 'ResponseText' => 'Successful', 'ResponseData' => $data], 200);
        } catch (\Swift_TransportException $e) {
            \Log::debug($e);
            return response()->json(['ResponseCode' => 0, 'ResponseText' => 'Something went wrong', 'ResponseData' => null], 400);
        }
        
    }
}
