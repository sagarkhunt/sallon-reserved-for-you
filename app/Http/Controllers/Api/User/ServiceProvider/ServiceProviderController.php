<?php

namespace App\Http\Controllers\Api\User\ServiceProvider;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\StoreCategory;
use App\Models\StoreGallery;
use App\Models\StoreProfile;
use App\Models\StoreTiming;
use App\Models\StoreRatingReview;
use App\Models\Service;
use App\Models\Store\Advantages;
use App\Models\Store\Parking;
use App\Models\Store\PublicTransportation;
use Illuminate\Http\Request;
use URL;
use DB;


class ServiceProviderController extends Controller
{
    public function index(Request $request){  
        
        $user = $request['user'];        
        
        $user_id = $user == null ? null:$user['user_id'];        
        
        $searchText = $request['search_text'];                
        $pincode = $request['pincode'];
        $category_id = $request['category_id'];
        $date = $request['date'];
        $booking = $request['booking_system'];
        $discount = $request['discount'];

        $day = \Carbon\Carbon::parse($date)->format('l');        
        $data = StoreProfile::leftjoin('store_categories', 'store_categories.store_id', '=', 'store_profiles.id')
                         ->leftjoin('services', 'services.store_id', '=', 'store_profiles.id')
                        ->with(['storeCategory','storeCategory.CategoryData','storeFavourite' =>function($query) use($user_id){
                            $query->where('user_id',$user_id);
                        },'storeRated']);                       
        
            if (!empty($category_id)) {
                $data = $data->where('store_categories.category_id', $category_id);
            }
           
            if (!empty($date)) {
                $data = $data->leftjoin('store_timings', 'store_timings.store_id', '=', 'store_profiles.id');
            }
            if (!empty($date)) {
                $data = $data->where('store_timings.day', $day)->where('store_timings.is_off', 'on');
            }
            if (!empty($pincode)) {
                $data = $data->where('store_profiles.zipcode', 'LIKE', "%{$pincode}%");
            }  
            if (!empty($booking)) {                
                $data = $data->where('store_profiles.store_active_plan', 'business');
            }
    
            if (!empty($discount)) {
                $data = $data->where('services.discount_type', '!=', 'null');                
            }            
            $data = $data->where('store_status','active')    
                    ->where('store_profiles.category_id',$request->mian_category)
                    ->where('store_name','like','%'.$searchText.'%')                     
                    ->select('store_profiles.id','store_profiles.user_id','store_profiles.store_name','store_profiles.store_description','store_profiles.store_profile',
                    'store_profiles.store_banner','store_profiles.store_status','store_profiles.store_address','store_profiles.category_id','store_profiles.store_active_plan','store_profiles.is_value')
                    ->groupBy('store_profiles.id')
                    ->orderBy('store_profiles.id', 'DESC')
                    ->take(10)
                   ->get();               
            
        //store list
        $storeId = [];
        foreach ($data as $row){                                        
            $storeId[] = $row['id'];
            foreach ($row->storeCategory as $category) {                    
                $category['name'] = $category['CategoryData']['name'];
                unset($category->CategoryData);
            }             
            //favourite or note            
            $row['favourite'] =!empty($row->storeFavourite) == true ? true : false;
            $row['category_name'] = $row['category_id'];
            //store rating   
            $sum = 0;         
            foreach ($row->storeRated as $rating) {                      
                $sum += $rating['total_avg_rating'];                                            
            }     
            $row['avg_rating'] = number_format($sum == 0 ? 0 : ($sum / $row->storeRated->count()),2);  
            $row['total_feedback']  =  $row->storeRated->count();
            unset($row->storeFavourite,$row->storeRated);
        }
        
        if (isset($request['sorting']) && $request['sorting'] != '') {
            if ($request['sorting'] == 'desc') {                    
                $data = collect($data->sortByDesc('id')->values()->all());    
            }else{                    
                $data = collect($data->sortBy('id')->values()->all());
            }
        }
        // }else{
        //     $data = collect($data->sortByDesc('avg_rating')->values()->all());
        // }        
        //all services
        // $serviceList = Service::where('category_id',$category_id)
        //                ->orWhere('service_name','like','%'.$searchText.'%')
        //                ->select('id','store_id','category_id','subcategory_id','service_name','image')
        //                ->get();
        
        $serviceList = Service::where('category_id',$category_id);

        if (!empty($searchText)) {
            $serviceList = $serviceList->where('service_name','like','%'.$searchText.'%');
        }
        if (!empty($date) || !empty($pincode)) {
            $serviceList = $serviceList->whereIn('store_id',$storeId);
        }

        $serviceList = $serviceList->select('id','store_id','category_id','subcategory_id','service_name','image')
                        ->take(10)
                        ->get();
        
        //top rated store
        $recommendedStore = StoreProfile::join('store_categories','store_categories.store_id','=','store_profiles.id');
            // if (!empty($category_id)) {
            //     $recommendedStore = $recommendedStore->where('store_categories.category_id', $category_id);
            // }
        
            // if (!empty($date)) {
            //     $recommendedStore = $recommendedStore->leftjoin('store_timings', 'store_timings.store_id', '=', 'store_profiles.id');
            // }
            // if (!empty($date)) {
            //     $recommendedStore = $recommendedStore->where('store_timings.day', $day)->where('store_timings.is_off', 'on');
            // }
            // if (!empty($pincode)) {
            //     $recommendedStore = $recommendedStore->where('store_profiles.zipcode', 'LIKE', "%{$pincode}%");
            // }

        $recommendedStore = $recommendedStore->where('store_profiles.category_id',$request->mian_category)
                            ->where('store_categories.category_id',$request->category_id)
                            ->select('store_categories.category_id as cat_id','store_profiles.id','store_profiles.store_name'
                            ,'store_profiles.store_profile','store_profiles.store_banner','store_profiles.store_status'
                            ,'store_profiles.category_id')
                            ->where('store_profiles.store_status','active')
                            ->where('store_profiles.is_recommended', 'yes')
                            // ->where('store_profiles.store_name','like','%'.$searchText.'%')                                             
                            // ->where('store_profiles.zipcode', 'LIKE', "%{$pincode}%")
                            ->take(10)
                            ->get();
        foreach ($recommendedStore as $value) {
            $sum = 0;    
            foreach ($value->storeRated as $row) {                      
                // $sum += $row['service_rate'] + $row['ambiente'] + $row['preie_leistungs_rate'] + $row['wartezeit'] + $row['atmosphare'];                                      
                $value['avg_rating'] = number_format($row['total_avg_rating'],2);            
            }                           
            // $value['total_rating'] = $sum;
            
            unset($value->storeRated,$value->userDetails,$value->storeOpeningHours);
        }
        //check condition sorting
        if (isset($data['sorting']) && $data['sorting'] != '') {
            if ($data['sorting'] == 'desc') {
                $recommendedStore = collect($recommendedStore->sortByDesc('id')->values()->all());    
            }else{                    
                $recommendedStore = collect($recommendedStore->sortBy('id')->values()->all());
            }
        }else{                       
            $recommendedStore = collect($recommendedStore->sortByDesc('avg_rating')->values()->all());                    
        }
        $dataList=[
            'service_list'    => $serviceList,            
            'top_rated_store' => $recommendedStore,
            'storeList'       => $data
        ];
        
        if(count($dataList['service_list']) == 0 && count($dataList['top_rated_store']) == 0 && count($dataList['storeList']) ==0){
            return response()->json(['ResponseCode' => 0, 'ResponseText' => "No Record Found", 'ResponseData' => null], 200);
        }
        return response()->json(['ResponseCode' => 1, 'ResponseText' => "Service Provider List get Successfully", 'ResponseData' => $dataList], 200);
        // if(count($dataList) != 0){
        //     return response()->json(['ResponseCode' => 1, 'ResponseText' => "Service Provider List get Successfully", 'ResponseData' => $dataList], 200);
        // } else {
        //     return response()->json(['ResponseCode' => 1, 'ResponseText' => "No Record Found", 'ResponseData' => null], 200);
        // }
    }

    

