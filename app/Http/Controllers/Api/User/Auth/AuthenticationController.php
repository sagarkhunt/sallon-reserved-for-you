<?php

namespace App\Http\Controllers\Api\User\Auth;

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

                // if (file_exists(storage_path('app/public/user/' . $user['profile_pic'])) && $user['profile_pic'] != '') {
                //     $user['profile_pic'] = URL::to('storage/app/public/user/' . $user['profile_pic']);
                // } else {
                //     $user['profile_pic'] = URL::to('storage/app/public/default/default_user.jpg');
                // }

                $data = ['token' => $token, 'user' => $user];

                unset($user['roles']);
                return response()->json(['ResponseCode' => 1, 'ResponseText' => 'Login Successful', 'ResponseData' => $data], 200);

            } else {
                return response()->json(['ResponseCode' => 0, 'ResponseText' => 'Invalid Password', 'ResponseData' => null], 200);
            }

        } else {
            return response()->json(['ResponseCode' => 0, 'ResponseText' => 'Email not registered', 'ResponseData' => null], 200);
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
            'password' => ['required','min:8','regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\x]).*$/']
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
            $createdUser = User::where('email', $user['email'])->where('status','active')->first();
            $datas = ['token' => $token, 'user' => $createdUser];
            return response()->json(['ResponseCode' => 1, 'ResponseText' => "Registration Successfully", 'ResponseData' => $datas], 200);
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
            $password = \BaseFunction::random_code(8);
            $newpassword['password'] = Hash::make($password);

            $update = User::where('email', $email)->update($newpassword);

            $title = 'Password reset';
            $name = $check_user['first_name'];
            $data = ['title' => $title, 'email' => $email, 'name' => $name, 'password' => $password];

            try {
                Mail::send('email.pass_reset', $data, function ($message) use ($data) {
                    $message->from('punit.radhe@gmail.com', env('MAIL_FROM_NAME',"Reserved4you"))->subject($data['title']);
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

    //user change password
    public function changePassword(Request $request)
    {
        
        $rule = [
            'current_password' => 'required',
            // 'new_password' => 'required|min:8',                        
            'new_password' => ['required','min:8','regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\x]).*$/'],                    
            'confirm_password' => 'required|same:new_password'
        ];
        $message = [
            'current_password.required' => 'current_password is required',
            'new_password.required' => 'password is required',
            'new_password.min' => 'password Must be 8 Characters',
            'confirm_password.required' => 'confirm password is required',           

        ];
        $validate = Validator::make($request->all(), $rule, $message);

        if ($validate->fails()) {
            return response()->json(['ResponseCode' => 0, 'ResponseText' => 'Validation Fails', 'ResponseData' => $validate->errors()->all()], 422);
        }
        try {
            $data  = request()->all();
            
            $newPassword = Hash::make($data['new_password']);
            $userId = $data['user']['user_id'];
            $updatePassword = User::where('id',$userId)->update(['password' => $newPassword]);
            if ($updatePassword) {
                return response()->json(['ResponseCode' => 1, 'ResponseText' => 'Password Change Successfull.', 'ResponseData' => null], 200);
            }
            return response()->json(['ResponseCode' => 0, 'ResponseText' => 'Something went wrong', 'ResponseData' => null], 400);
        } catch (\Swift_TransportException $e) {
            \Log::debug($e);
            return response()->json(['ResponseCode' => 0, 'ResponseText' => 'Something went wrong', 'ResponseData' => null], 400);
        }
    }

    //user social login
     /*social login*/
     public function socialLogin(Request $request)
     {
         $rule = [
            'user_name' => 'required',
            'device_id' => 'required',
            'device_type' => 'required',
            'social_id' => 'required',
            'social_type' => 'required',
        ];
        $message = [
            'user_name.required' => 'email is required',
            'device_id.required' => 'device_id is required',
            'device_type.required' => 'device_type is required',
            'social_id.required' => 'social_id is required',
            'social_type.required' => 'social_type is required',

        ];
        $validate = Validator::make($request->all(), $rule, $message);

        if ($validate->fails()) {
            return response()->json(['ResponseCode' => 0, 'ResponseText' => 'Validation Fails', 'ResponseData' => $validate->errors()->all()], 422);
        }
         try
         {
             $data = request()->all();             
             $user = User::where('email', $data['email'])
                                 ->orWhere('google_id' , $data['social_id'])
                                 ->orWhere('facebook_id' , $data['social_id'])
                                 ->first();
             
             $names = explode(" ", $data['user_name']);
             
             if ($user == null) {
                 $user = new User();
                 $user->first_name   = isset($names[0]) == false ? '' : $names[0];
                 $user->last_name    = isset($names[1]) == false ? '' : $names[1];
                 $user->email        = isset($data['email']) == false ? null : $data['email'];
                 $user->profile_pic  = $data['profile_pic'];                 
                 $user->social_type  = $data['social_type'];                                                 
                 $user->device_id    = $data['device_id'];                 
                 $user->device_type  = $data['device_type'];                 
                 $user->device_token = $data['device_token']; 

                if($data['social_type'] == 'facebook'){
                    $user->facebook_id = $data['social_id'];
                }elseif($data['social_type'] == 'apple'){
                    $user->apple_id = $data['social_id'];
                }else{
                    $user->google_id = $data['social_id'];
                }                                 
                $user->save();
 
                if ($user) {
                    /*assign role*/
                $userRole = Role::where('id', 2)->first();
                $user->attachRole($userRole);                    
                $token = \BaseFunction::setSessionData($user->id);
    
                $datas = ['token' => $token, 'user' => $user];
                return response()->json(['ResponseCode' => 1, 'ResponseText' => "Successfully", 'ResponseData' => $datas], 200);
                }                 
                 
             }  else {                  
                $userdata = [];
                $userdata['first_name']  = isset($names[0]) == false ? '' : $names[0];
                $userdata['last_name']   = isset($names[1]) == false ? '' : $names[1];
                $userdata['email']       = isset($data['email']) == false ? null : $data['email'];
                $userdata['profile_pic'] = $data['profile_pic'];                
                $userdata['social_type'] = $data['social_type'];
                // $userdata['social_id']   = $data['social_id'];
                $userdata['device_id']   = $data['device_id'];                 
                $userdata['device_type'] = $data['device_type'];                 
                $userdata['device_token']= $data['device_token']; 
                 
                if($data['social_type'] == 'facebook'){
                    $userdata['facebook_id'] = $data['social_id'];
                }elseif ($data['social_type'] == 'apple') {
                    $userdata['apple_id'] = $data['social_id'];
                }else{
                    $userdata['google_id'] = $data['social_id'];
                }
                $updateUser = User::where('id',$user->id)->update($userdata);                 
 
                $user =User::where('id',$user->id)->first();
                
                $token = \BaseFunction::setSessionData($user->id);                
                $datas = ['token' => $token, 'user' => $user];
                return response()->json(['ResponseCode' => 1, 'ResponseText' => "Successfully", 'ResponseData' => $datas], 200);
             }
 
         } catch (\Swift_TransportException $e) {
            \Log::debug($e);
            return response()->json(['ResponseCode' => 0, 'ResponseText' => 'Something went wrong', 'ResponseData' => null], 400);
        }
     }
 
    /**
     * guest user
     */
    public function guestUser(Request $request)
    {
        // $rule = [
        //     'first_name' => 'required',
        //     'last_name' => 'required',
        //     'email' => 'required',
        //     'phone_number' => 'required',            
        // ];
        // $message = [
        //     'first_nmae.required' => 'first name is required',
        //     'last_name.required' => 'last name is required',
        //     'email.required' => 'email is required',
        //     'phone_number.required' => 'phone number is required',

        // ];
        // $validate = Validator::make($request->all(), $rule, $message);

        // if ($validate->fails()) {
        //     return response()->json(['ResponseCode' => 0, 'ResponseText' => 'Validation Fails', 'ResponseData' => $validate->errors()->all()], 422);
        // }
        try {
            $data = request()->all();   
            
            $checkGuestUser = User::where('device_id',$data['device_id'])->where('device_token',$data['device_token'])->first();
            
            if (empty($checkGuestUser)) {
                $user = new User();
                $user->device_id = $data['device_id'];
                $user->device_token = $data['device_token'];
                $user->device_token = $data['device_token'];
                $user->save();
                $datas = ['token' => false,'user' => NULL];
                return response()->json(['ResponseCode' => 1, 'ResponseText' => "Registration Successfully", 'ResponseData' => $datas], 200);
            }else{  
                if (isset($data['email']) && isset($data['first_name']) && isset($data['last_name']) && isset($data['phone_number'])) {
                    $checkEmail = User::where('email',$data['email'])->first();
                    if(empty($checkEmail['email'])){
                        $checkGuestUserUpdate = User::updateOrCreate(
                            [
                                'id' =>$checkGuestUser->id
                            ],
                            [
                                'first_name' => $data['first_name'],
                                'last_name' => $data['last_name'],
                                'email' => $data['email'],
                                'phone_number' => $data['phone_number'],
                                'user_type' => $data['user_type']
                            ]);                    
                        if($checkGuestUserUpdate){
                            /**assign roles */
                            $userRole = Role::where('id', 2)->first();
                            $checkGuestUserUpdate->attachRole($userRole);
                
                            $token = \BaseFunction::setSessionData($checkGuestUserUpdate->id);
                            $createdUser = User::where('email', $checkGuestUserUpdate['email'])->where('status','active')->first();
                            $datas = ['token' => $token, 'user' => $createdUser];
                            return response()->json(['ResponseCode' => 1, 'ResponseText' => "Registration Successfully", 'ResponseData' => $datas], 200);
                        }
                    }else{                        
                        $token = \BaseFunction::setSessionData($checkEmail->id);
                        $data = ['token' => $token, 'user' => $checkEmail];            
                        return response()->json(['ResponseCode' => 1, 'ResponseText' => 'Login Successful', 'ResponseData' => $data], 200);            
                    }
    
                }else{  
                    if(empty($checkGuestUser['email'])){
                        $token = \BaseFunction::setSessionData($checkGuestUser->id);
                        $data = ['token' => false, 'user' => $checkGuestUser];            
                        return response()->json(['ResponseCode' => 1, 'ResponseText' => 'Login Successful', 'ResponseData' => $data], 200);            
                    }                     
                        $token = \BaseFunction::setSessionData($checkGuestUser->id);
                        $data = ['token' => $token, 'user' => $checkGuestUser];            
                        return response()->json(['ResponseCode' => 1, 'ResponseText' => 'Login Successful', 'ResponseData' => $data], 200);            
                }
            }
        } catch (\Swift_TransportException $e) {
            \Log::debug($e);
            return response()->json(['ResponseCode' => 0, 'ResponseText' => 'Something went wrong', 'ResponseData' => null], 400);
        }
    }
}
