<?php

namespace App\Http\Controllers\Api\User\MyOrder;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use URL;
use Mail;
use Exception;
use Carbon\Carbon;
use App\Models\ServiceAppoinment;
use App\Models\PaymentMethodInfo;

class OrderController extends Controller
{
    /**
     * order  list
     *  */    

    public function orderList(Request $request)
    {
        try {
            $data = request()->all();
            
            $userId = $data['user']['user_id'];
            $date = \Carbon\Carbon::now()->format('Y-m-d');
            $orderList = ServiceAppoinment::with('orderInfo','orderExpert','orderExpert.EmpCategory','orderServiceDetails')->where('user_id',$userId);            
            // dd($bookingserviceList);            
    		if (request('status') == 'upcoming') {    			
                $orderList = $orderList->whereDate('appo_date', '>=', $date)->WhereNotIn('status',['cancel','completed'])
                            ->orderBy('appo_date','DESC');
            }

            if (request('status') == 'recent') {                   
                $orderList = $orderList->where(function ($query) use ($date) {
                    $query->wheredate('appo_date', '<', $date)
                        ->orWhereIn('status',['cancel','completed']);
                })
                ->orderBy('appo_date','DESC');
            }
    		$orderList = $orderList->get();

    		if (count($orderList) <= 0 ) {
                return response()->json(['ResponseCode' => 0, 'ResponseText' => 'No data found', 'ResponseData' => null], 200);
            }
            foreach ($orderList as  $value) {                   
                $value['appo_date'] = date('M j, Y', strtotime($value['appo_date']));   
                $value['appo_time'] = date('h:i:s A', strtotime($value['appo_time']));              
                $value['service_image'] = $value['orderServiceDetails']['service_image_path'];
                $value['service_name']  = $value['orderServiceDetails']['service_name'];
                $value['service_price'] = $value['orderServiceDetails']['price'];
                // $value['order_ids']      = @$value['orderInfo']['order_id'];
                $value['service_expert']= @$value['orderExpert']['emp_name'];
                /**
                 * expert category
                 */                
                $category = array();
                if(!empty($value->orderExpert->EmpCategory)){
                    foreach ($value->orderExpert->EmpCategory as $key) {                         
                        $category [] = @$key['EmpCategoryDetails']['name'];
                    }                
                }else{
                    $category= [];
                }                
                $value->expert_category = $category;
                unset($value->orderInfo,$value->orderExpert,$value->orderServiceDetails);
            }
            return response()->json(['ResponseCode' => 1, 'ResponseText' => 'sucessfull', 'ResponseData' => $orderList], 200);
        } catch (\Swift_TransportException $e) {
            \Log::debug($e);
            return response()->json(['ResponseCode' => 0, 'ResponseText' => 'Something went wrong', 'ResponseData' => null], 400);
        }
    }

    /**
     * order date postponed
     */
    public function orderDatePostPoned(Request $request)
    {
        $rule = [
            'date' => 'required',                       
        ];

        $message = [
            'date.required' => 'date is required',            
        ];

        $validate = Validator::make($request->all(), $rule, $message);

        if ($validate->fails()) {
            return response()->json(['ResponseCode' => 0, 'ResponseText' => 'Validation Fails', 'ResponseData' => $validate->errors()->all()], 422);
        }
        try {
            $data = request()->all();
            $userId = $data['user']['user_id'];
            $updateOrderDate = ServiceAppoinment::where('id',$data['appoinment_id'])->where('user_id',$userId)->update(['is_postponed'=>$data['date']]);
            if ($updateOrderDate) {
                return response()->json(['ResponseCode' => 1, 'ResponseText' => 'Date Postpone successfully.', 'ResponseData' => null], 200);
            }
            return response()->json(['ResponseCode' => 0, 'ResponseText' => 'Something went wrong', 'ResponseData' => null], 400);
        } catch (\Swift_TransportException $e) {
            \Log::debug($e);
            return response()->json(['ResponseCode' => 0, 'ResponseText' => 'Something went wrong', 'ResponseData' => null], 400);
        }
    }

    /**
     * order cancellation
     */
    public function orderCancellationReason(Request $request)
    {
        $rule = [
            'cancel_reson_text' => 'required',                       
        ];

        $message = [
            'cancel_reson_text.required' => 'This field is requerid',            
        ];

        $validate = Validator::make($request->all(), $rule, $message);

        if ($validate->fails()) {
            return response()->json(['ResponseCode' => 0, 'ResponseText' => 'Validation Fails', 'ResponseData' => $validate->errors()->all()], 422);
        }
        try {
            $data = request()->all();                
            $userId = $data['user']['user_id'];
            $appointment_id = $data['appoinment_id'];
            $message = $data['cancel_reson_text'];            
            $cancelOrder = ServiceAppoinment::where('id',$appointment_id)->where('user_id',$userId)
                            ->update(
                                [
                                    'cancel_reson'=>$message, 
                                    'status' =>'cancel']
                                );
            
            if ($cancelOrder) {
                $payment = PaymentMethodInfo::where('appoinment_id',$appointment_id)->first();                
                if($payment['payment_method'] == 'stripe' || $payment['payment_method'] == 'klarna'){
                    $stripe = new \Stripe\StripeClient('sk_test_hHiwaXoEWblwzGpUgMsR9W0M00GwOAS2NB');                    
                    if($payment['payment_method'] == 'stripe') {
                        $refund = $stripe->refunds->create([
                            'charge' => $payment['payment_id'],
                        ]);
                        $updatePayment = PaymentMethodInfo::where('id',$payment['id'])->update(['refund_id'=>$refund['id'],'status'=>'refund']);
                    } elseif ($payment['payment_method'] == 'klarna'){
                        $refund = $stripe->refunds->create([
                            'payment_intent' => $payment['payment_id'],
                        ]);
                        $updatePayment = PaymentMethodInfo::where('id',$payment['id'])->update(['refund_id'=>$refund['id'],'status'=>'refund']);
                    }
                }
                return response()->json(['ResponseCode' => 1, 'ResponseText' => 'Oreder Cancel successfully.', 'ResponseData' => null], 200);
            }
            return response()->json(['ResponseCode' => 0, 'ResponseText' => 'Something went wrong', 'ResponseData' => null], 400);
        } catch (\Swift_TransportException $e) {
            \Log::debug($e);
            return response()->json(['ResponseCode' => 0, 'ResponseText' => 'Something went wrong', 'ResponseData' => null], 400);
        }
    }
}
