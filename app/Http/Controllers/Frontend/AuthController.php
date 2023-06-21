<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Auth;
use Hash;
use Redirect;
use Socialite;
use Exception;
use Session;
use Mail;

class AuthController extends Controller
{
    public function doLogin(Request $request){

        $this->validate($request,[
            'email'=>'required|email',
            'password'=>'required'
        ]);

        $checkLogin = User::where('email', $request['email'])->first();
        if (empty($checkLogin)) {
            return response()->json(['ResponseCode' => 0, 'ResponseText' => 'User not found.!', 'ResponseData' => null]);
        }
        $checkloginStatus = User::where('email', $request['email'])->where('status', 'active')->first();
        if (empty($checkloginStatus)) {

            return response()->json(['ResponseCode' => 0, 'ResponseText' => 'User block by admin..!', 'ResponseData' => null]);
        }

        $logindetails = array(
            'email' => $request['email'],
            'password' => $request['password']
        );

        if (Auth::attempt($logindetails)) {
            if (Auth::user()->hasRole('user')) {
                return response()->json(['ResponseCode' => 1, 'ResponseText' => 'Login Successful', 'ResponseData' => null], 200);
            }
            Auth::logout();

            return response()->json(['ResponseCode' => 0, 'ResponseText' => 'Only User Can Login Here..!', 'ResponseData' => null]);

        } else {
            return response()->json(['ResponseCode' => 0, 'ResponseText' => 'Invalid Credentials..!', 'ResponseData' => null]);
        }
    }

    public function doRegister(Request $request){
        $this->validate($request,[
            'first_name'=>'required',
            'last_name'=>'required',
            'email'=>'required|email|unique:users',
            'password'=>'required',
        ]);

        $data = $request->all();

        $data['password'] = Hash::make($request['password']);
        $user = new User();
        $user->fill($data);
        if($user->save()){

            $clientrole = Role::where('id',2)->first();
            $user->attachRole($clientrole);

            event(new Registered($user));
            $logindetails = array(
                'email' => $request['email'],
                'password' => $request['password']
            );

            $title = 'Welcome to Reserved4You';

            $data = ['title' => $title, 'email' => $request['email']];

            try {
                Mail::send('email.welcome', $data, function ($message) use ($data) {
                    $message->from('punit.radhe@gmail.com', env('MAIL_FROM_NAME',"Reserved4you"))->subject($data['title']);
                    $message->to($data['email']);
                });
            } catch (\Swift_TransportException $e) {
                \Log::debug($e);
            }
//
            if (Auth::attempt($logindetails)) {
                if (Auth::user()->hasRole('user')) {
                    $va = [
                        'status'=>'2',
                        'data'=>'okay'
                    ];
                    return $va;
                }
            }


        }
    }

    public function logout(){
        Auth::logout();
        return Redirect::to('/');
    }

    public function redirectToGoogle(){
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback(Request $request){
        try {

            $user = Socialite::driver('google')->user();

            $finduser = User::where('social_type','google')->where('google_id', $user->id)->first();

            if($finduser){

                Auth::login($finduser);

                return redirect('/');

            }else{
                $getEmail = User::where('email',$user->email)->first();
                if(empty($getEmail)){
                    $newUser = User::create([
                        'first_name' => $user->user['given_name'],
                        'last_name' => $user->user['family_name'],
                        'email' => $user->email,
                        'google_id'=> $user->id,
                        'social_type'=> 'google',
                        'password' => encrypt('123456789'),
                        'email_verified_at'=>\Carbon\Carbon::now()->format('Y-m-d h:i:s')
                    ]);

                    $clientrole = Role::where('id',2)->first();
                    $newUser->attachRole($clientrole);
                    Auth::login($newUser);
                } else {
                    $update['first_name'] = $user->user['given_name'];
                    $update['last_name'] = $user->user['family_name'];
                    $update['email'] = $user->email;
                    $update['google_id'] = $user->id;
                    $update['social_type'] = 'google';
                    $update['email_verified_at']= \Carbon\Carbon::now()->format('Y-m-d h:i:s');


                    $userUPdate = User::where('email',$user->email)->update($update);
                    Auth::login($getEmail);
                }



                return redirect('/');
            }

        } catch (Exception $e) {
            dd($e->getMessage());
        }
    }

    public function redirectToFacebook(){

        return Socialite::driver('facebook')->redirect();
    }

    public function handleFacebookCallback(Request $request){
        try {

            $user = Socialite::driver('facebook')->user();

            $finduser = User::where('social_type','facebook')->where('facebook_id', $user->id)->first();

            if($finduser){

                Auth::login($finduser);

                return redirect('/');

            }else{
                $getEmail = User::where('email',$user->email)->first();
                $name = explode(' ',$user->name);

                if(empty($getEmail)){

                    $newUser = User::create([
                        'first_name' => $name[0],
                        'last_name' => $name[1],
                        'email' => $user->email,
                        'facebook_id'=> $user->id,
                        'social_type'=> 'facebook',
                        'password' => encrypt('123456789'),
                        'email_verified_at'=>\Carbon\Carbon::now()->format('Y-m-d h:i:s')
                    ]);

                    $clientrole = Role::where('id',2)->first();
                    $newUser->attachRole($clientrole);

                    Auth::login($newUser);
                } else {
                    $update['first_name'] = $name[0];
                    $update['last_name'] = $name[1];
                    $update['email'] = $user->email;
                    $update['facebook_id'] = $user->id;
                    $update['social_type'] = 'facebook';
                    $update['email_verified_at']= \Carbon\Carbon::now()->format('Y-m-d h:i:s');
                    $userUPdate = User::where('email',$user->email)->update($update);
                    Auth::login($getEmail);
                }


                return redirect('/');
            }

        } catch (Exception $e) {
            dd($e->getMessage());
        }
    }

    public function forgotPassword(Request $request){
        $this->validate($request,[
            'email'=>'required|email',
        ]);

        $email = $request['email'];

        $check_user = User::where('email', $email)->first();

        if (empty($check_user)) {
            return response()->json(['ResponseCode' => 0, 'ResponseText' => 'Email not registered', 'ResponseData' => null], 200);
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
                return response()->json(['ResponseCode' => 0, 'ResponseText' => 'Something went wrong', 'ResponseData' => null], 200);
            }
        }
    }
}
