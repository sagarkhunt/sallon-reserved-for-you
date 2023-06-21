<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Plans;
use App\Models\Role;
use App\Models\Service;
use App\Models\StoreCategory;
use App\Models\StoreGallery;
use App\Models\StoreProfile;
use App\Models\StoreTiming;
use App\Models\User;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Session;
use File;
use Hash;

class StoreProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = StoreProfile::get();
        return view('Admin.Store.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = [''=>'Select Service Provider'] + User::leftjoin('role_user', 'role_user.user_id', '=', 'users.id')
                ->where('role_user.role_id', 3)
                ->select('users.*', DB::raw('CONCAT(first_name," ",last_name) AS name'))
                ->pluck('name', 'id')->all();

        $plan = ['' =>'Select Plan'] + Plans::where('status','active')->pluck('plan_name','id')->all();
        $category = Category::where('main_category', null)->where('status', 'active')->pluck('name', 'id')->all();

        return view('Admin.Store.create', compact('user', 'category','plan'));
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
            'store_name' => 'required',
            'store_address' => 'required',
            'zipcode' => 'required',
            'store_district' => 'required',
        ]);
        $data = $request->all();

        
        /**
         * Create new User
         */

        if (isset($request['new_user'])) {
            if($request['store_active_actual_plan'] != '1'){
                $this->validate($request, [
                    'first_name' => 'required',
                    'last_name' => 'required',
                    'email' => 'required|email',
                    'password' => 'required',
                ]);
            } else {
                $this->validate($request, [
                    'email' => 'required|email',
                    'password' => 'required',
                ]);
            }


            $user['first_name'] = $request['first_name'];
            $user['last_name'] = $request['last_name'];
            $user['email'] = $request['email'];
            $user['phone_number'] = $request['phone_number'];
            $user['user_role'] = 'service';
            $user['password'] = Hash::make($request['password']);

            $userData = new User();
            $userData->fill($user);
            $userData->save();

            $clientrole = Role::where('id', 3)->first();
            $userData->attachRole($clientrole);
            $data['user_id'] = $userData['id'];
        }

        if ($request->file('store_profile')) {

            $file = $request->file('store_profile');
            $filename = 'Store-' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(storage_path('app/public/store/'), $filename);
            $data['store_profile'] = $filename;
        }

        if ($request->file('store_banner')) {

            $file = $request->file('store_banner');
            $filename = 'Store-' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(storage_path('app/public/store/banner/'), $filename);
            $data['store_banner'] = $filename;
        }
        $data['store_status'] = 'active';

        $data['slug'] = str_replace(' ','-',strtolower($request['store_name']));
        $data['store_active_actual_plan'] = Plans::where('id',$request['store_active_actual_plan'])->value('slug_actual_plan');
        $data['store_active_plan'] = Plans::where('id',$request['store_active_actual_plan'])->value('slug');

        $store = new StoreProfile();
        $store->fill($data);
        if ($store->save()) {

            /**
             * Store Category
             */

            $category = $request['category'];

            if(!empty($category)){
                foreach ($category as $row) {
                    $storeCat['store_id'] = $store->id;
                    $storeCat['category_id'] = $row;

                    $storeCategory = new StoreCategory();
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

                $dayData['store_id'] = $store->id;
                $dayData['day'] = $item;
                if ($start_time[$i] == '' || $start_time[$i] == null && $end_time[$i] == '' ||  $end_time[$i] == null) {
                    $dayData['is_off'] = 'on';
                } else {
                    $dayData['is_off'] = null;
                }
                $dayData['start_time'] = $start_time[$i];
                $dayData['end_time'] = $end_time[$i];

                $dayStore = new StoreTiming();
                $dayStore->fill($dayData);
                $dayStore->save();
                $i++;
            }

            /**
             * Store Gallery
             */

            $gallery = $request['store_gallery'];

            if(!empty($gallery)){
                foreach ($gallery as $item) {
                    if (!empty($item)) {
                        $extension = $item->getClientOriginalExtension();

                        $destinationpath = storage_path('app/public/store/gallery/');

                        $filename = 'Store-' . uniqid() . '-' . rand(1, 9999) . '.' . $extension;

                        $item->move($destinationpath, $filename);

                        $barImage['file'] = $filename;
                        $barImage['file_type'] = 'image';
                        $barImage['store_id'] = $store->id;

                        $product_img = new StoreGallery();
                        $product_img->fill($barImage);
                        $product_img->save();
                    }

                }
            }


            Session::flash('message', '<div class="alert alert-success">Store Profile Created Successfully.!! </div>');
            return redirect('master-admin/store-profile');
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
        $store = StoreProfile::findorFail($id);
        $data = Service::where('store_id', $id)->get();
        return view('Admin.Store.view', compact('data', 'store'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = ['Select Service Provider'] + User::leftjoin('role_user', 'role_user.user_id', '=', 'users.id')
                ->where('role_user.role_id', 3)
                ->select('users.*', DB::raw('CONCAT(first_name," ",last_name) AS name'))
                ->pluck('name', 'id')->all();


        $selectedCategory = StoreCategory::where('store_id', $id)->pluck('category_id')->all();

        $data = StoreProfile::findorFail($id);
        $category = Category::where('main_category', null)->where('category_type', $data['category_id'])->where('status', 'active')->pluck('name', 'id')->all();
        $data['plan'] = Plans::where('slug_actual_plan',$data['store_active_actual_plan'])->value('id');
        $timing = StoreTiming::where('store_id', $id)->get();
        $plan = Plans::where('status','active')->pluck('plan_name','id')->all();
        $gallery = StoreGallery::where('store_id', $id)->get();

        return view('Admin.Store.edit', compact('user', 'category', 'data', 'selectedCategory', 'timing', 'gallery','plan'));
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
            'store_name' => 'required',
//            'store_contact_number' => 'required|numeric',
//            'store_description' => 'required',
//            'store_link_id' => 'required',
            'store_address' => 'required',
            'zipcode' => 'required',
            'store_district' => 'required',
//            'cancellation_deadline' => 'required',
//            'cancellation_day' => 'required',
            'store_profile' => 'mimes:jpeg,png,jpg,gif,svg',
            'store_banner' => 'mimes:jpeg,png,jpg,gif,svg',
        ]);

        $data = $request->all();

        $category = $request['category'];
        $day = $request['day'];
        $start_time = $request['start_time'];
        $end_time = $request['end_time'];
        $gallery = $request['store_gallery'];

        $data = $request->except('_token', '_method', 'category', 'day', 'start_time', 'end_time', 'is_holiday', 'store_gallery');

        if ($request->file('store_profile')) {

            $oldimage = StoreProfile::where('id', $id)->value('store_profile');

            if (!empty($oldimage)) {

                File::delete('storage/app/public/store/' . $oldimage);
            }

            $file = $request->file('store_profile');
            $filename = 'Store-' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(storage_path('app/public/store/'), $filename);
            $data['store_profile'] = $filename;
        }

        if ($request->file('store_banner')) {

            $oldimage = StoreProfile::where('id', $id)->value('store_banner');

            if (!empty($oldimage)) {

                File::delete('storage/app/public/store/banner/' . $oldimage);
            }

            $file = $request->file('store_banner');
            $filename = 'Store-' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(storage_path('app/public/store/banner/'), $filename);
            $data['store_banner'] = $filename;
        }

        $data['slug'] = str_replace(' ','-',strtolower($request['store_name']));

        $data['store_active_actual_plan'] = Plans::where('id',$request['store_active_actual_plan'])->value('slug_actual_plan');
        $data['store_active_plan'] = Plans::where('id',$request['store_active_actual_plan'])->value('slug');


        $update = StoreProfile::where('id', $id)->update($data);
        if ($update) {

            $deleteCategory = StoreCategory::where('store_id', $id)->delete();
            foreach ($category as $row) {
                $storeCat['store_id'] = $id;
                $storeCat['category_id'] = $row;

                $storeCategory = new StoreCategory();
                $storeCategory->fill($storeCat);
                $storeCategory->save();
            }


            $i = 0;
            $deleteDay = StoreTiming::where('store_id', $id)->delete();

            foreach ($day as $item) {

                $dayData['store_id'] = $id;
                $dayData['day'] = $item;
                if ($start_time[$i] == '' || $start_time[$i] == null && $end_time[$i] == '' ||  $end_time[$i] == null) {
                    $dayData['is_off'] = 'on';
                } else {
                    $dayData['is_off'] = null;
                }
                $dayData['start_time'] = $start_time[$i];
                $dayData['end_time'] = $end_time[$i];

                $dayStore = new StoreTiming();
                $dayStore->fill($dayData);
                $dayStore->save();
                $i++;
            }
            if(!empty($gallery)){
                foreach ($gallery as $item) {
                    if (!empty($item)) {
                        $extension = $item->getClientOriginalExtension();

                        $destinationpath = storage_path('app/public/store/gallery/');

                        $filename = 'Store-' . uniqid() . '-' . rand(1, 9999) . '.' . $extension;

                        $item->move($destinationpath, $filename);

                        $barImage['file'] = $filename;
                        $barImage['file_type'] = 'image';
                        $barImage['store_id'] = $id;

                        $product_img = new StoreGallery();
                        $product_img->fill($barImage);
                        $product_img->save();
                    }

                }
            }


            Session::flash('message', '<div class="alert alert-success">Store Profile Updated Successfully.!! </div>');
            return redirect('master-admin/store-profile');
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
        $oldimage = StoreProfile::where('id', $id)->value('store_profile');

        if (!empty($oldimage)) {

            File::delete('storage/app/public/store/' . $oldimage);
        }

        $oldimage = StoreProfile::where('id', $id)->value('store_banner');

        if (!empty($oldimage)) {

            File::delete('storage/app/public/store/banner/' . $oldimage);
        }

        $delete = StoreProfile::where('id', $id)->delete();
        $deleteCategory = StoreCategory::where('store_id', $id)->delete();

        if ($delete) {
            Session::flash('message', '<div class="alert alert-danger"><strong>Alert!</strong> Store Profile Deleted successfully. </div>');

            return redirect('master-admin/store-profile');
        }
    }

    public function changeCategory(Request $request)
    {
        $category_id = $request['category_id'];

        $data = Category::where('main_category', null)->where('category_type', $category_id)->where('status', 'active')->get();

        return ['status' => '1', 'data' => $data];

    }
}
