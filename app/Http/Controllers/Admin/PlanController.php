<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Plans;
use Illuminate\Http\Request;
use Session;


class PlanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Plans::get();
        return view('Admin.Plans.index',compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('Admin.Plans.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            'plan_name'=>'required',
            'plan_description'=>'required',
            'price'=>'required',
            'recruitment_fee'=>'required',
            'tax_fee'=>'required',
            'is_management_tool'=>'required',
            'is_profile_design'=>'required',
            'is_booking_system'=>'required',
            'is_ads_newsletter'=>'required',
            'is_social_media_platforms'=>'required',
            'plan_actual_name'=>'required',
        ]);

        $data = $request->all();
        $data['slug'] = str_replace(' ','-',strtolower($request['plan_actual_name']));
        $data['slug_actual_plan'] = str_replace(' ','-',strtolower($request['plan_name']));
        $plan = new Plans();
        $plan->fill($data);
        if($plan->save()){
            Session::flash('message','<div class="alert alert-success">Plan Created Successfully.!! </div>');
            return redirect('master-admin/plans');
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
        $data = Plans::findorFail($id);
        return view('Admin.Plans.edit',compact('data'));
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
        $this->validate($request,[
            'plan_name'=>'required',
            'plan_description'=>'required',
            'price'=>'required',
            'recruitment_fee'=>'required',
            'tax_fee'=>'required',
            'is_management_tool'=>'required',
            'is_profile_design'=>'required',
            'is_booking_system'=>'required',
            'is_ads_newsletter'=>'required',
            'is_social_media_platforms'=>'required',
            'plan_actual_name'=>'required',
            'status'=>'required',
        ]);

        $data = $request->all();
        $data = $request->except('_token', '_method');
        $data['slug'] = str_replace(' ','-',strtolower($request['plan_actual_name']));
        $data['slug_actual_plan'] = str_replace(' ','-',strtolower($request['plan_name']));
        $update = Plans::where('id',$id)->update($data);
        if($update){
            Session::flash('message','<div class="alert alert-success">Plan Updated Successfully.!! </div>');
            return redirect('master-admin/plans');
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
        $delete = Plans::where('id',$id)->delete();
        if($delete){
            Session::flash('message', '<div class="alert alert-danger"><strong>Alert!</strong> Plan Deleted successfully. </div>');

            return redirect('master-admin/plans');
        }
    }

    public function statusChange($id){
        $users  = Plans::findorFail($id);

        if($users['status'] == 'active'){
            $newStatus = 'inactive';
        } else {
            $newStatus = 'active';
        }

        $update = Plans::where('id',$id)->update(['status'=>$newStatus]);

        if($update){
            Session::flash('message', '<div class="alert alert-success"><strong>Success!</strong> Plan status updated successfully. </div>');

            return redirect('master-admin/plans');
        }
    }
}
