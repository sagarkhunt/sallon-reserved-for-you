<?php

namespace App\Http\Controllers\Api\User\Favourites;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Favourite;
use URL;
use File;
use Validator;

class FavouritesController extends Controller
{
    public function list(Request $request)
    {
        try {
            $data = request()->all();                        
            // $userId = $data['user']['user_id'];
            $user = $request['user'];  
            $userId = $user == null ? null:$user['user_id']; 
            if ($userId == null) {
                return response()->json(['ResponseCode' => 9, 'ResponseText' => 'Authorization Token is required.','ResponseData'=>null],499);
            }           
            $favoritList = Favourite::with('StoreDetail','StoreDetail.storeCategory','StoreDetail.storeCategory.CategoryData','StoreDetail.storeRated')->where('user_id',$userId)->select('id','user_id','store_id')->get();
            
            foreach ($favoritList as $value) {
                $value['store_name']   = $value['StoreDetail']['store_name'];
                $value['store_address']   = $value['StoreDetail']['store_address'];
                $value['store_profile_image_path']   = $value['StoreDetail']['store_profile_image_path'];
                $value['store_banner_image_path']   = $value['StoreDetail']['store_banner_image_path'];
                $value['category_name']   = $value['StoreDetail']['category_id'];
                //category name
                $category = [];
                foreach ($value->StoreDetail['storeCategory'] as $key) {                    
                    $category[]['cate_name']= $key['CategoryData']['name'];                       
                }                
                $value['category'] = $category;
                //store rating
                // $sum = 0;    
                // foreach ($value->StoreDetail['storeRated'] as $row) {                      
                //     $sum += $row['service_rate'] + $row['ambiente'] + $row['preie_leistungs_rate'] + $row['wartezeit'] + $row['atmosphare'];                            
                // }                     
                // $value['total_rating'] = $sum;    
                // //$value->StoreDetail['storeRated']->count()
                // $value['avg_rating'] = number_format($sum == 0 ? 0 : ($sum / 5 /$value->StoreDetail['storeRated']->count()),2);
                
                $sum = 0;         
                foreach ($value->StoreDetail['storeRated'] as $rating) {                      
                    $sum += $rating['total_avg_rating'];                                            
                }                  
                $value['total_rating'] = number_format($sum,2);   
                $value['avg_rating'] = number_format($sum == 0 ? 0 : ($sum / $value->StoreDetail['storeRated']->count()),2);
                $value['total_feedback']  =   $value->StoreDetail['storeRated']->count();

                unset($value->StoreDetail);
                
            }         

            if ($favoritList->count() > 0) {
                return response()->json(['ResponseCode' => 1, 'ReponseText' => 'Favourite List Successfully.','ResponseData'=> $favoritList],200);
            }
            return response()->json(['ResponseCode' => 0, 'ReponseText' => 'No Data Found.','ResponseData'=> NULL],200);
        } catch (\Swift_TransportException $e) {
            \Log::debug($e);
            return response()->json(['ResponseCode' => 0, 'ResponseText' => 'Something went wrong', 'ResponseData' => null], 400);
        }
    }


      /*add store Favorites */
    public function store(Request $request)
    {
        $rule = [            
            'store_id' =>'required',                
        ];
          $message = [
            'store_id.required' => 'Store id is required',            
        ];

        $validate = Validator::make($request->all(), $rule);
        if($validate->fails()){
            return response()->json(['ResponseCode' => 0, 'ResponseText' => 'Validation Fails', 'ResponseData' => $validate->errors()->all()], 422);
        }
        try {
            $data = request()->all();
            // $userId = $data['user']['user_id'];
            $user = $request['user'];  
            $userId = $user == null ? null:$user['user_id']; 
            if ($userId == null) {
                return response()->json(['ResponseCode' => 9, 'ResponseText' => 'Authorization Token is required.','ResponseData'=>null],499);
            }
            $storeFavorites = Favourite::updateOrCreate(
                            [
                                'user_id' =>$userId,
                                'store_id'=>$data['store_id']
                            ],
                            [
                            'user_id' =>$userId,
                            'store_id'=>$data['store_id']
                            ]);

            return response()->json(['ResponseCode' => 1, 'ResponseText' => 'The store has been added to the favorites list.','ResponseData'=> $storeFavorites],200);
              
          }  catch (\Swift_TransportException $e) {
            \Log::debug($e);
            return response()->json(['ResponseCode' => 0, 'ResponseText' => 'Something went wrong', 'ResponseData' => null], 400);
        }
    }
    //unfavourites store
    public function removeFavourite(Request $request)
    {
        $rule = [            
            'store_id' =>'required',                
        ];
          $message = [
            'store_id.required' => 'Store id is required',            
        ];

        $validate = Validator::make($request->all(), $rule);
        if($validate->fails()){
            return response()->json(['ResponseCode' => 0, 'ResponseText' => 'Validation Fails', 'ResponseData' => $validate->errors()->all()], 422);
        }
        try {
            $data = request()->all();
            
            // $userId = $data['user']['user_id'];
            $user = $request['user'];  
            $userId = $user == null ? null:$user['user_id']; 
            if ($userId == null) {
                return response()->json(['ResponseCode' => 9, 'ResponseText' => 'Authorization Token is required.','ResponseData'=>null],499);
            }
            $removeFavorites = Favourite::where('store_id',$data['store_id'])->where('user_id',$userId)->delete();
            return response()->json(['ResponseCode' => 1, 'ResponseText' => 'This is store is unfourite.','ResponseData'=> NULL],200);
              
          }  catch (\Swift_TransportException $e) {
            \Log::debug($e);
            return response()->json(['ResponseCode' => 0, 'ResponseText' => 'Something went wrong', 'ResponseData' => null], 400);
        }
    }
}
