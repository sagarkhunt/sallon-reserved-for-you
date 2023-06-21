<?php

namespace App\Http\Controllers\ServiceProvider;

use App\Models\Category;
use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\StoreCategory;
use App\Models\StoreEmpCategory;
use App\Models\StoreEmpService;
use App\Models\StoreEmpTimeslot;
use App\Models\StoreGallery;
use App\Models\StoreProfile;
use Illuminate\Http\Request;
use App\Models\StoreEmp;
use Auth;
use URL;
use Session;
use File;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $store_id = session('store_id');

        if(empty($store_id)){
            $getStore = StoreProfile::where('user_id', Auth::user()->id)->pluck('id')->all();
        } else{
            $getStore = StoreProfile::where('user_id', Auth::user()->id)->where('id',$store_id)->pluck('id')->all();
        }


        $data = StoreEmp::whereIn('store_id',$getStore)->get();
        foreach($data as $row){
            $day = \Carbon\Carbon::now()->format('l');
            $row->time = StoreEmpTimeslot::where('store_emp_id',$row->id)->where('day',$day)->first();
            $category = StoreEmpCategory::where('store_emp_id',$row->id)->first();
            $row->category = $category->EmpCategoryDetails->name;
            $row->day = $day;
        }

        $category = ['' =>'Select Category'] + Category::leftjoin('store_categories','store_categories.category_id','=','categories.id')
            ->whereIn('store_categories.store_id', $getStore)->pluck('categories.name','categories.id')->all();
        return view('ServiceProvider.Employee.index',compact('data','category'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function addEmployee(Request $request)
    {

        $this->validate($request, [
            'emp_name' => 'required',
//            'country' => 'required',
            'category_name' => 'required',
            'image' => 'required',
        ]);

        $data = $request->all();
        if ($request->file('image')) {
            $file = $request->file('image');
            $filename = 'employee-' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(storage_path('app/public/store/employee/'), $filename);
            $data['image'] = $filename;
        }

        $store_id = session('store_id');

        if(empty($store_id)){
            $store_id = StoreProfile::where('user_id', Auth::user()->id)->value('id');
        }

        $data['store_id'] = $store_id;

        $employee = new StoreEmp();
        $employee->fill($data);
        if($employee->save()){
            /**
             * Store Category
             */
            $category = $request['category_name'];
            $stcat['category_type'] = 'Cosmetics';
            $stcat['category_id'] = $category;
            $stcat['store_emp_id'] = $employee->id;

            $storeCategory = new StoreEmpCategory();
            $storeCategory->fill($stcat);
            $storeCategory->save();

            $service_name = $request['service_name'];

            if(!empty($service_name)){
                foreach ($service_name as $row) {
                    $storeCat['store_emp_id'] = $employee->id;
                    $storeCat['service_id'] = $row;
                    $storeCat['service_name'] = \BaseFunction::getCategoryType($row);

                    $storeCategory = new StoreEmpService();
                    $storeCategory->fill($storeCat);
                    $storeCategory->save();
                }

            }
            /**
             * Time Count
             */

            $day = $request['day'];
            $start_time = $request['start_time'];
            $end_time = $request['end_time'];
            $i = 0;
            foreach ($day as $item) {
                $dayData['store_emp_id'] = $employee->id;
                $dayData['day'] = $item;
                if ($start_time[$i] == '' && $end_time[$i] == '') {
                    $dayData['is_off'] = 'on';
                }
                $dayData['start_time'] = $start_time[$i];
                $dayData['end_time'] = $end_time[$i];

                $dayStore = new StoreEmpTimeslot();
                $dayStore->fill($dayData);
                $dayStore->save();
                $i++;
            }

            return redirect('service-provider/employee-list');
        }


    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editEmployee(Request $request)
    {

        $data = StoreEmp::findorFail($request['employee']);
        $data['image'] = URL::to('storage/app/public/store/employee/'.$data['image']);
        $data['category'] = StoreEmpCategory::where('store_emp_id',$data['id'])->value('category_id');
        $dataservice = array();
        $service = StoreEmpService::where('store_emp_id',$data['id'])->get();
        foreach ($service as $row){
            $dataservice[] = $row->service_id;
        }
        $data['service'] = $dataservice;
        $data['timeslot'] = StoreEmpTimeslot::where('store_emp_id',$data['id'])->get();

        return ['status' => '1', 'data' => $data];

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateEmployee(Request $request)
    {

        $data = $request->all();

        $category_name = $request['category_name'];
        $service_name = $request['service_name'];
        $day = $request['day'];
        $start_time = $request['start_time'];
        $end_time = $request['end_time'];
        $store_emp_id = $request['store_emp_id'];

        $data = $request->except('_token', '_method', 'day', 'start_time', 'end_time', 'is_holiday','store_emp_id','service_name','category_name');

        if ($request->file('image')) {

            $oldimage = StoreEmp::where('id', $store_emp_id)->value('image');

            if (!empty($oldimage)) {

                File::delete('storage/app/public/store/employee/' . $oldimage);
            }

            $file = $request->file('image');
            $filename = 'employee-' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(storage_path('app/public/store/employee/'), $filename);
            $data['image'] = $filename;
        }

        $updateEmp = StoreEmp::where('id',$store_emp_id)->update($data);

        if($updateEmp){

            $categoryDelete = StoreEmpCategory::where('store_emp_id',$store_emp_id)->delete();
            $stcat['category_type'] = 'Cosmetics';
            $stcat['category_id'] = $category_name;
            $stcat['store_emp_id'] = $store_emp_id;

            $storeCategory = new StoreEmpCategory();
            $storeCategory->fill($stcat);
            $storeCategory->save();

            if(!empty($service_name)){
                $serviceDelete = StoreEmpService::where('store_emp_id',$store_emp_id)->delete();

                foreach ($service_name as $row) {
                    $storeCat['store_emp_id'] = $store_emp_id;
                    $storeCat['service_id'] = $row;
                    $storeCat['service_name'] = \BaseFunction::getCategoryType($row);

                    $storeCategory = new StoreEmpService();
                    $storeCategory->fill($storeCat);
                    $storeCategory->save();
                }
            }

            /**
             * Time Count
             */
            $i = 0;
            $serviceDelete = StoreEmpTimeslot::where('store_emp_id',$store_emp_id)->delete();

            foreach ($day as $item) {
                $dayData['store_emp_id'] = $store_emp_id;
                $dayData['day'] = $item;
                if ($start_time[$i] == '' && $end_time[$i] == '') {
                    $dayData['is_off'] = 'on';
                }
                $dayData['start_time'] = $start_time[$i];
                $dayData['end_time'] = $end_time[$i];

                $dayStore = new StoreEmpTimeslot();
                $dayStore->fill($dayData);
                $dayStore->save();
                $i++;
            }

            return redirect('service-provider/employee-list');
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function removeEmployee(Request $request)
    {
        $id = $request['emp_id'];
        $oldimage = StoreEmp::where('id', $id)->value('image');

        if (!empty($oldimage)) {

            File::delete('storage/app/public/store/employee/' . $oldimage);
        }

        $delete = StoreEmp::where('id', $id)->delete();

        if ($delete) {

            return ['status' => 'true'];
        } else {
            return ['status' => 'false'];
        }

    }

    public function getService(Request $request){

        $store_id = session('store_id');

        if(empty($store_id)){
            $getStore = StoreProfile::where('user_id', Auth::user()->id)->pluck('id')->all();
        } else{
            $getStore = StoreProfile::where('user_id', Auth::user()->id)->where('id',$store_id)->pluck('id')->all();
        }

        $data = Service::whereIn('store_id', $getStore)->where('category_id',$request['category_id'])->where('status', 'active')->get();

        return ['status' => '1', 'data' => $data];

    }
}
