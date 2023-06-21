<?php

namespace App\Http\Controllers\Api\ServiceProvider\ServiceList;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Service;
use App\Models\StoreProfile;
use App\Models\StoreEmp;
use App\Models\Category;
use App\Models\StoreCategory;
use Validator;
use URL;
use Mail;
use File;
use Exception;
use Carbon\Carbon;


class ServiceListController extends Controller
{
    public function serviceList(Request $request)
    {
        try {
            
            $userId = $request['user']['user_id'];              
            $category_id = request('category_id') == null ? Null :request('category_id');
            $store_id = $request->store_id;                         
            // $serviceList = StoreProfile::with('serviceDetails')->where('user_id',$userId)->select('id','user_id')->get();
            // $store_id = StoreProfile::where('user_id',$userId)->select('id','user_id')->pluck('id');
            $serviceList = Service::where('store_id',$store_id);            
            // $category = Category::where('category_type','Cosmetics')->select('id','name','image')->get();

            //check condition sorting
            if (isset($request->category_id)) {                                
                $serviceList = $serviceList->where('category_id',$category_id);                  
            }
            $count = $serviceList->count();            
            $serviceList = $serviceList->orderBy('id','DESC')->paginate(100);            
            foreach ($serviceList as $value) {
                $value['finalPrice'] = \BaseFunction::finalPrice($value['id']);
            }
            if ($serviceList->count() <= 0) {
                return response()->json(['ResponseCode' => 0, 'ResponseText' => 'No data found', 'ResponseData' => NULL], 200);
            }
            $data = [
                'service_list' => $serviceList,
                'totalService' => $count
            ];
            return response()->json(['ResponseCode' => 1, 'ResponseText' => 'Successful', 'ResponseData' => $data], 200);
        } catch (\Swift_TransportException $e) {
            \Log::debug($e);
            return response()->json(['ResponseCode' => 0, 'ResponseText' => 'Something went wrong', 'ResponseData' => null], 400);
        }
    }

    //add service
    public function serviceStore(Request $request)
    {
       
        $rule = [
            'service_name' => 'required',
            'price' => 'required',            
            'discount' => 'required',                                 
            'discount_type' => 'required',            
            'duration_of_service' => 'required',            
            'image' => 'required',
        ];

        $message = [
            'service_name.required' => 'service_name is required',
            'price.required' => 'price is required',
            'price.required' => 'price is required',
            'discount.required' => 'discount is required',
            'discount_type.required' => 'discount_type is required',
            'duration_of_service.required' => 'duration of service is required',            
            'image.required' => 'image is required'
        ];

        $validate = Validator::make($request->all(), $rule, $message);

        if ($validate->fails()) {
            return response()->json(['ResponseCode' => 0, 'ResponseText' => 'Validation Fails', 'ResponseData' => $validate->errors()->all()], 422);
        }
        try {
            $data = request()->all();            
            if ($request->file('image')) {

                $file = $request->file('image');                
                $filename = 'Service-' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move(storage_path('app/public/service/'), $filename);
                $data['image'] = $filename;
            }
            
            $service = new Service();
            $service->fill($data);
            $service->save();
            
            return response()->json(['ResponseCode' => 1, 'ResponseText' => 'Service add successfully.', 'ResponseData' => null], 200);
        } catch (\Swift_TransportException $e) {
            \Log::debug($e);
            return response()->json(['ResponseCode' => 0, 'ResponseText' => 'Something went wrong', 'ResponseData' => null], 400);
        }
    }

    //update service

