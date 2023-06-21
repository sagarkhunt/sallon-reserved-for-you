<?php

namespace App\Http\Controllers\Api\ServiceProvider\Appointment;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use URL;
use Mail;
use File;
use Exception;
use Carbon\Carbon;
use App\Models\ServiceAppoinment;
use App\Models\StoreEmpTimeslot;
use App\Models\StoreEmp;
use App\Models\Service;
use App\Models\StoreTiming;
use App\Models\StoreEmpService;
use App\Models\User;
use App\Models\PaymentMethodInfo;


class AppointmentController extends Controller
{
    //appoinmentList
    public function appoinmentList(Request $request)
    {
        try {
            $data = request()->all();
            
            $userId  = $request['user']['user_id'];
            // $storeId = \BaseFunction::getStoreDetails($userId);     
            $storeId = $data['store_id'];
            $date = \Carbon\Carbon::now()->format('Y-m-d');
            
            $getAppoinmentList = ServiceAppoinment::with('userDetails','employeeDetails','serviceDetails','orderInfo')->where('store_id',$storeId)
                                    ->whereDate('appo_date', '>=', $date)
                                    ->WhereNotIn('status', ['cancel', 'completed'])
                                    ->orderBy('appo_date', 'DESC');
            
            if(isset($data['date']) && $data['date'] != ''){
                $getAppoinmentList = $getAppoinmentList->whereDate('appo_date','=',$data['date']);
            }
            $getAppoinmentList = $getAppoinmentList->get();
            
            foreach ($getAppoinmentList as $value) {  
                
                $value['user_name']         = $value['userDetails'] == null ? null : $value['userDetails']['first_name'].' '.$value['userDetails']['last_name'];
                $value['user_image_ path']  = $value['userDetails'] == null ? null : $value['userDetails']['user_image_path'];
                $value['service_name']      = $value['serviceDetails']['service_name'];
                $value['service_image']     = $value['serviceDetails']['service_image_path'];
                $value['service_price']     = $value['serviceDetails']['price'];
                $value['service_descount']  = $value['serviceDetails']['discount'];
                $value['service_descount_type']  = $value['serviceDetails']['discount_type'];
                $value['service_final_price'] = number_format(\BaseFunction::finalPrice($value['serviceDetails']['id']),2);
                $value['expert_id']         = @$value['employeeDetails'] == null ? null :$value['employeeDetails']['id'];
                $value['expert_name']       = @$value['employeeDetails'] == null ? null :$value['employeeDetails']['emp_name'];
                $value['expert_image']      = @$value['employeeDetails'] == null ? null :$value['employeeDetails']['emp_image_Path'];
                // $value['order_id']          = @$value['orderInfo']['order_id'];
                $value['total_paid']        = @$value['orderInfo']['total_amount'];
                $value['appo_date']         = date('M j, Y', strtotime($value['appo_date']));   
                $value['appo_time']         = date('h:i:s A', strtotime($value['appo_time']));   
                ;
                unset($value->userDetails,$value->employeeDetails,$value->serviceDetails,$value->orderInfo);
            }
            if ($getAppoinmentList->isEmpty()) {
                return response()->json(['ResponseCode' => 0, 'ResponseText' => 'No data found', 'ResponseData' => NULL], 200);
            }
            $data = [
                'total_apppinment' => $getAppoinmentList->count(),
                'getAppoinmentList' => $getAppoinmentList

            ];
            return response()->json(['ResponseCode' => 1, 'ResponseText' => 'Successful', 'ResponseData' => $data], 200);
        } catch (\Swift_TransportException $e) {
            \Log::debug($e);
            return response()->json(['ResponseCode' => 0, 'ResponseText' => 'Something went wrong', 'ResponseData' => null], 400);
        }
    }
    //order history
    public function orderList(Request $request)
    {
        try {
            $data = request()->all();
            
            $userId  = $request['user']['user_id'];
            // $storeId = \BaseFunction::getStoreDetails($userId); 
            $storeId = $data['store_id'];
            $date = \Carbon\Carbon::now()->format('Y-m-d');
            $orderHistoryList = ServiceAppoinment::with('userDetails','employeeDetails','serviceDetails','orderInfo')->where('store_id',$storeId)
                                ->where(function ($query) use ($date) {
                                    $query->wheredate('appo_date', '<', $date)
                                        ->orWhereIn('status', ['cancel', 'completed']);
                                })->orderBy('appo_date', 'DESC');
            if (isset($data['date']) && $data['date'] != '') {
                $orderHistoryList = $orderHistoryList->whereDate('appo_date',$data['date']);
            }
            $orderHistoryList= $orderHistoryList->orderBy('id','DESC')->get();
            foreach ($orderHistoryList as $value) {  
                
                $value['user_name']         = $value['userDetails'] == null ? null : $value['userDetails']['first_name'].' '.$value['userDetails']['last_name'];
                $value['user_image_ path']  = $value['userDetails'] == null ? null : $value['userDetails']['user_image_path'];
                $value['service_name']      = $value['serviceDetails']['service_name'];
                $value['service_image']     = $value['serviceDetails']['service_image_path'];
                $value['service_price']     = $value['serviceDetails']['price'];
                $value['service_descount_type']  = $value['serviceDetails']['discount_type'];
                $value['service_final_price'] = number_format(\BaseFunction::finalPrice($value['serviceDetails']['id']),2);
                $value['service_descount']  = $value['serviceDetails']['discount'];
                $value['expert_id']         = @$value['employeeDetails'] == null ? null :$value['employeeDetails']['id'];
                $value['expert_name']       = @$value['employeeDetails'] == null ? null :$value['employeeDetails']['emp_name'];
                $value['expert_image']      = @$value['employeeDetails'] == null ? null :$value['employeeDetails']['emp_image_Path'];
                // $value['order_id']          = @$value['orderInfo']['order_id'];
                $value['total_paid']        = @$value['orderInfo']['total_amount'];
                $value['appo_date']         = date('M j, Y', strtotime($value['appo_date']));   
                $value['appo_time']         = date('h:i:s A', strtotime($value['appo_time']));   
                ;
                unset($value->userDetails,$value->employeeDetails,$value->serviceDetails,$value->orderInfo);
            }
            if ($orderHistoryList->isEmpty()) {
                return response()->json(['ResponseCode' => 0, 'ResponseText' => 'No data found', 'ResponseData' => NULL], 200);

            }
            $data = [
                'total_apppinment' => $orderHistoryList->count(),
                'orderHistoryList' => $orderHistoryList

            ];
            return response()->json(['ResponseCode' => 1, 'ResponseText' => 'Successful', 'ResponseData' => $data], 200);
        }catch (\Swift_TransportException $e) {
            \Log::debug($e);
            return response()->json(['ResponseCode' => 0, 'ResponseText' => 'Something went wrong', 'ResponseData' => null], 400);
        }
    }