    public function serviceProviderView(Request $request)
    {
        try {
            $user = $request['user'];            
            $user_id = $user == null ? null :$user['user_id'];
            
            $storeDetails  = StoreProfile::with(['storeFavourite','storeGallery','serviceDetails' => function($q){
                              $q->select('id','store_id','category_id','subcategory_id','service_name','price','start_time','end_time','start_date','end_date','discount_type'
                              ,'discount','image');
                            },
                            'serviceDetails.SubCategoryData' => function($que){
                                $que->select('id','main_category','name','image')
                               ->where('status','active');
                            },'storeExpert','storeExpert.EmpCategory','storeOpeningHours' =>function($query){
                                $query->select('id','store_id','day','start_time','end_time','is_off');
                            },'storeFavourite' => function ($q) use($user_id){
                                $q->where('user_id',$user_id);
                            },'userDara','userStoreRatedFlag'=>function($qu)use($user_id,$request){
                                $qu->where('user_id',$user_id)->where('store_id',$request['provider_id']);
                            }])
                            ->where('store_status','active')
                            ->where('id',$request['provider_id'])
                            ->select('id','user_id','store_name','store_description','store_profile','store_banner','store_status','store_address','store_start_time','store_end_time','latitude','longitude','payment_method','store_active_plan','store_link_id')
                            ->first();

            // dd($storeDetails->toArray());
            
            //store rating
            // $sum = 0;
            // foreach ($storeDetails->storeRated as $value) {               
            //     $sum += $value['service_rate'] + $value['ambiente'] + $value['preie_leistungs_rate'] + $value['wartezeit'] + $value['atmosphare'];            
            //     $storeDetails['total_rating'] = $sum;
            //     $storeDetails['avg_rating'] = number_format($sum == 0 ? 0 : ($sum / 5 ),2);                            
            // }

            $sum = 0;         
            foreach ($storeDetails->storeRated as $rating) {                      
                $sum += $rating['total_avg_rating'];                                            
            }     
            $storeDetails['avg_rating'] = number_format($sum == 0 ? 0 : ($sum / $storeDetails->storeRated->count()),2); 
            $storeDetails['total_feedback']  =  $storeDetails->storeRated->count();
            //service name 
            $serviceName = [];
            foreach ($storeDetails['serviceDetails'] as $service) {                 
                $serviceName [] = $service['service_name'];
            }
            $storeDetails['serviceName'] = $serviceName;

            //Store category Name
            $categoryName = [];
            foreach ($storeDetails['storeCategory'] as $category) {                  
                $categoryName [] = $category['CategoryData']['name'];
            }
            $storeDetails['categoryName'] = $categoryName;
            
            //favouritse flage 
            $storeDetails['favourite'] = !empty($storeDetails['storeFavourite']) == true ? true : false;

            
            $storeDetails['feedback_flag'] = !empty($storeDetails['userStoreRatedFlag']) == true ? true : false;
            
            //store abount
            $storeDetails['About'] = [
                    'Small Discribe' =>$storeDetails['store_description'],                    
                    'advantages' =>Advantages::where('store_id', $request['provider_id'])->where('status', 'active')->get(),                                                           
                    'our_service_expert' => $storeDetails['storeExpert'],
                    'latitude' => $storeDetails['latitude'],
                    'longitude' => $storeDetails['longitude'],
                    'district' => $storeDetails['store_district'],
                    'public_transportation' =>PublicTransportation::where('store_id', $request['provider_id'])->where('status', 'active')->get(),
                    'opening_hours' => $storeDetails['storeOpeningHours'],
                    'home_url' => $storeDetails['store_link_id'],
                    'diraction'=>Parking::where('store_id', $request['provider_id'])->where('status', 'active')->get(),
                    'phone_number' => !empty($storeDetails['userDara']) == true ? $storeDetails['userDara']['phone_number'] : NULL
            ];
            
            //about emp expert
            $empService = [];
            foreach ($storeDetails->storeExpert as $row) {
                foreach ($row['EmpService'] as $key ) {                    
                    $empService [] = $key['EmpServiceDetails']['service_name'];      
                    $row ['empCate'] = $empService;  
                    unset($key->EmpServiceDetails);
                }                
                unset($row->EmpCategory,$row->EmpService);
            }   
            // if($storeDetails['store_active_plan'] == 'business'){
            //     $storeDetails['flag'] = ['service','about','galary','feedback'];
            // }elseif($storeDetails['store_active_plan'] == 'basic-plus'){
            //     $storeDetails['flag'] = ['about','galary','feedback'];
            // }else{
            //     $storeDetails['flag'] = ['about'];
            // }  

            //check for price amount or persentage wise
            foreach ($storeDetails->serviceDetails as $value) {
                $value['finalPrice'] = \BaseFunction::finalPrice($value['id']);
            }   

            unset($storeDetails->storeRated,$storeDetails->storeFavourite,$storeDetails->storeExpert,$storeDetails->storeOpeningHours,$storeDetails->userDara,$storeDetails->userStoreRatedFlag,$storeDetails->storeCategory);
            return response()->json(['ResponseCode' => 1, 'ResponseText' => "Service Provider Get Successfully", 'ResponseData' => $storeDetails], 200);
        }catch (\Swift_TransportException $e) {
            \Log::debug($e);
            return response()->json(['ResponseCode' => 0, 'ResponseText' => 'Something went wrong', 'ResponseData' => null], 400);
        }
    }
    /**
     * Add to favourits
     */
    public function storeFeedBack(Request $request)
    {
        try {
            $data = request()->all();
            $storeId = $data['store_id'];
            
            $storeRating = StoreRatingReview:: where('store_id',$storeId);
            //type wise get avarage
            $totalAvgRating   =  $storeRating
                                ->select(DB::raw('ROUND(AVG(service_rate) ,2) AS service_rate, ROUND(AVG(ambiente),2) AS ambiente, ROUND(AVG(preie_leistungs_rate),2) AS preie_leistungs_rate, ROUND(AVG(wartezeit),2) AS wartezeit, ROUND(AVG(atmosphare),2) AS atmosphare'))
                                ->get();            
            
            //total feed back count
            $totalFeedBack    =  $storeRating->count();
            //type weise avarage
            $userRatingReview =  StoreRatingReview:: where('store_id',$storeId)->with('userDetails')->get();                        
            foreach ($userRatingReview as $value) {                
                $value['user_name'] = $value['userDetails']['first_name'].' '.$value['userDetails']['last_name'];
                $value['user_image_path'] = $value['userDetails']['user_image_path'];
                $value['dayAgo'] = $value->created_at->diffInDays(\Carbon\Carbon::now()->toDateString());    
                $userRating = \BaseFunction::userRating($value);
                $value['userAvgRating']  = $userRating;
                unset($value->userDetails);
            }
            if (isset($data['sorting']) && $data['sorting'] != '') {                
                if ($data['sorting'] == 'desc') {
                    $userRatingReview = collect($userRatingReview->sortByDesc('userAvgRating')->values()->all());    
                }else{                    
                    $userRatingReview = collect($userRatingReview->sortBy('userAvgRating')->values()->all());
                }
            }
            // all avarage
            $rating_sum = 0;
                 
            foreach ($totalAvgRating->toArray() as  $value) {                
                $rating_sum += $value['service_rate'] + $value['ambiente'] + $value['preie_leistungs_rate'] + $value['wartezeit'] + $value['atmosphare'];                                             
                $rating_sum = number_format($rating_sum == 0 ? 0 : ($rating_sum / 5),2);
            }
            
            $data = [
                'allOverAvg' => $rating_sum,
                'totalFeedBack'=>$totalFeedBack,
                'totalAvgRating' => $totalAvgRating,
                'customerReview' =>$userRatingReview
            ];
            return response()->json(['ResponseCode' => 1, 'ResponseText' => 'Successful', 'ResponseData' => $data], 200);
        }catch (\Swift_TransportException $e) {
            \Log::debug($e);
            return response()->json(['ResponseCode' => 0, 'ResponseText' => 'Something went wrong', 'ResponseData' => null], 400);
        }
    }
    
