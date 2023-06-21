<?php

namespace App\Http\Controllers\Api\ServiceProvider\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StoreEmp;
use App\Models\StoreEmpCategory;
use App\Models\StoreEmpService;
use App\Models\StoreEmpTimeslot;
use App\Models\StoreCategory;
use App\Models\Service;
use Validator;
use URL;
use Mail;
use File;
use Exception;
use Carbon\Carbon;

class EmployeeListController extends Controller
{
    public function employeeList(Request $request)
    {
        try {
            $data = request()->all();
            $userId  = $request['user']['user_id'];
            // $storeId = \BaseFunction::getStoreDetails($userId);
            $storeId = $data['store_id'];
            
            //get employee in store 
            $employeeList = StoreEmp::with('EmpService','storeDetaials')->where('store_id',$storeId);            
            $count = $employeeList->count();            
            $employeeList = $employeeList->orderBy('id','DESC')->paginate(10);
            foreach ($employeeList as $value) {                    
                $services = array();
                foreach ($value->EmpService as $key) {
                    $services [] = $key['EmpServiceDetails']['service_name'];
                }
                $value['service_name'] = $services;
                $value['store_city'] = $value['storeDetaials']['store_district'];
                $value['store_start_time'] = $value['storeDetaials']['store_start_time'];
                $value['store_end_time'] = $value['storeDetaials']['store_end_time'];
                unset($value->EmpService,$value->storeDetaials);                
            }
            //if check empty or not.
            if ($employeeList->isEmpty()) {                
                return response()->json(['ResponseCode' => 0, 'ResponseText' => 'No data found', 'ResponseData' => NULL], 200);
            }
            $data = [
                'totalEmp'      => $count,
                'empployeeList' => $employeeList,                
            ];
            return response()->json(['ResponseCode' => 1, 'ResponseText' => 'Successful', 'ResponseData' => $data], 200);
            
        } catch (\Swift_TransportException $e) {
            \Log::debug($e);
            return response()->json(['ResponseCode' => 0, 'ResponseText' => 'Something went wrong', 'ResponseData' => null], 400);
        }
    }

    //add employee in store
    public function employeeStore(Request $request)
    {               
        $rule = [
            'emp_name' => 'required',
            // 'country' => 'required',
            'emp_category' => 'required',
            // 'emp_service' => 'required',
            'emp_time_slote' => 'required',
            // 'service_name' => 'required',   
            'image'=>'required'         
        ];

        $message = [
            'emp_name.required' => 'employee name is required',
            // 'country.required' => 'country is required',
            // 'service_name.required' => 'service name is required',            
            'emp_category.required' => 'category is required',            
            'emp_service.required' => 'service is required',            
            'emp_time_slote.required' => 'emp_time_slote is required',    
            'image.requeired' => 'image is required'      
        ];

        $validate = Validator::make($request->all(), $rule, $message);

        if ($validate->fails()) {
            return response()->json(['ResponseCode' => 0, 'ResponseText' => 'Validation Fails', 'ResponseData' => $validate->errors()->all()], 422);
        }
        try {
            $data = request()->all();         
            $categoryType = $data['category_type'];           
            $category = json_decode($data["emp_category"]);
            $service = json_decode($data["emp_service"]);
            $empTimeSlote = json_decode($data['emp_time_slote']);

            if ($request->file('image')) {

                $file = $request->file('image');
                $filename = 'StoreEmp-' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move(storage_path('app/public/employeeImage/'), $filename);
                $data['image'] = $filename;
            }
            unset($data['service_category'],$data['emp_time_slote'],$data['user'],$data['category_type'],$data['category_id']);
            $storeEmp = new StoreEmp();
            $storeEmp->fill($data);
            if ($storeEmp->save()) {    
                if ($category) {
                    foreach ($category as  $value) {                                                                  ;
                        $category_add = new StoreEmpCategory();
                        $category_add->store_emp_id = $storeEmp->id;
                        $category_add->category_type = $categoryType;
                        $category_add->category_id = $value->category_id;
                        $category_add->save();
                    }
                }
                if ($empTimeSlote) {
                    foreach ($empTimeSlote as $row) {
                        $storeTimeSlote = new StoreEmpTimeslot();
                        $storeTimeSlote->store_emp_id = $storeEmp->id;
                        $storeTimeSlote->day = $row->day;
                        $storeTimeSlote->start_time = $row->start_time;
                        $storeTimeSlote->end_time = $row->end_time;
                        $storeTimeSlote->is_off = $row->is_off;
                        $storeTimeSlote->save();
                    }
                }  
                if ($service) {
                    foreach ($service as  $value) {                                                                  ;
                        $service_add = new StoreEmpService();
                        $service_add->store_emp_id = $storeEmp->id;                                                
                        $service_add->service_id = $value->service_id;
                        $service_add->service_name = $value->service_name;
                        $service_add->save();
                    }
                }              
                return response()->json(['ResponseCode' => 1, 'ResponseText' => 'Employee add successfully.', 'ResponseData' => null], 200);
            }
            
        } catch (\Swift_TransportException $e) {
            \Log::debug($e);
            return response()->json(['ResponseCode' => 0, 'ResponseText' => 'Something went wrong', 'ResponseData' => null], 400);
        }
    }

