<?php

namespace App\Http\Controllers\Api\ServiceProvider\StoreProfile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StoreProfile;
use App\Models\StoreCategory;
use App\Models\StoreGallery;
use Validator;
use URL;
use Mail;
use File;
use Exception;
use Carbon\Carbon;


class StoreProfileController extends Controller
{
    public function getStoreDetails(Request $request)
    {
        try {
            $data = request()->all();
            $userId = $data['user']['user_id'];
            $store_id = $data['store_id'];
            $storeDetail = StoreProfile::with('storeCategory','storeCategory.CategoryData','storeGallery')->where('user_id',$userId)->where('id',$store_id)->first();            
            if (empty($storeDetail)) {
                return response()->json(['ResponseCode' => 0, 'ResponseText' => 'No store details Found.', 'ResponseData' => NULL], 200);
            }
            $category = [];
            foreach ($storeDetail->storeCategory as  $value) {
                $category[] = $value['CategoryData']['name'];   
                unset($value->CategoryData);
            }            
            $storeDetail['category_name'] = $category;
            unset($storeDetail->storeCategory);
            return response()->json(['ResponseCode' => 1, 'ResponseText' => 'Successfull', 'ResponseData' => $storeDetail], 200);
        } catch (\Swift_TransportException $e) {
            \Log::debug($e);
            return response()->json(['ResponseCode' => 0, 'ResponseText' => 'Something went wrong', 'ResponseData' => null], 400);
        }
    }

    //update store details
    public function updateStore(Request $request)
    {       
        $rule = [
            'store_name' => 'required',
            'store_address' => 'required',
            'store_category' => 'required',
            'store_start_time' => 'required',
            'store_end_time' => 'required',            
            'store_contact_number' => 'required',  
        ];

        $message = [
            'store_name.required' => 'store name is required',
            'store_address.required' => 'store_address is required',
            'store_category.required' => 'store_category is required',            
            'store_start_time.required' => 'store_start_time is required',            
            'store_end_time.required' => 'store_end_time is required',            
            'store_contact_number.required' => 'store_contact_number is required',                                            
        ];

        $validate = Validator::make($request->all(), $rule, $message);

        if ($validate->fails()) {
            return response()->json(['ResponseCode' => 0, 'ResponseText' => 'Validation Fails', 'ResponseData' => $validate->errors()->all()], 422);
        }
        try {
            $data = request()->all();     
                    
            $category = json_decode($data['store_category']);   
                        
            $storeId = $data['store_id'];
            $userId = $data['user']['user_id'];
            $checkStoreId = StoreProfile::where('id',$storeId)->where('user_id',$userId)->first();
            
            if (empty($checkStoreId)) {
                return response()->json(['ResponseCode' => 0, 'ResponseText' => 'Not found store id.', 'ResponseData' => ''], 400);
            }
            if ($request->file('store_profile')) {

                $oldimage = StoreProfile::where('id', $storeId)->value('store_profile');
                
                if (!empty($oldimage)) {
    
                    File::delete('storage/app/public/store/' . $oldimage);
                }
    
                $file = $request->file('store_profile');
                $filename = 'Store-' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move(storage_path('app/public/store/'), $filename);
                $data['store_profile'] = $filename;
            }
    
            if ($request->file('store_banner')) {
    
                $oldimage = StoreProfile::where('id', $storeId)->value('store_banner');
    
                if (!empty($oldimage)) {
    
                    File::delete('storage/app/public/store/banner/' . $oldimage);
                }
    
                $file = $request->file('store_banner');
                $filename = 'Store-' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move(storage_path('app/public/store/banner/'), $filename);
                $data['store_banner'] = $filename;
            }    
            
            unset($data['user'], $data['store_category'],$data['store_id']);            
            $update = StoreProfile::where('id', $storeId)->where('user_id',$userId)->update($data);
            if ($update) {
                $deleteCategory = StoreCategory::where('store_id', $storeId)->delete();
                    foreach ($category as $row) {
                        $storeCategory = new StoreCategory();
                        $storeCategory->store_id = $row->store_id;
                        $storeCategory->category_id = $row->category_id ;                       
                        $storeCategory->save();
                    }

                return response()->json(['ResponseCode' => 1, 'ResponseText' => 'Store Profile Update Successful', 'ResponseData' =>null], 200);                

            }
        } catch (\Swift_TransportException $e) {
            \Log::debug($e);
            return response()->json(['ResponseCode' => 0, 'ResponseText' => 'Something went wrong', 'ResponseData' => null], 400);
        }
    }