    /**
     * get store wise categery
     */
    public function storeCategory(Request $request)
    {
        try {
            $data = request()->all();
            
            $categoryData = StoreCategory::with('CategoryData')->withCount('CategoryWiseService')->where('store_id',$data['store_id'])->get();
            
            if ($categoryData->count() < 0) {
                return response()->json(['ResponseCode' => 1, 'ResponseText' => 'No Data Found', 'ResponseData' => NULL], 200);                
            }
            foreach ($categoryData as $value) {
                $value['category_name'] = $value['CategoryData']['name'];
                unset($value->CategoryData);
            }
            
            return response()->json(['ResponseCode' => 1, 'ResponseText' => 'Successful', 'ResponseData' => $categoryData], 200);
        }catch (\Swift_TransportException $e) {
            \Log::debug($e);
            return response()->json(['ResponseCode' => 0, 'ResponseText' => 'Something went wrong', 'ResponseData' => null], 400);
        }
    }

    /**
     * store category wise service
     */
    public function storeCategoryServices(Request $request)
    {
        try {
            $data = request()->all();
            $categoryWiseServices = Service::where('store_id',$data['store_id']);
            if ($data['category_id']) {
                $categoryWiseServices = $categoryWiseServices->where('category_id',$data['category_id']);
            }
            $categoryWiseServices = $categoryWiseServices->get();
            /**
             * check for price amount and persentage wise
             */
            foreach ($categoryWiseServices as $value) {
                $value['finalPrice'] = \BaseFunction::finalPrice($value['id']);
            }
            if ($categoryWiseServices->isEmpty()) {
                return response()->json(['ResponseCode' => 1, 'ResponseText' => 'No Data Found', 'ResponseData' => NULL], 200); 
            }
            return response()->json(['ResponseCode' => 1, 'ResponseText' => 'Successful', 'ResponseData' => $categoryWiseServices], 200);
        }catch (\Swift_TransportException $e) {
            \Log::debug($e);
            return response()->json(['ResponseCode' => 0, 'ResponseText' => 'Something went wrong', 'ResponseData' => null], 400);
        }
    }

