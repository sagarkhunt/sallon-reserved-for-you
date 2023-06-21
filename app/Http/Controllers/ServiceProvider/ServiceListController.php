<?php

namespace App\Http\Controllers\ServiceProvider;


use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\StoreCategory;
use App\Models\Category;
use App\Models\StoreProfile;
use Illuminate\Http\Request;
use Auth;
use URL;
use Session;
use File;

class ServiceListController extends Controller
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

        $category = StoreCategory::whereIn('store_id', $getStore)->groupBy('category_id')->get();
        
        $fistCategory = StoreCategory::whereIn('store_id', $getStore)->groupBy('category_id')->first();
        if (count($category) > 0) {
            $service = Service::where('category_id', $fistCategory['category_id'])->whereIn('store_id', $getStore)->get();
        } else {
            $service = array();
        }

        $getStoreListing = StoreProfile::where('user_id', Auth::user()->id)->pluck('store_name', 'id')->all();
        $categoryData = ['' => 'Select Category'] + Category::join('store_categories', 'store_categories.category_id', '=', 'categories.id')
                ->whereIn('store_categories.store_id', $getStore)->groupBy('category_id')->pluck('categories.name', 'categories.id')->all();

        return view('ServiceProvider.Service.index', compact('getStore', 'category', 'service', 'fistCategory', 'categoryData', 'getStoreListing'));
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
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function addService(Request $request)
    {
//        dd($request->all());
        $this->validate($request, [
//            'store_id' => 'required',
            'service_name' => 'required',
            'description' => 'required',
            'price' => 'required',
            'category_id' => 'required',
            'duration_of_service' => 'required',
        ]);

        $data = $request->all();
        if ($request->file('image')) {
            $file = $request->file('image');
            $filename = 'Service-' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(storage_path('app/public/service/'), $filename);
            $data['image'] = $filename;
        }
        $store_id = session('store_id');

        if(empty($store_id)){
            $store_id = StoreProfile::where('user_id', Auth::user()->id)->value('id');
        }

        $data['store_id'] = $store_id;
        $data['status'] = 'active';

        $service = new Service();
        $service->fill($data);
        if ($service->save()) {
            Session::flash('message', '<div class="alert alert-success">Service Created Successfully.!! </div>');
            return redirect('service-provider/service-list');
        }


    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function editService(Request $request)
    {
        $id = $request['service_id'];
        $data = Service::findorFail($id);
        $data['image'] = URL::to('storage/app/public/service/' . $data['image']);

        if (!empty($data)) {
            return ['status' => 'true', 'data' => $data];
        } else {
            return ['status' => 'false', 'data' => []];
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function updateService(Request $request)
    {
        $this->validate($request, [
//            'store_id' => 'required',
            'service_name' => 'required ',
            'description' => 'required',
            'price' => 'required',
            'category_id' => 'required',
            'duration_of_service' => 'required',
        ]);

        $data = $request->all();
        $id = $request['service_id'];
        $data = $request->except('_token', '_method', 'service_id');

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

        $update = Service::where('id', $id)->update($data);

        if ($update) {
            Session::flash('message', '<div class="alert alert-success">Service Updated Successfully.!! </div>');
            return redirect('service-provider/service-list');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function removeService(Request $request)
    {
        $id = $request['service_id'];
        $oldimage = Service::where('id', $id)->value('image');

        if (!empty($oldimage)) {

            File::delete('storage/app/public/service/' . $oldimage);
        }

        $delete = Service::where('id', $id)->delete();

        if ($delete) {

            return ['status' => 'true'];
        } else {
            return ['status' => 'false'];
        }

    }

    public function getService(Request $request)
    {
        $id = $request['id'];
        $store_id = session('store_id');

        if(empty($store_id)){
            $getStore = StoreProfile::where('user_id', Auth::user()->id)->pluck('id')->all();
        } else{
            $getStore = StoreProfile::where('user_id', Auth::user()->id)->where('id',$store_id)->pluck('id')->all();
        }


        $service = Service::where('category_id', $id)->whereIn('store_id', $getStore)->get();

        foreach ($service as $row) {
            if(file_exists(storage_path('app/public/service/'.$row['image'])) && $row['image'] != ''){
                $row->image = URL::to('storage/app/public/service/' . $row['image']);
            } else {
                $row->image = URL::to('storage/app/public/default/store_default.png');
            }

            $row->discountedPrice = \BaseFunction::finalPrice($row->id);

        }

        if (count($service) > 0) {
            return ['status' => 'true', 'data' => $service];
        } else {
            return ['status' => 'false', 'data' => []];
        }
    }

    public function changeCategory(Request $request)
    {
        $category_id = $request['category_id'];

        $data = Category::where('main_category', $category_id)->where('status', 'active')->get();

        return ['status' => '1', 'data' => $data];

    }
}
