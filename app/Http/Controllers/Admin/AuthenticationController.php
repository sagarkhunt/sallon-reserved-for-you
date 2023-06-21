<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User as AppUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Auth;

class AuthenticationController extends Controller
{
    public function login(){
        return view('Admin.Auth.login');
    }

    public function doLogin(Request $request){
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $checkLogin = AppUser::where('email', $request['email'])->first();
        if (empty($checkLogin)) {
            return redirect()->back()
                ->withErrors(['email' => "User not found.!"]);
        }

        $checkloginStatus = AppUser::where('email', $request['email'])->where('status', 'active')->first();

        if (empty($checkloginStatus)) {
            return redirect()->back()
                ->withErrors(['email' => "User block by admin..!"]);
        }

        $logindetails = array(
            'email' => $request['email'],
            'password' => $request['password']
        );

        if (Auth::attempt($logindetails)) {
            if (Auth::user()->hasRole('admin')) {
                return Redirect::to('master-admin');
            }

            Auth::logout();
            return redirect()->back()
                ->withErrors(['email' => "Only Admin Can Login Here..!"]);

        } else {
            return redirect()->back()
                ->withErrors(['email' => 'Invalid Login Details.']);
        }

    }

    public function logout(){
        Auth::logout();
        return Redirect::to('master-admin/login');
    }
}