    /**
     * service filter
     */

    public function filterService(Request $request)
    {
        try {
            $data = request()->all();    
            $expensive = $data['expensive'];   
            $discount = $request['discount'];            
            $booking = $request['booking_system'];               
            if (isset($data['user']) == true && !empty($data['user'])) {
                $userId = $data['user']['user_id'];                
            }else{
                $userId = '';
            }
            $getStore = StoreProfile::leftjoin('store_categories', 'store_categories.store_id', '=', 'store_profiles.id')
                ->leftjoin('services', 'services.store_id', '=', 'store_profiles.id')
                ->leftjoin('store_rating_reviews', 'store_rating_reviews.store_id', '=', 'store_profiles.id')
                ->with(['storeFavourite' => function($query)use($userId){
                    $query->where('user_id',$userId);
                }]);
                /**
                 * filter by zipcode
                 */
                if (!empty($data['pincode'])) {
                    $zipcode = $data['pincode']; 
                    $getStore = $getStore->where('store_profiles.zipcode','=',$zipcode);
                }
                /**
                 * filter by category
                 */
                if (!empty($data['category'])) {
                    $cat_id = [];
                    foreach (json_decode($data['category']) as $value) {                
                        $cat_id[] = $value->cat_id;
                    }
                    $getStore = $getStore->whereIn('store_categories.category_id',$cat_id);                
                    
                }
                /**
                 * filter by price and disount promotions
                 */
                $range ='';
                if(!empty($data['price'])){                            
                    $range = $data['price'];                
                    $getStore = $getStore->where('services.price','<=',$range);                                        
                }
                
                if (!empty($data['discount_promotions'])) {
                    $discount_promotions = $data['discount_promotions'];
                    $getStore = $getStore->where('services.discount_type',$discount_promotions);
                }
                /**
                 * filter rating
                 */
                if (!empty($data['rating'])) {
                    $rating = $data['rating'];
                    $getStore = $getStore->where('store_rating_reviews.total_avg_rating','<=',$rating);
                }

                if (!empty($booking)) {
                    $getStore = $getStore->where('store_profiles.store_active_plan', 'business');                    
                }
        
                if (!empty($discount)) {
                    $getStore = $getStore->where('services.discount_type', '!=', 'null');
                }
                
                $getStore = $getStore->orderBy('id', 'DESC')            
                ->select('store_profiles.id','store_profiles.user_id','store_profiles.store_name','store_profiles.store_description',
                'store_profiles.store_profile','store_profiles.store_banner','store_profiles.store_status','store_profiles.store_address',
                'store_profiles.category_id','store_profiles.store_active_plan','store_profiles.is_value','store_rating_reviews.total_avg_rating')
                ->groupBy('store_profiles.id')
                ->where('store_status', 'active')
                ->distinct()
                ->get();
            
            $store_id = array();
            foreach ($getStore as $row) {
                $categories = StoreCategory::where('store_id', $row->id)->get();

                $c = array();
                foreach ($categories as $item) {
                    $c[] = [
                        'id'=>@$item->id,
                        'store_id'=>@$item->store_id,
                        'category_id'=>@$item->category_id,
                        'name'=>@$item->CategoryData->name,

                    ];
                }
                $row->store_category = $c;   
                $row->expensive = count(explode("€", $row->is_value)) - 1;                
                $store_id[] = @$row->id;
                $row['favourite'] =!empty($row->storeFavourite) == true ? true : false;
                $row['total_avg_rating'] = number_format($row['total_avg_rating'],2);
                unset($row->storeFavourite);
            }    
            if ($expensive != 5 && !empty($expensive)) {                         
                $finalData = [];
                foreach ($getStore as $row) {
                    if ($expensive >= $row->expensive) {
                        $finalData[] = $row;
                    }
                }
                $getStore = $finalData;
            }  
            /**
             * check for service price
             */
            $getService = Service::whereIn('store_id',$store_id);
            if (!empty($data['price'])) {
                $range = $data['price'];
                $getService = $getService->where('price','<=',$range);
            }
            $getService = $getService->select('id','store_id','category_id','service_name','image','price')->get();

            $recommendedStore = StoreProfile::join('store_categories','store_categories.store_id','=','store_profiles.id')
                            // ->where('store_profiles.category_id',$request->mian_category)
                            // ->where('store_categories.category_id',$request->category_id)
                            ->select('store_categories.category_id as cat_id','store_profiles.id','store_profiles.store_name'
                            ,'store_profiles.store_profile','store_profiles.store_banner','store_profiles.store_status'
                            ,'store_profiles.category_id','store_profiles.is_recommended')
                            ->where('store_profiles.store_status','active')
                            ->where('store_profiles.is_recommended', 'yes')
                            ->groupBy('store_profiles.id')
                            // ->where('store_profiles.store_name','like','%'.$searchText.'%')                                             
                            // ->where('store_profiles.zipcode', 'LIKE', "%{$pincode}%")
                            ->take(10)
                            ->get();
            
            /**check data null or not */
            if(count($getStore) > 0){                 
                $dataList = [                    
                        'service_list'    => $getService,            
                        'top_rated_store' => $recommendedStore,
                        'storeList'       => $getStore                    
                ];
                return response()->json(['ResponseCode' => 1, 'ResponseText' => 'Successful', 'ResponseData' => $dataList], 200);
            } else {
                return response()->json(['ResponseCode' => 0, 'ResponseText' => 'No Data Found', 'ResponseData' => NULL], 200);
            }
        } catch (\Swift_TransportException $e) {
            \Log::debug($e);
            return response()->json(['ResponseCode' => 0, 'ResponseText' => 'Something went wrong', 'ResponseData' => null], 400);
        }
    }