    //appoinmentDatePostpone
    public function appoinmentDatePostpone(Request $request)
    {
        $rule = [
            'appo_date' => 'required',                       
        ];

        $message = [
            'appo_date.required' => 'appo_date is required',            
        ];

        $validate = Validator::make($request->all(), $rule, $message);

        if ($validate->fails()) {
            return response()->json(['ResponseCode' => 0, 'ResponseText' => 'Validation Fails', 'ResponseData' => $validate->errors()->all()], 422);
        }
        try {
            $data = request()->all(); 
                    
            $userId = $data['user']['user_id'];               
            $updateDate = ServiceAppoinment::where('id',$data['appo_id'])->update(['appo_date'=>$data['appo_date'],'appo_time' => $data['appo_time'],'status'=>'reschedule']);
            if ($updateDate) {
                return response()->json(['ResponseCode' => 1, 'ResponseText' => 'Date Postpone successfully.', 'ResponseData' => null], 200);
            }
            return response()->json(['ResponseCode' => 0, 'ResponseText' => 'Something went wrong', 'ResponseData' => null], 400);
        } catch (\Swift_TransportException $e) {
            \Log::debug($e);
            return response()->json(['ResponseCode' => 0, 'ResponseText' => 'Something went wrong', 'ResponseData' => null], 400);
        }
    }

