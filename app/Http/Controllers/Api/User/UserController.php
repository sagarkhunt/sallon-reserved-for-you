<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use URL;
use File;
use Validator;

class UserController extends Controller
{
    public function index(Request $request){
        $user = $request['user'];
        $user_id = $user['user_id'];
        $user_data = User::where('id', $user_id)->first();

        if (file_exists(storage_path('app/public/user/' . $user_data['profile_pic'])) && $user_data['profile_pic'] != '') {
            $user_data['profile_pic'] = URL::to('storage/app/public/user/' . $user_data['profile_pic']);
        } else {
            $user_data['profile_pic'] = URL::to('storage/app/public/default/default_user.jpg');
        }

        $data['user'] = $user_data;

        return response()->json(['ResponseCode' => 1, 'ResponseText' => 'Success.', 'ResponseData' => $data], 200);
    }


    public function updateProfile(Request $request)
    {
        $user = $request['user'];
        $user_id = $user['user_id'];
        $user_data = User::where('id', $user_id)->first();
        $rule = [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:users,email,' . $user_id,
            'profile_pic'=>'mimes:jpeg,bmp,png,gif'
        ];
        $message = [
            'first_name.required' => 'First Name is required',
            'last_name.required' => 'Last Name is required',
            'email.required' => 'email is required',
        ];
        $validate = Validator::make($request->all(), $rule, $message);

        if ($validate->fails()) {
            return response()->json(['ResponseCode' => 0, 'ResponseText' => 'Validation Fails', 'ResponseData' => $validate->errors()->all()], 422);
        }


        $data['first_name'] = $request['first_name'];
        $data['last_name'] = $request['last_name'];
        $data['email'] = $request['email'];
        $data['phone_number'] = $request['phone_number'];
        $data['city'] = $request['city'];
        $data['country'] = $request['country'];
        $data['zipcode'] = $request['zipcode'];


        if ($request->file('profile_pic')) {

            $oldimage = User::where('id', $user_id)->value('profile_pic');
            if (!empty($oldimage)) {
                File::delete('storage/app/public/user/' . $oldimage);
            }

            $file = $request->file('profile_pic');
            $filename = 'user-' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move('storage/app/public/user', $filename);
            $data['profile_pic'] = $filename;
        }


        $update = User::where('id', $user_id)->update($data);
        if ($update) {
            $data = User::where('id', $user_id)->first();

            if (file_exists(storage_path('app/public/user/' . $data['profile_pic'])) && $data['profile_pic'] != '') {
                $data['profile_pic'] = URL::to('storage/app/public/user/' . $data['profile_pic']);
            } else {
                $data['profile_pic'] = URL::to('storage/app/public/default/default_doctor.jpg');
            }

            return response()->json(['ResponseCode' => 1, 'ResponseText' => 'Update Success.', 'ResponseData' => $data], 200);
        }

    }
}