    /**
     * get all category
     */

    public function allCategoryList(Request $request)
    {
        try {
            $categoryList = Category::where('category_type','Cosmetics')->select('id','name','image')->get();
            if (count($categoryList) > 0) {
                return response()->json(['ResponseCode' => 1, 'ResponseText' => 'Successful', 'ResponseData' => $categoryList], 200);
            }else{
                return response()->json(['ResponseCode' => 0, 'ResponseText' => 'No Data Found', 'ResponseData' => NULL], 200);
            }
        } catch (\Swift_TransportException $e) {
            \Log::debug($e);
            return response()->json(['ResponseCode' => 0, 'ResponseText' => 'Something went wrong', 'ResponseData' => null], 400);
        }
    }

    /**
     * store all employee
     */
    public function storeEmployee(Request $request)
    {
        try {
            $data = request()->all();
            $storeEmp = StoreProfile::with(['storeExpert' =>function($query){
                        $query->select('id','store_id','emp_name');
                        }])->where('id',$data['store_id'])->first();
            // dd($storeEmp);
            if (!empty($storeEmp)) {
                $EmpName = [];
                foreach ($storeEmp->storeExpert as $value) {                    
                    $EmpName [] = $value;                    
                }  
                
                if (empty($EmpName)) {                   
                    return response()->json(['ResponseCode' => 1, 'ResponseText' => 'Successful', 'ResponseData' => $EmpName], 200);
                }else{
                    return response()->json(['ResponseCode' => 1, 'ResponseText' => 'Successful', 'ResponseData' => $EmpName], 200);   
                    
                }              
                
            }
            return response()->json(['ResponseCode' => 0, 'ResponseText' => 'No Data Found', 'ResponseData' => NULL], 200);
        } catch (\Swift_TransportException $e) {
            \Log::debug($e);
            return response()->json(['ResponseCode' => 0, 'ResponseText' => 'Something went wrong', 'ResponseData' => null], 400);
        }
    }

