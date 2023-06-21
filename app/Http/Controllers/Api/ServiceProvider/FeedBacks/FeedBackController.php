<?php

namespace App\Http\Controllers\Api\ServiceProvider\FeedBacks;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StoreRatingReview;
use Validator;
use URL;
use Mail;
use File;
use Exception;
use Carbon\Carbon;
use DB;

class FeedBackController extends Controller
{
    public function storeRating(Request $request)
    {
        try {
            $data = request()->all();   
                              
            // $storeId = \BaseFunction::getStoreDetails($data['user']['user_id']);
            $storeId = $data['store_id'];
            
            $storeRating = StoreRatingReview:: where('store_id',$storeId);
            //type wise get avarage
            $totalAvgRating   =  $storeRating->select(DB::raw('ROUND(AVG(service_rate) ,2) AS service_rate, ROUND(AVG(ambiente),2) AS ambiente, ROUND(AVG(preie_leistungs_rate),2) AS preie_leistungs_rate, ROUND(AVG(wartezeit),2) AS wartezeit, ROUND(AVG(atmosphare),2) AS atmosphare'))->get();            
            //total feed back count
            $totalFeedBack    =  $storeRating->count();
            
            //type weise avarage
            $userRatingReview =  StoreRatingReview::where('store_id',$storeId)->with('userDetails')->select('id','user_id','total_avg_rating','write_comment','created_at')->get();                        
            // all avarage
            $rating_sum = 0;
            foreach ($userRatingReview as $value) {
                           ;
                $value['user_name'] = $value['userDetails']['first_name'].' '.$value['userDetails']['last_name'];
                $value['user_image_path'] = $value['userDetails']['user_image_path'];
                $value['dayAgo'] = $value->created_at->diffInDays(\Carbon\Carbon::now()->toDateString());  
                $rating_sum += $value['total_avg_rating'];         
                // $userRating = \BaseFunction::userRating($value);
                // $value['userAvgRating']  = $userRating;
                unset($value->userDetails);
            }
            
            $rating_sum = number_format($rating_sum == 0 ? 0 : ($rating_sum /$userRatingReview->count()),2);  
            $data = [
                'allOverAvg' => $rating_sum,
                'totalFeedBack'=>$totalFeedBack,
                'totalAvgRating' => $totalAvgRating,
                'customerReview' =>$userRatingReview
            ];
            return response()->json(['ResponseCode' => 1, 'ResponseText' => 'Successful', 'ResponseData' => $data], 200);
        } catch (\Swift_TransportException $e) {
            \Log::debug($e);
            return response()->json(['ResponseCode' => 0, 'ResponseText' => 'Something went wrong', 'ResponseData' => null], 400);
        }
    }
}
