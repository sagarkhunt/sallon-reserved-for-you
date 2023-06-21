<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Hash;
use File;

class ServiceProviderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = User::leftjoin('role_user', 'role_user.user_id', '=', 'users.id')
            ->where('role_user.role_id', 3)->get();

        return view('Admin.ServiceProvider.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('Admin.ServiceProvider.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:6',
            'phone_number' => 'required|numeric',
            'profile_pic' => 'mimes:jpeg,png,jpg,gif,svg'
        ]);

        $data = $request->all();
        $data['password'] = Hash::make($request['password']);

        if ($request->file('profile_pic')) {

            $file = $request->file('profile_pic');
            $filename = 'user-' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(storage_path('app/public/user/'), $filename);
            $data['profile_pic'] = $filename;
        }

        $user = new User();
        $user->fill($data);
        if($user->save()){
            $clientrole = Role::where('id', 3)->first();
            $user->attachRole($clientrole);

            Session::flash('message','<div class="alert alert-success">Service Provider Created Successfully.!! </div>');
            return redirect('master-admin/service-provider');
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
    public function edit($id)
    {
        $data = User::findorFail($id);
        return view('Admin.ServiceProvider.edit',compact('data'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email',
            'phone_number' => 'required|numeric',
            'profile_pic' => 'mimes:jpeg,png,jpg,gif,svg'
        ]);

        $data = $request->all();

        $password = $request['password'];
        $data = $request->except('_token', '_method','password');
        if(!empty($request['password'])){
            $password = Hash::make($password);
            $data['password'] = $password;
        }

        if ($request->file('profile_pic')) {

            $oldimage = User::where('id', $id)->value('profile_pic');

            if (!empty($oldimage)) {

                File::delete('storage/app/public/user/' . $oldimage);
            }

            $file = $request->file('profile_pic');
            $filename = 'user-' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move('storage/app/public/user', $filename);
            $data['profile_pic'] = $filename;
        }


        $update = User::where('id',$id)->update($data);
        if($update){

            Session::flash('message','<div class="alert alert-success">Service Provider Updated Successfully.!! </div>');
            return redirect('master-admin/service-provider');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $oldimage = User::where('id', $id)->value('profile_pic');

        if (!empty($oldimage)) {

            File::delete('storage/app/public/user/' . $oldimage);
        }

        $delete = User::where('id',$id)->delete();

        if($delete){
            Session::flash('message', '<div class="alert alert-danger"><strong>Alert!</strong> Service Provider Deleted successfully. </div>');

            return redirect('master-admin/service-provider');
        }
    }

    /*
       Change user Status
   */
    public function statusChange($id){
        $users  = User::findorFail($id);

        if($users['status'] == 'active'){
            $newStatus = 'inactive';
        } else {
            $newStatus = 'active';
        }

        $update = User::where('id',$id)->update(['status'=>$newStatus]);

        if($update){
            Session::flash('message', '<div class="alert alert-success"><strong>Success!</strong> Service Provider status updated successfully. </div>');

            return redirect('master-admin/service-provider');
        }
    }
}
