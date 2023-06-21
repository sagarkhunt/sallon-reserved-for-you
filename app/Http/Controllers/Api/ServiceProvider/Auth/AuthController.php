<?php

namespace App\Http\Controllers\Api\ServiceProvider\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use App\Models\ApiSession;
use App\Models\StoreProfile;
use Illuminate\Support\Facades\Hash;
use Validator;
use URL;
use Mail;
use File;
use Exception;


class AuthController extends Controller
{   
    //login for provider
    public function login(Request $request)
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

        try {
            $user = User::where('email', $request['email'])->where('status','active')->first();
            
            if (!empty($user)) {                
                $uid =  $user->id;
                $role= \DB::table('role_user')
                    ->where('role_user.user_id','=',$uid)
                    ->join('roles', 'role_user.role_id', '=', 'roles.id')
                    ->select('roles.display_name as name')
                    ->first();                
                if ($role->name=='Service Provider') {
                    if (Hash::check($request['password'], $user->password)) {
                        $token = \BaseFunction::setSessionData($user->id);
    
                        // if (file_exists(storage_path('app/public/user/' . $user['profile_pic'])) && $user['profile_pic'] != '') {
                        //     $user['profile_pic'] = URL::to('storage/app/public/user/' . $user['profile_pic']);
                        // } else {
                        //     $user['profile_pic'] = URL::to('storage/app/public/default/default_user.jpg');
                        // }
    
                        $data = ['token' => $token, 'user' => $user];
                        
                        // unset($user['roles']);
                        return response()->json(['ResponseCode' => 1, 'ResponseText' => 'Login Successful', 'ResponseData' => $data], 200);
    
                    } else {
                        return response()->json(['ResponseCode' => 0, 'ResponseText' => 'Invalid Password', 'ResponseData' => null], 400);
                    }    
                } else{
                    return response()->json(['ResponseCode' => 0, 'ResponseText' => 'Wrong email. Contact to your admin', 'ResponseData' => null], 400);
                }                

            } else {
                return response()->json(['ResponseCode' => 0, 'ResponseText' => 'Email not registered', 'ResponseData' => null], 400);
            }
        }  catch (\Swift_TransportException $e) {
            \Log::debug($e);
            return response()->json(['ResponseCode' => 0, 'ResponseText' => 'Something went wrong', 'ResponseData' => null], 400);
        }
    }

    //forgot password
    public function forgotPassword(Request $request)
    {
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
        try {
            $email = $request['email'];

            $check_user = User::where('email', $email)->first();
            
            if (empty($check_user)) {
                return response()->json(['ResponseCode' => 0, 'ResponseText' => 'Email not registered', 'ResponseData' => null], 422);
            } else {
                $password = \BaseFunction::random_code(8);                
                $newpassword['password'] = Hash::make($password);

                $update = User::where('email', $email)->update($newpassword);

                $title = 'Password reset';
                $name = $check_user['first_name'].' '.$check_user['last_name'];
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
        } catch (\Swift_TransportException $e) {
            throw new \Exception($e->getMessage());
        }
    }

    //logout
    public function logout(Request $request)
    {
        $user = $request['user'];
        $user_id = $user['user_id'];
        ApiSession::where('user_id', $user_id)->delete();
        return response()->json(['ResponseCode' => 1, 'ResponseText' => 'Successfully logout', 'ResponseData' => null]);
    }

    /**
     * Provider all store 
     */
    public function providerAllStore(Request $request)
    {
        try {
            $data = request()->all();            
            $allStore = StoreProfile::where('user_id',$data['user']['user_id'])->get(['id','store_name','store_active_plan']);
            if ($allStore->count() <= 0) {
                return response()->json(['ResponseCode' => 0, 'ResponseText' => 'No store found', 'ResponseData' => null], 200);    
            }
            return response()->json(['ResponseCode' => 1, 'ResponseText' => 'Store list', 'ResponseData' => $allStore], 200);    
        } catch (\Swift_TransportException $e) {
            \Log::debug($e);
            return response()->json(['ResponseCode' => 0, 'ResponseText' => 'Something went wrong', 'ResponseData' => null], 400);
        }
    }
}
