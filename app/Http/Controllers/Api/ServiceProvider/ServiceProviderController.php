<?php

namespace App\Http\Controllers\Api\ServiceProvider;

use App\Http\Controllers\Controller;
use App\Models\StoreCategory;
use App\Models\StoreGallery;
use App\Models\StoreProfile;
use App\Models\StoreTiming;
use Illuminate\Http\Request;

class ServiceProviderController extends Controller
{
    public function index(Request $request){
        $user = $request['user'];
        $user_id = $user['user_id'];

        $data = StoreProfile::where('store_status','active')->get();

        foreach ($data as $row){

            /**
             * Store Profile Image
             */

            if(file_exists(storage_path('app/public/store/'.$row->store_profile)) && $row->store_profile != ''){
                $row->store_profile = URL::to('storage/app/public/store/'.$row->store_profile);
            } else {
                $row->store_profile = URL::to('storage/app/public/default/default-user.png');
            }

            /**
             * Store Banner Image
             */

            if(file_exists(storage_path('app/public/store/banner/'.$row->store_banner)) && $row->store_banner != ''){
                $row->store_banner = URL::to('storage/app/public/store/banner/'.$row->store_banner);
            } else {
                $row->store_banner = URL::to('storage/app/public/default/default-user.png');
            }

//            /**
//             * Store Gallery
//             */
//            $storeGallery = StoreGallery::where('store_id',$row->id)->get();
//
//            foreach ($storeGallery as $item){
//                if(file_exists(storage_path('app/public/store/gallery/'.$item->file)) && $item->file != ''){
//                    $item->file = URL::to('storage/app/public/store/gallery/'.$item->file);
//                } else {
//                    $item->file = URL::to('storage/app/public/default/default-user.png');
//                }
//            }
//
//            $row->gallery = $storeGallery;
//
//            $row->user = $row->userDara->first_name.' '.$row->userDara->last_name;
//
//            /**
//             * Store Category
//             */
//            $storeCategory = StoreCategory::where('store_id',$row->id)->get();
//
//            foreach ($storeCategory as $rows){
//                $rows->category = @$rows->CategoryData->name;
//            }
//
//            $row->category = $storeCategory;
//
//            $row->timing = StoreTiming::where('store_id',$row->id)->get();

        }
        if(count($data) > 0){
            return response()->json(['ResponseCode' => 1, 'ResponseText' => "Service Provider List get Successfully", 'data' => $data], 200);
        } else {
            return response()->json(['ResponseCode' => 1, 'ResponseText' => "No Record Found", 'data' => null], 200);
        }
    }

    public function serviceProviderView(Request $request){
        $user = $request['user'];
        $user_id = $user['user_id'];

        $data = StoreProfile::where('id',$request['provider_id'])->get();

        /**
         * Store Profile Image
         */

        if(file_exists(storage_path('app/public/store/'.$data['store_profile'])) && $data['store_profile'] != ''){
            $data['store_profile'] = URL::to('storage/app/public/store/'.$data['store_profile']);
        } else {
            $data['store_profile'] = URL::to('storage/app/public/default/default-user.png');
        }

        /**
         * Store Banner Image
         */

        if(file_exists(storage_path('app/public/store/banner/'.$data['store_banner'])) && $data['store_banner'] != ''){
            $data['store_banner'] = URL::to('storage/app/public/store/banner/'.$data['store_banner']);
        } else {
            $data['store_banner'] = URL::to('storage/app/public/default/default-user.png');
        }

        /**
         * Store Gallery
         */
        $storeGallery = StoreGallery::where('store_id',$data['id'])->get();

        foreach ($storeGallery as $item){
            if(file_exists(storage_path('app/public/store/gallery/'.$item->file)) && $item->file != ''){
                $item->file = URL::to('storage/app/public/store/gallery/'.$item->file);
            } else {
                $item->file = URL::to('storage/app/public/default/default-user.png');
            }
        }

        $data['gallery'] = $storeGallery;

        $data['user'] = @$data['userDara']['first_name'].' '.$data['userDara']['last_name'];

        /**
         * Store Category
         */
        $storeCategory = StoreCategory::where('store_id',$data['id'])->get();

        foreach ($storeCategory as $rows){
            $rows->category = @$rows->CategoryData->name;
        }

        $data['category'] = $storeCategory;

        $data['timing'] = StoreTiming::where('store_id',$data['id'])->get();

        return response()->json(['ResponseCode' => 1, 'ResponseText' => "Service Provider Get Successfully", 'data' => $data], 200);
    }
}