    //apponment cancel
    public function appoinmentCancel(Request $request)
    {
        try {
            $data = request()->all();
            $userId = $data['user']['user_id'];
            $appointment_id = $request['appo_id'];
            $message = $request['cancel_reason'];              
            $updateStatus = ServiceAppoinment::where('id',$appointment_id)->update(['status' => $data['status'],'cancel_reson' => $message]);            
            if ($updateStatus) {
                $payment = PaymentMethodInfo::where('appoinment_id', $appointment_id)->first();
                if (!empty($payment)) {
                    if ($payment['payment_method'] == 'stripe' || $payment['payment_method'] == 'klarna') {
                        $stripe = new \Stripe\StripeClient('sk_test_hHiwaXoEWblwzGpUgMsR9W0M00GwOAS2NB');
                        if ($payment['payment_method'] == 'stripe') {
                            $refund = $stripe->refunds->create([
                                'charge' => $payment['payment_id'],
                            ]);
                            $updatePayment = PaymentMethodInfo::where('id', $payment['id'])->update(['refund_id' => $refund['id'], 'status' => 'refund']);
                        } elseif ($payment['payment_method'] == 'klarna') {
                            $refund = $stripe->refunds->create([
                                'payment_intent' => $payment['payment_id'],
                            ]);
                            $updatePayment = PaymentMethodInfo::where('id', $payment['id'])->update(['refund_id' => $refund['id'], 'status' => 'refund']);
                        }
                    }
                }
                return response()->json(['ResponseCode' => 1, 'ResponseText' => 'Appoinment cancel successfully.', 'ResponseData' => null], 200);
            }
            return response()->json(['ResponseCode' => 0, 'ResponseText' => 'Something went wrong', 'ResponseData' => null], 400);
        } catch (\Swift_TransportException $e) {
            \Log::debug($e);
            return response()->json(['ResponseCode' => 0, 'ResponseText' => 'Something went wrong', 'ResponseData' => null], 400);
        }
    }