    public function employeeView(Request $request)
    {
        try {
            $data = request()->all();
            
            $viewEmp = StoreEmp::with('EmpCategory','EmpService','EmpTimeSlot')->where('id',$data['emp_id'])->first();
            if (!empty($viewEmp)) {       
                //category
                $category = array();
                foreach ($viewEmp->EmpCategory as $key) {
                    $category[] = $key['EmpCategoryDetails']['name'];
                }         
                //service
                $service = array();
                foreach ($viewEmp->EmpService as $key) {
                    $service [] = $key['EmpServiceDetails']['service_name'];
                }
                $viewEmp['category_name'] = $category;
                $viewEmp['service_name'] = $service;
                unset($viewEmp->EmpCategory,$viewEmp->EmpService);
                return response()->json(['ResponseCode' => 1, 'ResponseText' => 'Employee add successfully.', 'ResponseData' => $viewEmp], 200);
            }
            return response()->json(['ResponseCode' => 0, 'ResponseText' => 'wrong employee id.', 'ResponseData' => null], 200);
        } catch (\Swift_TransportException $e) {
            \Log::debug($e);
            return response()->json(['ResponseCode' => 0, 'ResponseText' => 'Something went wrong', 'ResponseData' => null], 400);
        }
    }

    //update employee
    public function employeeUpdate(Request $request)
    {
        
        $rule = [
            'emp_name' => 'required',
            // 'country' => 'required',
            'emp_category' => 'required',
            'emp_service' => 'required',
            'emp_time_slote' => 'required'                     
        ];

        $message = [
            'emp_name.required' => 'employee name is required',
            // 'country.required' => 'country is required',                       
            'emp_category.required' => 'category is required',            
            'emp_service.required' => 'service is required',            
            'emp_time_slote.required' => 'emp_time_slote is required',            
        ];

        $validate = Validator::make($request->all(), $rule, $message);

        if ($validate->fails()) {
            return response()->json(['ResponseCode' => 0, 'ResponseText' => 'Validation Fails', 'ResponseData' => $validate->errors()->all()], 422);
        }
        try {
            $data = request()->all();                        
            
            $categoryType = $data['category_type'];            
            $empTimeSlote = json_decode($data['emp_time_slote']);            
            $category = json_decode($data["emp_category"]) ;            
            $service = json_decode($data['emp_service']) ;  
            $checkEmpId =StoreEmp::where('id',$data['emp_id'])->first();
            if (empty($checkEmpId)) {
                return response()->json(['ResponseCode' => 0, 'ResponseText' => 'Worng emp id.', 'ResponseData' => ''], 400);
            }
            $updateEmp = StoreEmp::where('id',$data['emp_id'])->where('store_id',$data['store_id'])->delete();
            if ($updateEmp) {                

                if ($request->file('image')) {

                    $oldimage = StoreEmp::where('id', $data['emp_id'])->value('image');
        
                    if (!empty($oldimage)) {    
                        File::delete('app/public/employeeImage/' . $oldimage);
                    }
        
                    $file = $request->file('image');
                    $filename = 'StoreEmp-' . uniqid() . '.' . $file->getClientOriginalExtension();
                    $file->move(storage_path('app/public/employeeImage/'), $filename);
                    $data['image'] = $filename;
                }    
                unset($data['emp_category'],$data['emp_time_slote'],$data['user'], $data['category_type'], $data['emp_service']);
                $storeEmp = new StoreEmp();
                $storeEmp->fill($data);
                if ($storeEmp->save()) {
                    /**
                     * update category emp
                     */
                    if ($category) {
                        foreach ($category as  $value) {                                 
                            $category_add = new StoreEmpCategory();
                            $category_add->store_emp_id = $storeEmp->id;
                            $category_add->category_type = $categoryType;
                            $category_add->category_id = $value->category_id;                            
                            $category_add->save();
                        }
                    }
                    /**
                     * update service emp
                     */
                    if ($service) {
                        foreach ($service as  $value) {                                 
                            $service_add = new StoreEmpService();
                            $service_add->store_emp_id = $storeEmp->id;                            
                            $service_add->service_id = $value->service_id;
                            $service_add->service_name = $value->service_name;
                            $service_add->save();
                        }
                    }
                    /**
                     * update time slot emp
                     */
                    if ($empTimeSlote) {
                        foreach ($empTimeSlote as $row) {
                            $storeTimeSlote = new StoreEmpTimeslot();
                            $storeTimeSlote->store_emp_id = $storeEmp->id;
                            $storeTimeSlote->day = $row->day;
                            $storeTimeSlote->start_time = $row->start_time;
                            $storeTimeSlote->end_time = $row->end_time;
                            $storeTimeSlote->is_off = $row->is_off;
                            $storeTimeSlote->save();
                        }
                    }                
                    return response()->json(['ResponseCode' => 1, 'ResponseText' => 'Employee update successfully.', 'ResponseData' => null], 200);
                }
            }
            return response()->json(['ResponseCode' => 0, 'ResponseText' => 'Something went wrong', 'ResponseData' => null], 400);
            
        } catch (\Swift_TransportException $e) {
            \Log::debug($e);
            return response()->json(['ResponseCode' => 0, 'ResponseText' => 'Something went wrong', 'ResponseData' => null], 400);
        }
    }

