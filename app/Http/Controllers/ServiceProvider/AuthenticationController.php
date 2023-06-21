<?php

namespace App\Http\Controllers\ServiceProvider;

use App\Http\Controllers\Controller;
use App\Models\User as AppUser;
use Illuminate\Http\Request;
use Auth;
use Redirect;
use Hash;
use Mail;
use Shanmuga\LaravelEntrust\Traits\LaravelEntrustUserTrait;

class AuthenticationController extends Controller
{
    use LaravelEntrustUserTrait;

    public function login()
    {
        return view('ServiceProvider.Auth.login');
    }

    public function doLogin(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $checkLogin = AppUser::leftjoin('role_user', 'role_user.user_id', '=', 'users.id')
            ->where('role_user.role_id', 3)->where('users.email', $request['email'])->where('users.social_type', null)->select('users.*')->first();

        if (empty($checkLogin)) {
            return redirect()->back()
                ->withErrors(['email' => "User not found.!"]);
        }

        $checkloginStatus = AppUser::leftjoin('role_user', 'role_user.user_id', '=', 'users.id')
            ->where('role_user.role_id', 3)->where('users.email', $request['email'])->where('users.social_type', null)->where('users.status', 'active')->select('users.*')->first();

        if (empty($checkloginStatus)) {
            return redirect()->back()
                ->withErrors(['email' => "User block by admin..!"]);
        }

        $logindetails = array(
            'email' => $request['email'],
            'password' => $request['password'],
            'user_role' => 'service'
        );


        if (Auth::attempt($logindetails)) {
            if (Auth::user()->hasRole('service') || Auth::user()->hasRole('employee')) {
                return Redirect::to('service-provider');
            }


            Auth::logout();
            return redirect()->back()
                ->withErrors(['email' => "Only Service Provider Can Login Here..!"]);

        } else {

            return redirect()->back()
                ->withErrors(['email' => 'Invalid Login Details.']);
        }

    }

    public function logout()
    {
        Auth::logout();
        return Redirect::to('service-provider/login');
    }
}
