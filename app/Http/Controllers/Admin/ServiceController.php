<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Service;
use App\Models\StoreProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use File;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $data = Service::get();
        $store = StoreProfile::findorFail($id);
        return view('Admin.Service.index',compact('data','store'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        $store = StoreProfile::findorFail($id);

        $category = ['' => 'Select Category']  + Category::leftjoin('store_categories','store_categories.category_id','categories.id')
                ->where('categories.category_type',$store['category_id'])
                ->where('store_categories.store_id',$store['id'])
                ->where('categories.status','active')
                ->where('categories.main_category',null)
                ->pluck('categories.name','categories.id')->all();
        return view('Admin.Service.create', compact( 'category','store'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request,$id)
    {

        $this->validate($request,[
            'store_id'=>'required',
            'category_id'=>'required',
            'service_name'=>'required',
            'price'=>'required',
            'description'=>'required',
            'duration_of_service'=>'required',
            'image'=>'required|mimes:jpeg,png,jpg,gif,svg',
        ]);

        $data = $request->all();
        $data['store_id'] = $id;
        if ($request->file('image')) {

            $file = $request->file('image');
            $filename = 'Service-' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(storage_path('app/public/service/'), $filename);
            $data['image'] = $filename;
        }
        $data['status'] = 'active';
        $service = new Service();
        $service->fill($data);
        if($service->save()){
            Session::flash('message', '<div class="alert alert-success">Service Created Successfully.!! </div>');
            return redirect('master-admin/store-profile/'.$id);
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
    public function edit($store_id,$id)
    {

        $store = StoreProfile::findorFail($store_id);

        $category = ['' => 'Select Category']  + Category::leftjoin('store_categories','store_categories.category_id','categories.id')
                ->where('categories.category_type',$store['category_id'])
                ->where('store_categories.store_id',$store['id'])
                ->where('categories.status','active')
                ->where('categories.main_category',null)
                ->pluck('categories.name','categories.id')->all();
        
        $data = Service::findorFail($id);
        return view('Admin.Service.edit', compact( 'category','store','data'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$store_id,$id)
    {

        $this->validate($request,[
            'store_id'=>'required',
            'category_id'=>'required',
            'service_name'=>'required',
            'price'=>'required',
            'description'=>'required',
            'duration_of_service'=>'required',
            'image'=>'mimes:jpeg,png,jpg,gif,svg',
        ]);

        $data = $request->all();
        $data = $request->except('_token', '_method','store_id');

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

        $update = Service::where('id',$id)->update($data);
        if($update){
            Session::flash('message', '<div class="alert alert-success">Service Updated Successfully.!! </div>');
            return redirect('master-admin/store-profile/'.$store_id);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($store_id,$id)
    {
        $oldimage = Service::where('id', $id)->value('image');

        if (!empty($oldimage)) {

            File::delete('storage/app/public/service/' . $oldimage);
        }

        $delete = Service::where('id', $id)->delete();

        if ($delete) {
            Session::flash('message', '<div class="alert alert-danger"><strong>Alert!</strong> Service Deleted successfully. </div>');

            return redirect('master-admin/store-profile/'.$store_id);
        }
    }

    public function changeCategory(Request $request){
        $category_id = $request['category_id'];

        $data = Category::where('main_category',$category_id)->where('status','active')->get();

        return ['status' => '1','data' => $data];

    }
}
