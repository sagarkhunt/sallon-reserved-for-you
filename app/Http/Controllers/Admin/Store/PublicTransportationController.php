<?php

namespace App\Http\Controllers\Admin\Store;

use App\Http\Controllers\Controller;
use App\Models\Store\PublicTransportation;
use Illuminate\Http\Request;
use Session;
use File;

class PublicTransportationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $data = PublicTransportation::where('store_id',$id)->get();
        return view('Admin.Store.PublicTransportation.index',compact('data','id'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        return view('Admin.Store.PublicTransportation.create',compact('id'));
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
            'title'=>'required',
            'transportation_no'=>'required',
            'image'=>'required|mimes:svg',
        ]);

        $data = $request->all();
        $data['store_id'] = $id;
        if ($request->file('image')) {

            $file = $request->file('image');
            $filename = 'store-transportation-' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(storage_path('app/public/store/transportation/'), $filename);
            $data['image'] = $filename;
        }

        $publicTransportation = new PublicTransportation();
        $publicTransportation->fill($data);
        if($publicTransportation->save()){
            Session::flash('message','<div class="alert alert-success">Public Transportation added Successfully.!! </div>');
            return redirect('master-admin/store-profile/'.$id.'/public-transportation');
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
        $data = PublicTransportation::where('store_id',$store_id)->where('id',$id)->first();
        return view('Admin.Store.PublicTransportation.edit',compact('data','id'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$store_id, $id)
    {
        $this->validate($request,[
            'title'=>'required',
            'transportation_no'=>'required',
            'image'=>'mimes:svg',
        ]);

        $data = $request->all();
        $data = $request->except('_token','_method');

        if ($request->file('image')) {

            $oldimage = PublicTransportation::where('store_id',$store_id)->where('id', $id)->value('image');

            if (!empty($oldimage)) {

                File::delete('storage/app/public/store/transportation/' . $oldimage);
            }

            $file = $request->file('image');
            $filename = 'store-transportation-' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(storage_path('app/public/store/transportation/'), $filename);
            $data['image'] = $filename;
        }

        $update = PublicTransportation::where('store_id',$store_id)->where('id',$id)->update($data);

        if($update){
            Session::flash('message','<div class="alert alert-success">Public Transportation Updated Successfully.!! </div>');
            return redirect('master-admin/store-profile/'.$id.'/public-transportation');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($store_id, $id)
    {
        $oldimage = PublicTransportation::where('store_id',$store_id)->where('id', $id)->value('image');

        if (!empty($oldimage)) {

            File::delete('storage/app/public/store/transportation/' . $oldimage);
        }

        $delete = PublicTransportation::where('store_id',$store_id)->where('id',$id)->delete();

        if($delete){
            Session::flash('message','<div class="alert alert-danger">Public Transportation Deleted Successfully.!! </div>');
            return redirect('master-admin/store-profile/'.$id.'/public-transportation');
        }
    }
}
