<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use App\Models\ApiSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Validator;
use URL;
use Mail;
use File;

class AuthenticationController extends Controller
{
    /**
     * Authentication APi
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function authentication(Request $request)
    {
        $rule = [
            'email' => 'required',
            'password' => 'required'
        ];

        $message = [
            'email.required' => 'email is required',
            'password.required' => 'password is required'
        ];

        $validate = Validator::make($request->all(), $rule, $message);

        if ($validate->fails()) {
            return response()->json(['ResponseCode' => 0, 'ResponseText' => 'Validation Fails', 'ResponseData' => $validate->errors()->all()], 422);
        }

        $user = User::where('email', $request['email'])->where('status','active')->first();

        if (!empty($user)) {

            if (Hash::check($request['password'], $user->password)) {
                $token = \BaseFunction::setSessionData($user->id);

                if (file_exists(storage_path('app/public/user/' . $user['profile_pic'])) && $user['profile_pic'] != '') {
                    $user['profile_pic'] = URL::to('storage/app/public/user/' . $user['profile_pic']);
                } else {
                    $user['profile_pic'] = URL::to('storage/app/public/default/default_user.jpg');
                }

                $data = ['token' => $token, 'user' => $user];

                unset($user['roles']);
                return response()->json(['ResponseCode' => 1, 'ResponseText' => 'Login Successful', 'ResponseData' => $data], 200);

            } else {
                return response()->json(['ResponseCode' => 0, 'ResponseText' => 'Invalid Password', 'ResponseData' => null], 400);
            }

        } else {
            return response()->json(['ResponseCode' => 0, 'ResponseText' => 'Email not registered', 'ResponseData' => null], 400);
        }

    }

    /**
     * Register User APi
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        $rule = [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8'
        ];
        $message = [
            'first_name.required' => 'First Name is required',
            'last_name.required' => 'Last Name is required',
            'email.required' => 'email/username is required',
            'password.required' => 'password is required',
            'password.min' => 'password Must be 8 Characters',
        ];
        $validate = Validator::make($request->all(), $rule, $message);

        if ($validate->fails()) {
            return response()->json(['ResponseCode' => 0, 'ResponseText' => 'Validation Fails', 'ResponseData' => $validate->errors()->all()], 422);
        }

        $data['first_name'] = $request['first_name'];
        $data['last_name'] = $request['last_name'];
        $data['email'] = $request['email'];
        $data['password'] = Hash::make($request['password']);
        $data['device_token'] = $request['device_token'];
        $data['device_id'] = $request['device_id'];
        $data['device_type'] = $request['device_type'];

        $user = new User();
        $user->fill($data);

        if($user->save()){
            $clientrole = Role::where('id', 2)->first();
            $user->attachRole($clientrole);


            if (file_exists(storage_path('app/public/user/' . $user['profile_pic'])) && $user['profile_pic'] != '') {
                $user['profile_pic'] = URL::to('storage/app/public/user/' . $user['profile_pic']);
            } else {
                $user['profile_pic'] = URL::to('storage/app/public/default/default_user.jpg');
            }
            $token = \BaseFunction::setSessionData($user->id);

            $datas = ['token' => $token, 'user' => $user];
            return response()->json(['ResponseCode' => 1, 'ResponseText' => "Registration Successfully", 'data' => $datas], 200);
        }
    }

    /**
     * Forgot Password
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function forgotPassword(Request $request){
        $rule = [
            'email' => 'required',
        ];
        $message = [
            'email.required' => 'email is required',
        ];
        $validate = Validator::make($request->all(), $rule, $message);

        if ($validate->fails()) {
            return response()->json(['ResponseCode' => 0, 'ResponseText' => 'Validation Fails', 'ResponseData' => $validate->errors()->all()], 422);
        }
        $email = $request['email'];

        $check_user = User::where('email', $email)->first();
        if (empty($check_user)) {
            return response()->json(['ResponseCode' => 0, 'ResponseText' => 'Email not registered', 'ResponseData' => null], 422);
        } else {
            $password = BaseFunction::random_code(8);
            $newpassword['password'] = Hash::make($password);

            $update = User::where('email', $email)->update($newpassword);

            $title = 'Password reset';
            $name = $check_user['first_name'];
            $data = ['title' => $title, 'email' => $email, 'name' => $name, 'password' => $password];

            try {
                Mail::send('email.reset', $data, function ($message) use ($data) {
                    $message->from('noreply@reserved4you.com', "Reserved4you")->subject($data['title']);
                    $message->to($data['email']);
                });

                return response()->json(['ResponseCode' => 1, 'ResponseText' => 'New Password Sent to Email Address', 'ResponseData' => null], 200);
            } catch (\Swift_TransportException $e) {
                \Log::debug($e);
                return response()->json(['ResponseCode' => 0, 'ResponseText' => 'Something went wrong', 'ResponseData' => null], 400);
            }
        }

    }


    /**
     * Logout API
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        $user = $request['user'];
        $user_id = $user['user_id'];
        ApiSession::where('user_id', $user_id)->delete();
        return response()->json(['ResponseCode' => 1, 'ResponseText' => 'Successfully logout', 'ResponseData' => null]);
    }

}
