<?php

namespace App\Http\Middleware;

use App\Models\ApiSession;
use Closure;

class ApiAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $token=$request->header('Authorization');        
        $user_data= \BaseFunction::checkApisSession($token);
        if(empty($token) || $token == 'null'){
            $request['user']=$user_data;
            return $next($request);
            // return response()->json(['ResponseCode'=>9,'ResponseText'=>'Authorization Token is required','ResponseData'=>null],499);
        }
        $apisession=ApiSession::where('session_id',$token)->first();
        
        if(empty($apisession)){
            return response()->json(['ResponseCode'=>9,'ResponseText'=>'Invalid User','ResponseData'=>null],498);
        }
        $user_data= \BaseFunction::checkApisSession($token);
        if(empty($user_data))
        {
            return response()->json(['ResponseCode'=>'9','ResponseText'=>'Invalid user','ResponseData'=>null],498);
        }

        $request['user']=$user_data;
        return $next($request);
    }
}
