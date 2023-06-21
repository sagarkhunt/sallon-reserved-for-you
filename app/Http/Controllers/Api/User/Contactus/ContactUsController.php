<?php

namespace App\Http\Controllers\Api\User\Contactus;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Contactus;
use URL;
use File;
use Validator;
use Mail;

class ContactUsController extends Controller
{
    public function store(Request $request)
    {
        $rule = [
            'name' => 'required',
            'email' =>'required'
        ];

        $message = [
            'name.required' => 'Name is required',            
            'email.required' => 'email is required',
        ];

        $validate = Validator::make($request->all(),$rule,$message);
        if ($validate->fails()) {
            return response()->json(['ResponseCode' => 0, 'ResponseText' => 'Validation Fails', 'ResponseData' => $validate->errors()->all()], 422);
        }
        try {
            $data = request()->all();
            
            $contactUs = new Contactus();
            $contactUs->user_id = $data['user']['user_id'];
            $contactUs->name = $data['name'];
            $contactUs->email = $data['email'];
            $contactUs->message = $data['message'];

            if ($contactUs->save()) {
                $title = 'ContactUs';
                $name = $contactUs['name'];
                $email = $contactUs['email'];
                $message = $contactUs['message'];
                $data = ['titles' => $title, 'emails' => $email, 'names' => $name, 'messages' => $message];
                
                Mail::send('email.contact_us', $data, function ($message) use ($data) {
                    $message->from($data['emails'], env('MAIL_FROM_NAME',"Reserved4you"))->subject($data['titles']);
                    $message->to('punit.radhe@gmail.com');
                });

                return response()->json(['ResponseCode' => 1, 'ResponseText' => 'Email Sent Success', 'ResponseData' => null], 200);            
            }
        }catch (\Swift_TransportException $e) {
            \Log::debug($e);
            return response()->json(['ResponseCode' => 0, 'ResponseText' => 'Something went wrong', 'ResponseData' => null], 400);
        }
    }
}