     /**
      * check booking time available
      */
     public function bookingTimeAvailable(Request $request)
     {
         try {
             $data = request()->all();                        
             
            $day = \Carbon\Carbon::parse($data['date'])->format('l');                        
                
             // $expertTimeDetail = StoreEmp::with(['EmpTimeSlot' =>function($query) use($day){
             //                     $query->where('day',$day);
             //                     }])->where('id',$data['emp_id'])->first();            
             $empTime = StoreEmpTimeslot::where('store_emp_id',$data['emp_id'])->where('day',$day)->first();             
             if(empty($empTime)){
                return response()->json(['ResponseCode' => 0, 'ResponseText' => "Worng emp id", 'ResponseData' => NULL], 400);
             }
            if ($empTime['is_off'] == 'on') {
                 return response()->json(['ResponseCode' => 0, 'ResponseText' => "this day holiday", 'ResponseData' => NULL], 200);
             }
            $timeDuration = Service::where('id',$data['service_id'])->select('duration_of_service')->first();
            if (empty($timeDuration)) {
                return response()->json(['ResponseCode' => 0, 'ResponseText' => 'Worng service id.', 'ResponseData' => ''], 200);
            } 
             
             $ReturnArray = array ();// Define output            
             $StartTime    = strtotime ($empTime['start_time']); //Get Timestamp
             
             $EndTime      = strtotime ($empTime['end_time']); //Get Timestamp
             $AddMins  = $timeDuration['duration_of_service'] * 60;
             
             while ($StartTime <= $EndTime) //Run loop
             {
                 $ReturnArray[] = date ("H:i", $StartTime);
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

     /**
      * direct booking time available for store
      */

    public function getAvailableTimeDirectStore(Request $request)
    {
        $data = $request->all();               
        $date = $data['date'];
        $service_id = $data['service_id'];
        // $time = \BaseFunction::bookingAvailableTime($data['date'], $data['service_id']);
        $day = \Carbon\Carbon::parse($date)->format('l');
        
        $getStoreId = Service::where('id', $service_id)->first(); 
        
        if (empty($getStoreId)) {
            return response()->json(['ResponseCode' => 0, 'ResponseText' => 'Worng service id.', 'ResponseData' => ''], 200);
        }       
        
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

    /**
     * Add new appoinment for service provider
    */
    public function providerAddNewAppoinment(Request $request)
    {
        $rule = [
            'customer_name' => 'required',
            'customer_email' =>'required'                       
        ];

        $message = [
            'customer_name.required' => 'customer_name is required',            
            'customer_email.required' => 'customer_email is required',            
        ];

        $validate = Validator::make($request->all(), $rule, $message);

        if ($validate->fails()) {
            return response()->json(['ResponseCode' => 0, 'ResponseText' => 'Validation Fails', 'ResponseData' => $validate->errors()->all()], 422);
        }
        try {
            $data = request()->all();
            $names = explode(" ", $data['customer_name']);
            
            $serviceDetails = Service::where('id', $data['service_id'])->first();
            if (empty($serviceDetails) && !empty($data['service_id'])) {
                return response()->json(['ResponseCode' => 0, 'ResponseText' => 'Worng service id.', 'ResponseData' => ''], 200);
            }            
            
            $amount = \BaseFunction::finalPrice($serviceDetails['id']);
            
            $userDetails = User::with('userAddress')->where('id',$data['user']['user_id'])->first();
            
            $checkEmpId =StoreEmp::where('id',$data['emp_id'])->first();
            if (empty($checkEmpId) && !empty($data['emp_id'])) {
                return response()->json(['ResponseCode' => 0, 'ResponseText' => 'Worng emp id.', 'ResponseData' => ''], 200);
            }            
            $newAppointmentData['user_id'] = $data['user']['user_id'];
            $newAppointmentData['store_id'] = $data['store_id'];
            $newAppointmentData['store_emp_id'] = $data['emp_id'];
            $newAppointmentData['service_id'] = $data['service_id'];
            $newAppointmentData['service_name'] = $serviceDetails['service_name'];
            $newAppointmentData['appo_date'] = \Carbon\Carbon::parse($data['app_date']);
            $newAppointmentData['appo_time'] = \Carbon\Carbon::parse($data['app_time'])->format('H:i:s');
            $newAppointmentData['status'] = 'booked';
            $newAppointmentData['order_id'] = \BaseFunction::orderNumber();
            $newAppointmentData['price'] = $amount;            
            $newAppointmentData['first_name'] = isset($names[0]) == false ? '' : $names[0];
            $newAppointmentData['last_name'] = isset($names[1]) == false ? '' : $names[1];
            $newAppointmentData['email'] = $data['customer_email']; 
            $newAppointmentData['phone_number'] = NULL;
            $newAppointmentData['appointment_type'] = 'service provider';

            $newAppointment = new ServiceAppoinment();
            $newAppointment->fill($newAppointmentData);
            if ($newAppointment->save()) {                            
                // $paymentinfo['user_id'] = $data['user']['user_id'];
                // $paymentinfo['store_id'] = $newAppointment['store_id'];
                // $paymentinfo['service_id'] = $newAppointment['service_id'];
                // $paymentinfo['order_id'] = $newAppointment['order_id'];
                // $paymentinfo['payment_id'] = NULL;
                // $paymentinfo['total_amount'] = $newAppointment['price'];                
                // $paymentinfo['status'] = 'pending';                

                // $paymentinfo['appoinment_id'] = $newAppointment['id'];
                // $paymentinfo['payment_method'] = 'cash';
                // $paymentinfo['payment_type'] = NULL;

                // $paymentDatas = new PaymentMethodInfo();
                // $paymentDatas->fill($paymentinfo);
                // $paymentDatas->save();
                return response()->json(['ResponseCode' => 1, 'ResponseText' => 'New Appoinment Added Successful!', 'ResponseData' => true], 200);
            }else{
                return response()->json(['ResponseCode' => 0, 'ResponseText' => 'Something went wrong.', 'ResponseData' => ''], 200);
            }
        } catch (\Swift_TransportException $e) {
            \Log::debug($e);
            return response()->json(['ResponseCode' => 0, 'ResponseText' => 'Something went wrong', 'ResponseData' => null], 400);
        }
    }

    /**
     * get store employee
     */

    public function getEmployee(Request $request)
    {
        try {
            $data = request()->all();
            $employeeList = StoreEmp::where('store_id',$data['store_id'])->select('id','emp_name','image')->get();
            if ($employeeList->count() <= 0) {
                return response()->json(['ResponseCode' => 0, 'ResponseText' => 'No data found', 'ResponseData' => NULL], 200);
            }
            return response()->json(['ResponseCode' => 1, 'ResponseText' => 'Successful', 'ResponseData' => $employeeList], 200);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    
}