    /**
     * get all service
     */
    public function allService(Request $request)
    {
        try {
            $data = request()->all();
            $category_id = $data['category_id'];            
            $category_type = $data['category_type'];            
            $allStore =  StoreProfile::join('store_categories','store_categories.store_id','=','store_profiles.id')
                        ->where('store_profiles.category_id',$category_type)
                        ->where('store_categories.category_id',$category_id)
                        ->select('store_categories.category_id as cat_id','store_profiles.id')
                        ->get();            
            
            if ($allStore->count() <= 0) {
                return response()->json(['ResponseCode' => 0, 'ResponseText' => 'No data found', 'ResponseData' => Null], 200);
            }            
            $store_id = array();
            foreach ($allStore as $value) {
                $store_id [] =$value['id'];
            }
            
            $all_service = Service::whereIn('store_id',$store_id)->select('id','store_id','service_name','image')->paginate(10);            
            if ($all_service->count() <= 0) {
                return response()->json(['ResponseCode' => 0, 'ResponseText' => 'No more data found', 'ResponseData' => Null], 200);
            }
            return response()->json(['ResponseCode' => 1, 'ResponseText' => 'Success', 'ResponseData' => $all_service], 200);          
            
        } catch (\Swift_TransportException $e) {
            \Log::debug($e);
            return response()->json(['ResponseCode' => 0, 'ResponseText' => 'Something went wrong', 'ResponseData' => null], 400);
        }
    }

    /**
     * all store
     */

