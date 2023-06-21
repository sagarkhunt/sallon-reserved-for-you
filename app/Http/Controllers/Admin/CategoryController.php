<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use URL;
use File;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Category::where('category_type','Gastronomy')->get();
        return view('Admin.Category.index',compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = ['' => 'Select Category'] + Category::where('category_type','Gastronomy')->where('status','active')->pluck('name','id')->all();
        return view('Admin.Category.create',compact('data'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'image' => 'mimes:svg'
        ]);

        $data = $request->all();

        if ($request->file('image')) {
            $file = $request->file('image');
            $filename = 'category-' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(storage_path('app/public/category/'), $filename);
            $data['image'] = $filename;
        }
        $data['category_type'] = 'Gastronomy';
        $data['slug'] = str_replace(' ','-',strtolower($request['name']));
        $category = new Category();
        $category->fill($data);
        if($category->save()){
            Session::flash('message','<div class="alert alert-success">Category Created Successfully.!! </div>');
            return redirect('master-admin/category');
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

    public function edit($id)
    {
        $data = Category::findorFail($id);
        $category = [''=>'Select Category'] + Category::where('category_type','Gastronomy')->where('status','active')->pluck('name','id')->all();
        return view('Admin.Category.edit',compact('data','category'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
            'image' => 'mimes:svg'
        ]);

        $data = $request->all();
        $data = $request->except('_token', '_method');

        if ($request->file('image')) {

            $oldimage = Category::where('id', $id)->value('image');

            if (!empty($oldimage)) {

                File::delete('storage/app/public/category/' . $oldimage);
            }

            $file = $request->file('image');
            $filename = 'category-' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move('storage/app/public/category', $filename);
            $data['image'] = $filename;
        }
        $data['slug'] = str_replace(' ','-',strtolower($request['name']));

        $update = Category::where('id',$id)->update($data);

        if($update){
            Session::flash('message','<div class="alert alert-success">User Updated Successfully.!! </div>');
            return redirect('master-admin/category');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */

    public function destroy($id)
    {
        $oldimage = Category::where('id', $id)->value('image');

        if (!empty($oldimage)) {

            File::delete('storage/app/public/category/' . $oldimage);
        }

        $delete = Category::where('id',$id)->delete();

        if($delete){
            Session::flash('message', '<div class="alert alert-danger"><strong>Alert!</strong> Category Deleted successfully. </div>');

            return redirect('master-admin/category');
        }
    }

    /*
      Change user Status
  */

    public function statusChange($id){
        $users  = Category::findorFail($id);

        if($users['status'] == 'active'){
            $newStatus = 'inactive';
        } else {
            $newStatus = 'active';
        }

        $update = Category::where('id',$id)->update(['status'=>$newStatus]);

        if($update){
            Session::flash('message', '<div class="alert alert-success"><strong>Success!</strong> Category status updated successfully. </div>');

            return redirect('master-admin/category');
        }
    }
}
