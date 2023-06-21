<?php

namespace App\Http\Controllers\Admin\Store;

use App\Http\Controllers\Controller;
use App\Models\Store\Advantages;
use Illuminate\Http\Request;
use File;
use Session;

class AdvantagesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index($id)
    {
        $data = Advantages::where('store_id',$id)->get();
        return view('Admin.Store.Advantages.index',compact('data','id'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function create($id)
    {
        return view('Admin.Store.Advantages.create',compact('id'));
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
           'description'=>'required',
           'image'=>'required|mimes:svg',
        ]);

        $data = $request->all();
        $data['store_id'] = $id;

        if ($request->file('image')) {

            $file = $request->file('image');
            $filename = 'store-advantage-' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(storage_path('app/public/store/advantage/'), $filename);
            $data['image'] = $filename;
        }

        $advantages = new Advantages();
        $advantages->fill($data);
        if($advantages->save()){
            Session::flash('message','<div class="alert alert-success">Advantages Created Successfully.!! </div>');
            return redirect('master-admin/store-profile/'.$id.'/advantages');
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
        $data = Advantages::where('store_id',$store_id)->where('id',$id)->first();
        return view('Admin.Store.Advantages.edit',compact('data','store_id'));
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
            'title'=>'required',
            'description'=>'required',
            'image'=>'mimes:jpg,png,gif,jpeg',
        ]);

        $data = $request->all();
        $data = $request->except('_token','_method');

        if ($request->file('image')) {

            $oldimage = Advantages::where('store_id',$store_id)->where('id', $id)->value('image');

            if (!empty($oldimage)) {

                File::delete('storage/app/public/store/advantage/' . $oldimage);
            }

            $file = $request->file('image');
            $filename = 'store-advantage-' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(storage_path('app/public/store/advantage/'), $filename);
            $data['image'] = $filename;
        }

        $update = Advantages::where('store_id',$store_id)->where('id',$id)->update($data);

        if($update){
            Session::flash('message','<div class="alert alert-success">Advantages Updated Successfully.!! </div>');
            return redirect('master-admin/store-profile/'.$id.'/advantages');
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
        $oldimage = Advantages::where('store_id',$store_id)->where('id', $id)->value('image');

        if (!empty($oldimage)) {

            File::delete('storage/app/public/store/advantage/' . $oldimage);
        }

        $delete = Advantages::where('store_id',$store_id)->where('id',$id)->delete();

        if($delete){
            Session::flash('message','<div class="alert alert-danger">Advantages Deleted Successfully.!! </div>');
            return redirect('master-admin/store-profile/'.$id.'/advantages');
        }
    }
}
