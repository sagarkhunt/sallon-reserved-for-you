<?php

namespace App\Http\Controllers\Api\ServiceProvider\MyWallet;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use URL;
use Mail;
use File;
use Exception;
use Carbon\Carbon;
use App\Models\PaymentMethodInfo;
use App\Models\MyWallet;
use App\Models\Withdrwal;
use App\Models\ServiceAppoinment;


class WalletController extends Controller
{
    public function walletDetails(Request $request)
    {
        try {
            $data = request()->all();
            // $storeId = \BaseFunction::getStoreDetails($data['user']['user_id']);  
            $storeId  = $data['store_id'];
            $commission = \Config::get('admin.commission');
            
            $PaymentMethodInfo = PaymentMethodInfo::where('store_id',$storeId)->get();
            
            // after commission view all earning
            $allEarning = ServiceAppoinment::where('store_id', $storeId)->where('status', 'completed')->where('appointment_type','system')->sum('price');            
            $commissionAmount = $allEarning * $commission / 100 ;            
            
            //store provider total withdeawn
            $withdrawnBalance = Withdrwal::where('store_id', $storeId)->where('status', 'complete')->sum('amount');
            //provider available balance
            $avalableBalance = $allEarning - $withdrawnBalance;
            //provider refunded Balance 
            $refundedBalance = ServiceAppoinment::where('store_id', $storeId)->where('status', 'cancel')->sum('price');;

            //statistics
            

            //all transaction

            $allTransaction = ServiceAppoinment::with('userDetails','employeeDetails','serviceDetails','orderInfo')->where('store_id',$storeId)->where('appointment_type','system')->get();            
            // dd($allTransaction->toArray());
            foreach ($allTransaction as $row) {
                
                $row['user_name']    = $row['userDetails'] == null ? null : $row['userDetails']['first_name'].' '.$row['userDetails']['last_name'];
                $row['service_name'] = $row['serviceDetails']['service_name'];
                $row['service_price'] = $row['serviceDetails']['price'];
                $row['expert_name']  = $row['employeeDetails'] == null ? null :$row['employeeDetails']['emp_name'];
                $row['ord_date']     = @date('M j, Y', strtotime($row['orderInfo']['created_at']));
                $row['ord_id']       = $row['order_id'];                
                $row['ord_status']       = @$row['orderInfo']['status'];    

                unset($row->userDetails,$row->employeeDetails,$row->serviceDetails,$row->orderInfo);
            }
            
            $data = [
                'netEarning'       => number_format($allEarning-$commissionAmount,2),
                'withdrawnBalance' => number_format($withdrawnBalance,2),
                'avalableBalance'  => number_format($avalableBalance,2),
                'refundedBalance'  => number_format($refundedBalance,2),
                'totalEarning'     => number_format($allEarning,2),
                'statistics'       => NULL,
                'allTransaction'   => $allTransaction,
                'totalOrder'       => $allTransaction->count()

            ];
            return response()->json(['ResponseCode' => 1, 'ResponseText' => "MyWallet Successfully", 'data' => $data], 200);
        } catch (\Swift_TransportException $e) {
            \Log::debug($e);
            return response()->json(['ResponseCode' => 0, 'ResponseText' => 'Something went wrong', 'ResponseData' => null], 400);
        }
    }
}
