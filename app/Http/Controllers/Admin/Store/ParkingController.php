<?php

namespace App\Http\Controllers\Admin\Store;

use App\Http\Controllers\Controller;
use App\Models\Store\Parking;
use Illuminate\Http\Request;
use File;
use Session;

class ParkingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $data = Parking::where('store_id',$id)->get();
        return view('Admin.Store.Parking.index',compact('data','id'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        return view('Admin.Store.Parking.create',compact('id'));
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
            'parking_name'=>'required'
        ]);

        $data = $request->all();
        $data['store_id'] = $id;

        $parking = new Parking();
        $parking->fill($data);
        if($parking->save()){
            Session::flash('message','<div class="alert alert-success">Parking Added Successfully.!! </div>');
            return redirect('master-admin/store-profile/'.$id.'/parking');
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
        $data = Parking::where('store_id',$store_id)->where('id',$id)->first();
        return view('Admin.Store.Parking.edit',compact('data','store_id'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $store_id,$id)
    {
        $this->validate($request,[
            'parking_name'=>'required'
        ]);

        $data = $request->all();
        $data = $request->except('_token');

        $update = Parking::where('store_id',$store_id)->where('id',$id)->update($data);

        if($update){
            Session::flash('message','<div class="alert alert-success">Parking Updated Successfully.!! </div>');
            return redirect('master-admin/store-profile/'.$id.'/parking');
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
        $delete = Parking::where('store_id',$store_id)->where('id',$id)->delete();

        if($delete){
            Session::flash('message','<div class="alert alert-danger">Parking Deleted Successfully.!! </div>');
            return redirect('master-admin/store-profile/'.$id.'/parking');
        }
    }
}