    //update store gallery
    public function updateStoreGallery(Request $request)
    {
        $rule = [
            'store_gallery_image' => 'required',
        ];

        $message = [
            'store_gallery_image.required' => 'image is required',
        ];

        $validate = Validator::make($request->all(), $rule, $message);

        if ($validate->fails()) {
            return response()->json(['ResponseCode' => 0, 'ResponseText' => 'Validation Fails', 'ResponseData' => $validate->errors()->all()], 422);
        }

        try {
            $data = request()->all();  
            $userId = $data['user']['user_id'];     
            $checkStoreId = StoreProfile::where('id',$data['store_id'])->where('user_id',$userId)->first();
            if (empty($checkStoreId)) {
                return response()->json(['ResponseCode' => 0, 'ResponseText' => 'Not found store id.', 'ResponseData' => ''], 400);
            }                 
            $storeGallery = $data['store_gallery_image'];                                    
            if(!empty($storeGallery)){
                foreach ($storeGallery as $item) {                    
                    if (!empty($item)) {
                        $extension = $item->getClientOriginalExtension();

                        $destinationpath = storage_path('app/public/store/gallery/');

                        $filename = 'Store-' . uniqid() . '-' . rand(1, 9999) . '.' . $extension;

                        $item->move($destinationpath, $filename);

                        $galleryImage['file'] = $filename;
                        $galleryImage['file_type'] = 'image';
                        $galleryImage['store_id'] = $data['store_id'];

                        $product_img = new StoreGallery();
                        $product_img->fill($galleryImage);
                        $product_img->save();
                    }
                }
                return response()->json(['ResponseCode' => 1, 'ResponseText' => 'Update Image in Gallary Successful', 'ResponseData' =>null], 200);
            }
            return response()->json(['ResponseCode' => 0, 'ResponseText' => 'Something went wrong', 'ResponseData' => null], 400);
        } catch (\Swift_TransportException $e) {
            \Log::debug($e);
            return response()->json(['ResponseCode' => 0, 'ResponseText' => 'Something went wrong', 'ResponseData' => null], 400);
        }
    }

    /**
     * delete gallery image
     */
    public function deleteGalleryImage(Request $request)
    {
        try {
            $data = request()->all();
            $userId = $data['user']['user_id'];
            $checkStoreId = StoreProfile::where('id',$data['store_id'])->where('user_id',$userId)->first();
            if (empty($checkStoreId)) {
                return response()->json(['ResponseCode' => 0, 'ResponseText' => 'Not found store id.', 'ResponseData' => ''], 400);
            }
            $storeGallery = json_decode($data["store_gallery_image"]);                  
            if(!empty($storeGallery)){
                foreach ($storeGallery as $item) {                                        
                    if (!empty($item)) {                        
                        $oldimage = StoreGallery::where('id', $item->g_id)->value('file');                        
                        if (!empty($oldimage)) {
                            File::delete('storage/app/public/store/gallery/' . $oldimage);
                        }
                        $delete = StoreGallery::where('id', $item->g_id)->delete();                        
                    }

                }                
                return response()->json(['ResponseCode' => 1, 'ResponseText' => 'Delete Image Successful', 'ResponseData' =>null], 200);
            }
            return response()->json(['ResponseCode' => 0, 'ResponseText' => 'Something went wrong', 'ResponseData' => null], 400);
        } catch (\Swift_TransportException $e) {
            \Log::debug($e);
            return response()->json(['ResponseCode' => 0, 'ResponseText' => 'Something went wrong', 'ResponseData' => null], 400);
        }
    }
}