    public function allStore(Request $request)
    {
        try {
            $data = request()->all();
            $userId = $data['user'] == null ? '':$data['user']['user_id'];
            $category_id = $data['category_id'];
            $category_type = $data['category_type'];
            $booking = $request['booking_system'];
            $discount = $request['discount'];
            $allStore =  StoreProfile::join('store_categories','store_categories.store_id','=','store_profiles.id')
                        ->leftjoin('services', 'services.store_id', '=', 'store_profiles.id')                        
                        ->with(['storeFavourite' => function($query)use($userId){
                            $query->where('user_id',$userId);
                        }]);
            if (!empty($booking)) {                
                $allStore = $allStore->where('store_profiles.store_active_plan', 'business');
            }
    
            if (!empty($discount)) {
                $allStore = $allStore->where('services.discount_type', '!=', 'null');                
            }
            $allStore=$allStore->where('store_profiles.category_id',$category_type)
                        ->where('store_categories.category_id',$category_id)
                        ->select('store_profiles.id','store_profiles.user_id','store_profiles.store_name','store_profiles.store_description','store_profiles.store_profile',
                        'store_profiles.store_banner','store_profiles.store_status','store_profiles.store_address','store_profiles.category_id','store_profiles.store_active_plan','store_profiles.is_value')
                        ->groupBy('store_profiles.id')                        
                        ->paginate(10); 

            foreach ($allStore as $row){                            
                foreach ($row->storeCategory as $category) {                    
                    $category['name'] = $category['CategoryData']['name'];
                    unset($category->CategoryData);
                }             
                //favourite or note
                $row['favourite'] =!empty($row->storeFavourite) == true ? true : false;
                $row['category_name'] = $row['category_id'];
                //store rating   
                $sum = 0;         
                foreach ($row->storeRated as $rating) {                      
                    $sum += $rating['total_avg_rating'];                                            
                }     
                $row['avg_rating'] = number_format($sum == 0 ? 0 : ($sum / $row->storeRated->count()),2);        
                unset($row->storeFavourite,$row->storeRated);
            }
            if ($allStore->isEmpty()) {
                return response()->json(['ResponseCode' => 0, 'ResponseText' => 'No more data found', 'ResponseData' => null], 200);                      
            }
            return response()->json(['ResponseCode' => 1, 'ResponseText' => 'Success', 'ResponseData' => $allStore], 200);                      
        }catch (\Swift_TransportException $e) {
            \Log::debug($e);
            return response()->json(['ResponseCode' => 0, 'ResponseText' => 'Something went wrong', 'ResponseData' => null], 400);
        }
    }

    /**
     * get all-recommended-store
     */
    public function allRecommendedStore(Request $request)
    {
        try {
            $data = request()->all();
            
            $category_id = $data['category_id'];
            $category_type = $data['category_type'];
            $allRecommendedStore =  StoreProfile::join('store_categories','store_categories.store_id','=','store_profiles.id')
                        ->where('store_profiles.category_id',$category_type)
                        ->where('store_categories.category_id',$category_id)
                        ->where('store_profiles.is_recommended', 'yes')
                        ->select('store_categories.category_id as cat_id','store_profiles.id','store_profiles.store_name'
                        ,'store_profiles.store_profile','store_profiles.store_banner','store_profiles.store_status'
                        ,'store_profiles.category_id')->paginate(10); 

            foreach ($allRecommendedStore as $row){                                            
                $sum = 0;         
                foreach ($row->storeRated as $rating) {                      
                    $sum += $rating['total_avg_rating'];                                            
                }     
                $row['avg_rating'] = number_format($sum == 0 ? 0 : ($sum / $row->storeRated->count()),2);        
                unset($row->storeFavourite,$row->storeRated);
            }
            // $allRecommendedStore = collect($allRecommendedStore->sortByDesc('avg_rating')->values() ->all());
            if ($allRecommendedStore->isEmpty()) {
                return response()->json(['ResponseCode' => 0, 'ResponseText' => 'No more data found', 'ResponseData' => null], 200);                      
            }
            return response()->json(['ResponseCode' => 1, 'ResponseText' => 'Success', 'ResponseData' => $allRecommendedStore], 200);
        } catch (\Swift_TransportException $e) {
            \Log::debug($e);
            return response()->json(['ResponseCode' => 0, 'ResponseText' => 'Something went wrong', 'ResponseData' => null], 400);
        }
    }

    /**
     * store-storeSuggetion
     */
    public function storeSuggetion(Request $request)
    {
        try {
            $search = $request['search_text'];
            
            $getStores = StoreProfile::where('store_name', 'LIKE', "%{$search}%")
                ->orderBy('id', 'DESC')
                ->where('store_status', 'active')
                ->distinct()
                ->get();

            $getService = Service::where('service_name', 'LIKE', "%{$search}%")
                ->orderBy('id', 'DESC')
                ->where('status', 'active')
                ->distinct()
                ->get();

            $getCategory = Category::where('name', 'LIKE', "%{$search}%")
                ->orderBy('id', 'DESC')
                ->where('status', 'active')
                ->where('category_type', 'Cosmetics')
                ->distinct()
                ->get();

            $getStore = [];
            foreach ($getStores as $row) {
                $row->url = 'store';
                $row->search_name = $row->store_name;
                $getStore[] = $row;
            }


            foreach ($getService as $row) {
                $row->url = 'service';
                $row->search_name = $row->service_name;
                $getStore[] = $row;
            }
            foreach ($getCategory as $row) {
                $row->url = 'category';
                $row->search_name = $row->name;
                $getStore[] = $row;
            }
            $response = array();
            foreach($getStore as $name){
                $response[] = array("id"=>$name->id,"name"=>$name->search_name);
            }
            if (!empty($response)) {
                return response()->json(['ResponseCode' => 1, 'ResponseText' => 'Success', 'ResponseData' => $response], 200);
            } else {
                return response()->json(['ResponseCode' => 0, 'ResponseText' => 'no name found', 'ResponseData' => null], 200);
            }
        } catch (\Swift_TransportException $e) {
            \Log::debug($e);
            return response()->json(['ResponseCode' => 0, 'ResponseText' => 'Something went wrong', 'ResponseData' => null], 400);
        }
    }