    public function serviceUpdate(Request $request)
    {
        $rule = [
            'service_name' => 'required',
            'price' => 'required',            
            'discount' => 'required',                                 
            'discount_type' => 'required',            
            'duration_of_service' => 'required',            
            // 'image' => 'required',
        ];

        $message = [
            'service_name.required' => 'service_name is required',
            'price.required' => 'price is required',
            'price.required' => 'price is required',
            'discount.required' => 'discount is required',
            'discount_type.required' => 'discount_type is required',
            'duration_of_service.required' => 'duration of service is required',            
            // 'image.required' => 'image is required'
        ];

        $validate = Validator::make($request->all(), $rule, $message);

        if ($validate->fails()) {
            return response()->json(['ResponseCode' => 0, 'ResponseText' => 'Validation Fails', 'ResponseData' => $validate->errors()->all()], 422);
        }
        try {
            $data = request()->all();
            $id = $data['service_id'];
            $checkServiceId = Service::where('id',$id)->first();
            if (empty($checkServiceId)) {
                return response()->json(['ResponseCode' => 0, 'ResponseText' => 'Worng Service id.', 'ResponseData' => ''], 400);
            }
            if ($request->file('image')) {

                $oldimage = Service::where('id', $id)->value('image');
    
                if (!empty($oldimage)) {
    
                    File::delete('storage/app/public/service/' . $oldimage);
                }
    
                $file = $request->file('image');                
                $filename = 'Service-' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move(storage_path('app/public/service/'), $filename);
                $data['image'] = $filename;
            }            
            unset($data['service_id'],$data['user']);
            $update = Service::where('id',$id)->update($data);

            return response()->json(['ResponseCode' => 1, 'ResponseText' => 'Service updated successfully.', 'ResponseData' => $update], 200);
        } catch (\Swift_TransportException $e) {
            \Log::debug($e);
            return response()->json(['ResponseCode' => 0, 'ResponseText' => 'Something went wrong', 'ResponseData' => null], 400);
        }
    }

    //Service delete
    public function serviceDelete(Request $request)
    {
        try {
            $data = request()->all();
            $deleteService = Service::where('id', $data['service_id'])->where('store_id',$data['store_id'])->first();
            if (empty($deleteService)) {
                return response()->json(['ResponseCode' => 0, 'ResponseText' => 'Not found service id.', 'ResponseData' => ''], 400);
            }
            $oldimage = Service::where('id', $data['service_id'])->where('store_id',$data['store_id'])->value('image');

            if (!empty($oldimage)) {
    
                File::delete('storage/app/public/service/' . $oldimage);
            }
    
            $deleteService = $deleteService->delete();
            return response()->json(['ResponseCode' => 1, 'ResponseText' => 'Service delete successfully.', 'ResponseData' => NULL], 200);
        } catch (\Swift_TransportException $e) {
            \Log::debug($e);
            return response()->json(['ResponseCode' => 0, 'ResponseText' => 'Something went wrong', 'ResponseData' => null], 400);
        }
    }

    /**
     * service category
     */

    public function serviceListCategory()
    {
        try {
            $category = Category::where('category_type','Cosmetics')->select('id','name','image')->get();
            if ($category->count() <= 0) {
                return response()->json(['ResponseCode' => 0, 'ResponseText' => 'No category found.', 'ResponseData' => null], 200);    
            }
            return response()->json(['ResponseCode' => 1, 'ResponseText' => 'category list.', 'ResponseData' => $category], 200);    
        } catch (\Swift_TransportException $e) {
            \Log::debug($e);
            return response()->json(['ResponseCode' => 0, 'ResponseText' => 'Something went wrong', 'ResponseData' => null], 400);
        }
    }

    /**
     * store service category list
     */
    public function storeCategory(Request $request)
    {
        try {
            $data = request()->all();
            $store_id = $data['store_id'];
            $category_id = StoreCategory::where('store_id',$store_id)->pluck('category_id');
            $storeAllCate = Category::whereIn('id',$category_id)->select('id','name','image')->get();
            if ($storeAllCate->count() <= 0) {
                return response()->json(['ResponseCode' => 0, 'ResponseText' => 'No category found.', 'ResponseData' => null], 200);
            }
            return response()->json(['ResponseCode' => 1, 'ResponseText' => 'category list.', 'ResponseData' => $storeAllCate], 200);    
        } catch (\Swift_TransportException $e) {
            \Log::debug($e);
            return response()->json(['ResponseCode' => 0, 'ResponseText' => 'Something went wrong', 'ResponseData' => null], 400);
        }
    }
}
