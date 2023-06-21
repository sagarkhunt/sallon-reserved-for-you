<?php

namespace App\Http\Controllers\Api\User\Booking;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StoreEmp;
use App\Models\StoreEmpTimeslot;
use App\Models\ServiceAppoinment;
use App\Models\Service;
use App\Models\StoreTiming;
use App\Models\StoreEmpService;
use Carbon\Carbon;

class BookingController extends Controller
{
    //get service expert details    
    public function serviceExpertDetails(Request $request)
    {
        try {
            $data = request()->all();
            $expertDetail = StoreEmp::with('EmpTimeSlot','EmpCategory','EmpService')->where('id',$data['service_expert_id'])->first();
            $availableTime = [];
            //time slot
            foreach ($expertDetail->EmpTimeSlot as $value) { 
                $time = ServiceAppoinment::where('store_emp_id',$value['store_emp_id'])->where('store_id',$data['store_id'])->where('appo_time',$value['start_time'])->first();                             
                $time = $time == null ? '' : $time['appo_time'];            
                
                if (Carbon::parse($value['start_time']) == Carbon::parse($time)) {                    
                    $value['flag'] = 'Booked';
                }else{                    
                    $value['flag'] = 'Available';
                }
                $availableTime [] =$value;
            }
            //emp services            
            foreach ($expertDetail->EmpService as $row) {   

                $row['store_emp_id'] = $row['store_emp_id'];
                $row['service_id'] = @$row['EmpServiceDetails']['id'];
                $row['service_name'] = @$row['EmpServiceDetails']['service_name'];
                $row['price'] = @$row['EmpServiceDetails']['price'];
                $row['image'] = @$row['EmpServiceDetails']['image'];
                $row['service_image_path'] = @$row['EmpServiceDetails']['service_image_path'];                                
                unset($row->EmpServiceDetails);
                
            }   
            unset($expertDetail->EmpCategory);
            $data = [
                'serviceExpertDetail' => $expertDetail,                            
            ];
            return response()->json(['ResponseCode' => 1, 'ResponseText' => "Service Expert Details Get Successfully", 'ResponseData' => $data], 200);
        } catch (\Swift_TransportException $e) {
            \Log::debug($e);
            return response()->json(['ResponseCode' => 0, 'ResponseText' => 'Something went wrong', 'ResponseData' => null], 400);
        }
    }
    //Appoinment book
    public function bookingTimeAvailable(Request $request)
    {
        try {
            $data = request()->all();                        
            
            $day = \Carbon\Carbon::parse($data['date'])->format('l');                        
            
            // $expertTimeDetail = StoreEmp::with(['EmpTimeSlot' =>function($query) use($day){
            //                     $query->where('day',$day);
            //                     }])->where('id',$data['emp_id'])->first();            
            $empTime = StoreEmpTimeslot::where('store_emp_id',$data['emp_id'])->where('day',$day)->first();    
                     
            if (empty($empTime) || $empTime['is_off'] == 'on') {
                return response()->json(['ResponseCode' => 0, 'ResponseText' => "this day holiday", 'ResponseData' => NULL], 200);
            }
            $timeDuration = Service::where('id',$data['service_id'])->select('duration_of_service')->first();
            
            $ReturnArray = array ();// Define output            
            $StartTime    = strtotime ($empTime['start_time']); //Get Timestamp
            
            $EndTime      = strtotime ($empTime['end_time']); //Get Timestamp
            $AddMins  = $timeDuration['duration_of_service'] * 60;
            
            while ($StartTime <= $EndTime) //Run loop
            {
                $ReturnArray[] = date ("H:i:s", $StartTime);
                $StartTime += $AddMins; //Endtime check
            }
            
            $availableTime = [];
            //time slot
            foreach ($ReturnArray as $value) {                                                   
                if ($value == '0:00') {
                    return response()->json(['ResponseCode' => 0, 'ResponseText' => "store is closed now.", 'ResponseData' => NULL], 200);
                }
                $time = ServiceAppoinment::where(['store_emp_id' => $data['emp_id'],'service_id' => $data['service_id']])
                        ->where('appo_date',$data['date'])->where('appo_time',$value)                        
                        ->first();                   
                $time = $time == null ? '' : $time['appo_time'];            
                $flag = '';
                if (Carbon::parse($value) == Carbon::parse($time)) {                    
                    $flag = 'Booked';                    
                }else{     
                    $flag = 'Available';
                }
                $availableTime [] = [
                    'time' => $value,
                    'flag' => $flag
                ];
            }
            return response()->json(['ResponseCode' => 1, 'ResponseText' => "Successfully", 'ResponseData' => $availableTime], 200);
        } catch (\Swift_TransportException $e) {
            \Log::debug($e);
            return response()->json(['ResponseCode' => 0, 'ResponseText' => 'Something went wrong', 'ResponseData' => null], 400);
        }
    }
    //direct store booking time
    public function getAvailableTimeDirect(Request $request)
    {
        $data = $request->all();        
        $date = $data['date'];
        $service_id = $data['service_id'];
        // $time = \BaseFunction::bookingAvailableTime($data['date'], $data['service_id']);
        $day = \Carbon\Carbon::parse($date)->format('l');
        
        $getStoreId = Service::where('id', $service_id)->first();        
        $storeTime = StoreTiming::where('store_id', $getStoreId['store_id'])->where('day', $day)->first();

        if (empty($storeTime) || $storeTime['is_off'] == 'on') {
            return response()->json(['ResponseCode' => 0, 'ResponseText' => "this day holiday", 'ResponseData' => NULL], 200);
        }
        $ReturnArray = array();// Define output
        $StartTime = strtotime($storeTime['start_time']); //Get Timestamp
        
        $EndTime = strtotime($storeTime['end_time']); //Get Timestamp
        
        $AddMins = $getStoreId['duration_of_service'] * 60;
        
        while ($StartTime <= $EndTime) //Run loop
        {
            $ReturnArray[] = date("H:i:s", $StartTime);
            $StartTime += $AddMins; //Endtime check            
        }
        
        $availableTime = [];
            
        foreach ($ReturnArray as $value) {            
            if ($value == '00:00') {
                return response()->json(['ResponseCode' => 0, 'ResponseText' => "store is closed now.", 'ResponseData' => NULL], 200);
            }
            $time = ServiceAppoinment::where(['service_id' => $service_id])
                ->where('appo_date', $date)->where('appo_time', $value)
                ->first();

            $time = $time == null ? '' : $time['appo_time'];
            $flag = '';
            if (Carbon::parse($value) == Carbon::parse($time)) {
                $flag = 'Booked';
            } else {
                $flag = 'Available';
            }
            $availableTime [] = [
                'time' => $value,
                'flag' => $flag
            ];
        }        
        // return $availableTime;
        if (count($availableTime) > 0) {
            return response()->json(['ResponseCode' => 1, 'ResponseText' => "Successfully", 'ResponseData' => $availableTime], 200);            
        } else {
            return response()->json(['ResponseCode' => 0, 'ResponseText' => "No time available", 'ResponseData' => []], 200);            
        }
    }
    //get available employeee
    public function getAvailableEmpService(Request $request)
    {
        $data = $request->all();

        // $day = \Carbon\Carbon::parse($date)->format('l');

        $getStoreEmp = StoreEmpService::where('service_id', $data['service_id'])->pluck('store_emp_id')->all();
        
        $timeDuration = Service::where('id', $data['service_id'])->value('duration_of_service');
        $getServiceEmp = array();
        foreach ($getStoreEmp as $row){
            $empTime = StoreEmpTimeslot::where('store_emp_id', $row)->first();
            if(!empty($empTime)){             
                $employeeList = StoreEmp::where('id',$row)->first();
                $getServiceEmp[] = $employeeList;
            }

        }        
        if (count($getServiceEmp) > 0) {
            return response()->json(['ResponseCode' => 1, 'ResponseText' => "Successfully", 'ResponseData' => $getServiceEmp], 200);
        } else {
            return response()->json(['ResponseCode' => 0, 'ResponseText' => "No Emp available for this service", 'ResponseData' => []], 200);  
        }
    }
}