    //delete store employee
    public function employeeDelete(Request $request)
    {
        try {
            $data = request()->all();
            $deleteEmp = StoreEmp::where('id',$data['emp_id'])->where('store_id',$data['store_id'])->first();
            if (empty($deleteEmp)) {
                return response()->json(['ResponseCode' => 0, 'ResponseText' => 'Not found emp id.', 'ResponseData' => ''], 400);
            }
            $oldimage = StoreEmp::where('id',$data['emp_id'])->where('store_id',$data['store_id'])->value('image');
            if (!empty($oldimage)) {
    
                File::delete('storage/app/public/service/' . $oldimage);
            }
            $deleteEmp = $deleteEmp->delete();
            return response()->json(['ResponseCode' => 1, 'ResponseText' => 'Employee delete successfully.', 'ResponseData' => NULL], 200);
        } catch (\Swift_TransportException $e) {
            \Log::debug($e);
            return response()->json(['ResponseCode' => 0, 'ResponseText' => 'Something went wrong', 'ResponseData' => null], 400);
        }
    }

    /**
     * get store all category
     */
    public function storeCategory(Request $request)
    {
        try {
            $data = request()->all ();
            $storeCategory = StoreCategory::with('CategoryData')->where('store_id',$data['store_id'])->get();
            
            $categoryList = [];
            foreach ($storeCategory as $value) {
                $categoryList [] = [
                    'category_id' => $value['category_id'],
                    'category_name' => $value['CategoryData']['name'],
                    'category_image' => $value['CategoryData']['category_image_path'],
                ];
            }

            if (count($categoryList) == 0) {
                return response()->json(['ResponseCode' => 0, 'ResponseText' => 'No List', 'ResponseData' =>NULL], 200);
            }
            return response()->json(['ResponseCode' => 1, 'ResponseText' => 'Category list.', 'ResponseData' => $categoryList], 200);
        } catch (\Swift_TransportException $e) {
            \Log::debug($e);
            return response()->json(['ResponseCode' => 0, 'ResponseText' => 'Something went wrong', 'ResponseData' => null], 400);
        }
    }

    /**
     * get store all service
     */
    public function storeService(Request $request)
    {
        try {
            $data = request()->all();
            if (isset($data['category_id']) && isset($data['store_id'])) {                
                $storeService = Service::where('store_id',$data['store_id'])->where('category_id',$data['category_id'])->select('id','service_name','image')->get();    
            }else{                                                
                $storeService = Service::where('store_id',$data['store_id'])->select('id','service_name','image')->get();
            }
            if (count($storeService) == 0) {
                return response()->json(['ResponseCode' => 0, 'ResponseText' => 'No List', 'ResponseData' =>NULL], 200);
            }
            return response()->json(['ResponseCode' => 1, 'ResponseText' => 'Service list.', 'ResponseData' => $storeService], 200);
        } catch (\Swift_TransportException $e) {
            \Log::debug($e);
            return response()->json(['ResponseCode' => 0, 'ResponseText' => 'Something went wrong', 'ResponseData' => null], 400);
        }
    }
}