    /**
     * sorting for store
     */

    public function shortBy(Request $request)
    {
        try {
            $short = $request['sort_by'];  
            $discount = $request['discount'];
            $booking = $request['booking_value'];   
            if (isset($data['user']) == true && !empty($data['user'])) {
                $userId = $data['user']['user_id'];                
            }else{
                $userId = '';  
            }
            $getStore = StoreProfile::leftjoin('store_categories', 'store_categories.store_id', '=', 'store_profiles.id')
                ->leftjoin('services', 'services.store_id', '=', 'store_profiles.id')
                ->with(['storeFavourite' => function($query)use($userId){
                    $query->where('user_id',$userId);
                }]);
            
            if ($short == 'recommanded') {
                $getStore = $getStore->where('store_profiles.is_recommended', 'yes');
            }
            
            if ($short == 'new') {
                $getStore = $getStore->orderBy('store_profiles.id', 'DESC');
            }
            if (!empty($booking)) {
                $getStore = $getStore->where('store_profiles.store_active_plan', 'business');
            }
    
            if (!empty($discount)) {
                $getStore = $getStore->where('services.discount_type', '!=', 'null');                
            }
            
            $getStore = $getStore->where('store_profiles.store_status', 'active')
                            ->select('store_profiles.id','store_profiles.user_id','store_profiles.store_name','store_profiles.store_description',
                            'store_profiles.store_profile','store_profiles.store_banner','store_profiles.store_status','store_profiles.store_address',
                            'store_profiles.category_id','store_profiles.store_active_plan','store_profiles.is_recommended','store_profiles.is_value','services.discount_type')                                                        
                            ->groupBy('store_profiles.id')
                            ->distinct()
                            ->get();
            
            $shorting = [];        
            $ratingNew = [];  
            foreach ($getStore as $row){                                                    
                foreach ($row->storeCategory as $category) {                    
                    $category['name'] = $category['CategoryData']['name'];
                    unset($category->CategoryData);
                }             
                //favourite or note
                $row['favourite'] =!empty($row->storeFavourite) == true ? true : false;
                $row['category_name'] = $row['category_id'];
                //store rating 
                $sum = 0;         
                foreach ($row->storeRated as $rating) {                      
                    $sum += $rating['total_avg_rating'];                                            
                }     
                $row['avg_rating'] = number_format($sum == 0 ? 0 : ($sum / $row->storeRated->count()),2);  
                $row['total_feedback']  =  $row->storeRated->count();

                if ($short == 'low_price') {
                    $row->price = Service::where('store_id', $row->id)->min('price');

                }
                if ($short == 'high_price') {
                    $row->price = Service::where('store_id', $row->id)->max('price');
                }

                if ($short == 'low_price' || $short == 'high_price') {
                    if ($row->price != null) {
                        $shorting[] = $row;
                    }
                }                
                
                if ($short == 'best_rating') {                    
                    if ($row->avg_rating != null) {                        
                        $ratingNew[] = $row;                
                    }
                
                }
                
                unset($row->storeFavourite,$row->storeRated);
            }
            

            if ($short == 'low_price') {
                $keys = array_column($shorting, 'price');

                array_multisort($keys, SORT_ASC, $shorting);
                $getStore = $shorting;
            }
            
            if ($short == 'high_price') {
                $keys = array_column($shorting, 'price');

                array_multisort($keys, SORT_DESC, $shorting);
                $getStore = $shorting;
            }

            if ($short == 'best_rating') {
                $keys = array_column($ratingNew, 'avg_rating');
    
                array_multisort($keys, SORT_DESC, $ratingNew);
                $getStore = $ratingNew;
            }            

            if (count($getStore) > 0) {
                return response()->json(['ResponseCode' => 1, 'ResponseText' => "Service Provider List get Successfully", 'ResponseData' => $getStore], 200);
            } else {
                return response()->json(['ResponseCode' => 0, 'ResponseText' => "No data found", 'ResponseData' => $getStore], 200);           
            }
        } catch (\Swift_TransportException $e) {
            \Log::debug($e);
            return response()->json(['ResponseCode' => 0, 'ResponseText' => 'Something went wrong', 'ResponseData' => null], 400);
        }
        
    }
}
