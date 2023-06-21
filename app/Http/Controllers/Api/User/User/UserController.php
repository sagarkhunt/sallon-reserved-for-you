<?php

namespace App\Http\Controllers\Api\User\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserAddress;
use App\Models\StoreRatingReview;
use URL;
use File;
use Validator;

class UserController extends Controller
{
    public function index(Request $request){
        $user = $request['user'];
        $user_id = $user['user_id'];
        $user_data = User::where('id', $user_id)->first();

        // if (file_exists(storage_path('app/public/user/' . $user_data['profile_pic'])) && $user_data['profile_pic'] != '') {
        //     $user_data['profile_pic'] = URL::to('storage/app/public/user/' . $user_data['profile_pic']);
        // } else {
        //     $user_data['profile_pic'] = URL::to('storage/app/public/default/default_user.jpg');
        // }

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
        $data['address'] = $request['address'];
        $data['city'] = $request['city'];
        $data['state'] = $request['state'];
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
    //serives
    public function selectService()
    {
        $allService=  [
            
            '1' => 'Gastronomy',
            '2' => 'Cosmetics',
            '3' => 'Health',
            '4' => 'Law and Advice',
        ];
        return response()->json(['ResponseCode' => 1, 'ResponseText' => 'Update Success.', 'ResponseData' => $allService], 200);
    }

    /**
     * User address list ,store ,update
     */
    public function list()
    {
        try {
            $data = request()->all();        
            $userId = $data['user']['user_id'];
            $addressList = UserAddress::where('user_id',$userId)->get();
            if ($addressList->count() > 0) {
                return response()->json(['ResponseCode' => 1, 'ResponseText' => 'Success', 'ResponseData' => $addressList], 200);    
            }
            return response()->json(['ResponseCode' => 0, 'ResponseText' => 'No address found', 'ResponseData' => null], 400);
        } catch (\Swift_TransportException $e) {
            \Log::debug($e);
            return response()->json(['ResponseCode' => 0, 'ResponseText' => 'Something went wrong', 'ResponseData' => null], 400);
        }
    }

    /**
     * store address
     */
    public function store(Request $request)
    {
        
        $rule = [
            'address' =>'required',
            'type' =>'required',
            'latitude' =>'required',
            'longitude' =>'required',
        ];

        $message = [
            'address.required' => 'Address is required',
            'type.required' => 'Type is required',
            'latitude.required' => 'Latitude is required',
            'longitude.required' => 'Longitude is required',
        ];

        $validate = Validator::make($request->all(), $rule, $message);

        if($validate->fails()){
            return response()->json(['ResponseCode' => 0, 'ResponseText' => 'Validation Fails', 'ResponseData' => $validate->errors()->all()], 422);
        }
        try {
            $data = request()->all();
            
            $userId = $data['user']['user_id'];
            // $storeAdd = UserAddress::updateOrCreate([
            //                 'user_id' => $userId,
            //                 'type' => $data['type']
            //             ],
            //             [
            //                 'address' => $data['address'],
            //                 'type' => $data['type'],
            //                 'location' => $data['location'],
            //                 'latitude' => $data['latitude'],
            //                 'longitude' => $data['longitude'],
            //             ]);
            $storeAdd = new UserAddress();
            $storeAdd->user_id = $userId;
            $storeAdd->type = $data['type'];
            $storeAdd->address = $data['address'];
            $storeAdd->location = $data['location'];
            $storeAdd->latitude = $data['latitude'];
            $storeAdd->longitude = $data['longitude'];
            $storeAdd->save();
            return response()->json(['ResponseCode' => 1, 'ResponseText' =>'Address Add Successful', 'ResponseData' => $storeAdd], 200);
        } catch (\Swift_TransportException $e) {
            \Log::debug($e);
            return response()->json(['ResponseCode' => 0, 'ResponseText' => 'Something went wrong', 'ResponseData' => null], 400);
        }
    }

    /**
     * update address
     */

     public function update(Request $request)
     {
        $rule = [
            'address' =>'required',
            'type' =>'required',
            'latitude' =>'required',
            'longitude' =>'required',
        ];

        $message = [
            'address.required' => 'Address is required',
            'type.required' => 'Type is required',
            'latitude.required' => 'Latitude is required',
            'longitude.required' => 'Longitude is required',
        ];

        $validate = Validator::make($request->all(), $rule, $message);

        if($validate->fails()){
            return response()->json(['ResponseCode' => 0, 'ResponseText' => 'Validation Fails', 'ResponseData' => $validate->errors()->all()], 422);
        }
        try {
            $data = request()->all();            
            $userId = $data['user']['user_id'];
            $updateAdd = UserAddress::where('id' , $data['address_id'])->where('user_id',$userId)->update(
                        [
                            'address' => $data['address'],
                            'type' => $data['type'],
                            'location' => $data['location'],
                            'latitude' => $data['latitude'],
                            'longitude' => $data['longitude'],
                        ]);
            return response()->json(['ResponseCode' => 1, 'ResponseText' =>'Address update Successful', 'ResponseData' => $updateAdd], 200);
        } catch (\Swift_TransportException $e) {
            \Log::debug($e);
            return response()->json(['ResponseCode' => 0, 'ResponseText' => 'Something went wrong', 'ResponseData' => null], 400);
        }  
     }

     /**
      * destroy
      */
    public function destroy(Request $request)
    {
        try {
            $data = request()->all();
            $userId = $data['user']['user_id'];
            $deleteAdd = UserAddress::where('id',$data['add_id'])->where('user_id',$userId)->delete();
            return response()->json(['ResponseCode' => 1, 'ReponseText' => 'Address Delete Successfully.','ResponseData'=> null],200);
        } catch (\Swift_TransportException $e) {
            \Log::debug($e);
            return response()->json(['ResponseCode' => 0, 'ResponseText' => 'Something went wrong', 'ResponseData' => null], 400);
        }
    }

    /**
     * user store rrating
     */
    public function userRating(Request $request)
    {
        try {
            $data = request()->all();
            //calculate for avg rating
            $total = $data['service_rate'] + $data['ambiente'] + $data['preie_leistungs_rate'] + $data['wartezeit'] + $data['atmosphare'];
            $avgRating = $total / 5;
            //store rating 
            $userRating = new StoreRatingReview;
            $userRating->user_id              = $data['user']['user_id'];            
            $userRating->store_id             = $data['store_id'];            
            $userRating->service_rate         = $data['service_rate'];            
            $userRating->ambiente             = $data['ambiente'];            
            $userRating->preie_leistungs_rate = $data['preie_leistungs_rate'];            
            $userRating->wartezeit            = $data['wartezeit'];            
            $userRating->atmosphare           = $data['atmosphare'];         
            $userRating->total_avg_rating     = $avgRating;
            $userRating->write_comment        = $data['write_comment'];            
            $userRating->save();
            return response()->json(['ResponseCode' => 1, 'ReponseText' => 'Rating submit successfully.','ResponseData'=> $userRating],200);
        } catch (\Swift_TransportException $e) {
            \Log::debug($e);
            return response()->json(['ResponseCode' => 0, 'ResponseText' => 'Something went wrong', 'ResponseData' => null], 400);
        }
    }
}
