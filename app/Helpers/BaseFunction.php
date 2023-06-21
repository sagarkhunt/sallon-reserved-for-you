<?php

namespace App\Helpers;

use App\Models\Category;
use App\Models\ApiSession;
use App\Models\Product;
use App\Models\Service;
use App\Models\ServiceAppoinment;
use App\Models\StoreEmp;
use App\Models\StoreEmpService;
use App\Models\StoreEmpTimeslot;
use App\Models\StoreRatingReview;
use App\Models\StoreTiming;
use App\Models\User;
use App\Models\StoreProfile;
use Auth;
use URL;
use Mail;
use File;
use Carbon\Carbon;

class BaseFunction
{
    /**
     * Generate Api Token
     * @param $userId
     * @return bool|mixed|string
     */
    public static function setSessionData($userId)
    {
        if (empty($userId)) {
            return "User id is empty.";
        } else {
            /*  FIND USER ID IN API SESSION AVAILABE OR NOT  */
            $getApiSessionData = ApiSession::where('user_id', $userId)->first();
            if ($getApiSessionData) {
                if ($getApiSessionData->delete()) {
                    $apiSession = new ApiSession();
                    /*  SET SESSION DATA  */
                    $sessionData = [];
                    $sessionData['session_id'] = encrypt(rand(1000, 999999999));
                    $sessionData['user_id'] = $userId;
                    $sessionData['login_time'] = \Carbon\Carbon::now();
                    $sessionData['active'] = 1;
                    $apiSession->fill($sessionData);
                    if ($apiSession->save()) {
                        return $apiSession->session_id;
                    } else {
                        return FALSE;
                    }
                } else {
                    return FALSE;
                }
            } else {
                $apiSession = new ApiSession();
                /*  SET SESSION DATA  */
                $sessionData = [];
                $sessionData['session_id'] = encrypt(rand(1000, 999999999));
                $sessionData['user_id'] = $userId;
                $sessionData['login_time'] = \Carbon\Carbon::now();
                $sessionData['active'] = 1;
                $apiSession->fill($sessionData);
                if ($apiSession->save()) {
                    return $apiSession->session_id;
                } else {
                    return FALSE;
                }
            }
        }
    }

    /**
     * Check token is valid or not
     * @param $sessionId
     * @return array
     */
    public static function checkApisSession($sessionId)
    {
        $checkSessionExist = ApiSession::where('session_id', $sessionId)->first();
        if ($checkSessionExist) {
            $sGetUserDataessionData = [];
            $sessionData['id'] = ($checkSessionExist->id) ? $checkSessionExist->id : '';
            $sessionData['session_id'] = ($checkSessionExist->session_id) ? $checkSessionExist->session_id : '';
            $sessionData['user_id'] = ($checkSessionExist->user_id) ? $checkSessionExist->user_id : '';
            $sessionData['active'] = ($checkSessionExist->active) ? $checkSessionExist->active : '';
            $sessionData['login_time'] = ($checkSessionExist->login_time) ? $checkSessionExist->login_time : '';
            return $sessionData;
        } else {
            return array();
        }
    }

    /**
     * Get Random Code
     * @param $limit
     * @return false|string
     */
    public static function random_code($limit)
    {
        return substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, $limit);
    }

    /**
     * Get User Details
     * @param $id
     * @return mixed
     */
    public static function getUserDetails($id)
    {
        $data = User::findorFail($id);

        return $data;
    }

    public static function getCategoryDate($id)
    {
        $data = Category::findorFail($id);
        return $data;
    }

    public static function getStoreDetails($userId)
    {
        $id = StoreProfile::where('user_id', $userId)->pluck('id');
        return $id;
    }

    public static function getCategoryType($id)
    {
        $data = Category::where('id', $id)->value('category_type');
        return $data;
    }

    public static function getserviceName($id)
    {
        $data = Service::where('id', $id)->value('service_name');
        return $data;
    }

    //user avg rating
    public static function userRating($data)
    {
        $rating = $data['service_rate'] + $data['ambiente'] + $data['preie_leistungs_rate'] + $data['wartezeit'] + $data['atmosphare'];
        return number_format($rating / 5, 2);
    }

    public static function orderNumber()
    {
        $getAppointment = ServiceAppoinment::orderBy('id', 'DESC')->value('order_id');

        if (empty($getAppointment)) {
            $orderId = 0001;
        } else {
            $orderId = $getAppointment + 1;
        }

        return $orderId;

    }

    public static function finalPrice($id)
    {
        $getService = Service::where('id', $id)->first();

        $price = $getService['price'];

        $discountType = $getService['discount_type'];

        if ($discountType == 'amount') {
            $finalPrice = $price - $getService['discount'];
        } elseif ($discountType == 'percentage') {
            $discount = ($price * $getService['discount']) / 100;
            $finalPrice = $price - $discount;
        } else {
            $finalPrice = $price;
        }
        return round($finalPrice,1);

    }

    public static function getSlug($id)
    {
        $slug = StoreProfile::where('id', $id)->value('slug');

        return $slug;
    }

    public static function bookingTimeAvailable($date, $emp_id, $service_id)
    {

        $day = \Carbon\Carbon::parse($date)->format('l');

        $empTime = StoreEmpTimeslot::where('store_emp_id', $emp_id)->where('day', $day)->first();
        if(empty($empTime)){
            return array();
        }
        if ($empTime['is_off'] == 'on') {
            return array();
        }


        $timeDuration = Service::where('id', $service_id)->value('duration_of_service');

        $ReturnArray = array();// Define output
        $StartTime = strtotime($empTime['start_time']); //Get Timestamp

        $EndTime = strtotime($empTime['end_time']); //Get Timestamp
        $AddMins = $timeDuration * 60;

        while ($StartTime <= $EndTime) //Run loop
        {
            $ReturnArray[] = date("H:i", $StartTime);
            $StartTime += $AddMins; //Endtime check
        }
        $currentTime = \Carbon\Carbon::now()->timezone('+1')->format("H:i");
        $now = \Carbon\Carbon::now()->timezone('+1');

        $availableTime = [];

        foreach ($ReturnArray as $value) {
            if ($value == '0:00') {
                return array();
            }
            $time = ServiceAppoinment::where(['store_emp_id' => $emp_id, 'service_id' => $service_id])
                ->where('appo_date', $date)->where('appo_time', $value)
                ->first();

            $time = $time == null ? '' : $time['appo_time'];
            $flag = '';
            if (Carbon::parse($value) == Carbon::parse($time)) {
                $flag = 'Booked';
            } else {
                $flag = 'Available';
            }
            if(\Carbon\Carbon::parse($date)->toDateString() == \Carbon\Carbon::now()->timezone('+1')->toDateString()){
                if (Carbon::parse($value)->format("H:i") > $currentTime) {
                    $availableTime [] = [
                        'time' => $value,
                        'flag' => $flag
                    ];
                }
            } else {
                $availableTime [] = [
                    'time' => $value,
                    'flag' => $flag
                ];
            }

        }

        return $availableTime;

    }

    public static function bookingAvailableTime($date, $service_id)
    {
        $date = \Carbon\Carbon::parse($date)->format('Y-m-d');
        $day = \Carbon\Carbon::parse($date)->format('l');

        $getStoreId = Service::where('id', $service_id)->first();
        $storeTime = StoreTiming::where('store_id', $getStoreId['store_id'])->where('day', $day)->first();

        if (empty($storeTime)) {
            return array();
        }

        if ($storeTime['is_off'] == 'on') {
            return array();
        }


        $ReturnArray = array();// Define output
        $StartTime = strtotime($storeTime['start_time']); //Get Timestamp

        $EndTime = strtotime($storeTime['end_time']); //Get Timestamp
        $AddMins = $getStoreId['duration_of_service'] * 60;


        while ($StartTime <= $EndTime) //Run loop
        {
            $ReturnArray[] = date("H:i", $StartTime);
            $StartTime += $AddMins; //Endtime check
        }
        $availableTime = [];

        $currentTime = \Carbon\Carbon::now()->timezone('+1')->format("H:i");
        $now = \Carbon\Carbon::now()->timezone('+1');

        foreach ($ReturnArray as $value) {
            if ($value == '0:00') {
                return array();
            }
            $time = ServiceAppoinment::where(['service_id' => $service_id])
                ->where('appo_date', $date)->where('appo_time', $value)
                ->first();

            $time = $time == null ? '' : $time['appo_time'];
            $flag = '';
            if (Carbon::parse($value)->format("H:i") == Carbon::parse($time)) {
                $flag = 'Booked';
            } else {
                $flag = 'Available';
            }
            if(\Carbon\Carbon::parse($date)->toDateString() == \Carbon\Carbon::now()->timezone('+1')->toDateString()){
                if (Carbon::parse($value) > $currentTime) {
                    $availableTime [] = [
                        'time' => $value,
                        'flag' => $flag
                    ];
                }
            } else {
                $availableTime [] = [
                    'time' => $value,
                    'flag' => $flag
                ];
            }
        }

        return $availableTime;
    }

    public static function getEmpFromTime($date, $service_id, $time)
    {
        $day = \Carbon\Carbon::parse($date)->format('l');

        $getStoreEmp = StoreEmpService::where('service_id', $service_id)->pluck('store_emp_id')->all();


        $timeDuration = Service::where('id', $service_id)->value('duration_of_service');
        $getFinalEmp = array();
        foreach ($getStoreEmp as $row) {
            $empTime = StoreEmpTimeslot::where('store_emp_id', $row)->where('day', $day)->where('is_off', '!=', 'on')->first();
            if (!empty($empTime)) {
                $time = ServiceAppoinment::where(['store_emp_id' => $row, 'service_id' => $service_id])
                    ->where('appo_date', $date)->where('appo_time', $time)
                    ->first();
                $employeeList = StoreEmp::where('id', $row)->first();

                $employeeList['image'] = URL::to('storage/app/public/store/employee/' . $employeeList['image']);


                $getFinalEmp[] = $employeeList;
            }

        }
        return $getFinalEmp;
    }

    public static function finalRating($id)
    {
        $getratingCount = StoreRatingReview::where('store_id', $id)->count();
        $getratingData = StoreRatingReview::where('store_id', $id)->sum('total_avg_rating');
        $totalRating = 0;
        if($getratingCount != 0){

            $totalRating = $getratingData / $getratingCount;
        }

        return number_format($totalRating,1);
    }

    public static function finalCount($id){
        $getratingCount = StoreRatingReview::where('store_id', $id)->count();

        return $getratingCount;
    }

    public static function getRatingStar($id)
    {
        $id = (string) $id;

        $html = '';
        if ($id == '1.0') {
            $html = '<li><i class="fas fa-star"></i></li><li><i class="far fa-star"></i></li><li><i class="far fa-star"></i></li><li><i class="far fa-star"></i></li><li><i class="far fa-star"></i></li>';
        } elseif ($id == '1.5') {
            $html = '<li><i class="fas fa-star"></i></li><li><i class="fas fa-star-half-alt"></i></li><li><i class="far fa-star"></i></li><li><i class="far fa-star"></i></li><li><i class="far fa-star"></i></li>';
        } elseif ($id == '2.0') {
            $html = '<li><i class="fas fa-star"></i></li><li><i class="fas fa-star"></i></li><li><i class="far fa-star"></i></li><li><i class="far fa-star"></i></li><li><i class="far fa-star"></i></li>';
        } elseif ($id == '2.5') {
            $html = '<li><i class="fas fa-star"></i></li><li><i class="fas fa-star"></i></li><li><i class="fas fa-star-half-alt"></i></li><li><i class="far fa-star"></i></li><li><i class="far fa-star"></i></li>';
        } elseif ($id == '3.0') {
            $html = '<li><i class="fas fa-star"></i></li><li><i class="fas fa-star"></i></li><li><i class="fas fa-star"></i></li><li><i class="far fa-star"></i></li><li><i class="far fa-star"></i></li>';
        } elseif ($id == '3.5') {
            $html = '<li><i class="fas fa-star"></i></li><li><i class="fas fa-star"></i></li><li><i class="fas fa-star"></i></li><li><i class="fas fa-star-half-alt"></i></li><li><i class="far fa-star"></i></li>';
        } elseif ($id == '4.0') {
            $html = '<li><i class="fas fa-star"></i></li><li><i class="fas fa-star"></i></li><li><i class="fas fa-star"></i></li><li><i class="fas fa-star"></i></li><li><i class="far fa-star"></i></li>';
        } elseif ($id == '4.5') {
            $html = '<li><i class="fas fa-star"></i></li><li><i class="fas fa-star"></i></li><li><i class="fas fa-star"></i></li><li><i class="fas fa-star"></i></li><li><i class="fas fa-star-half-alt"></i></li>';
        } elseif ($id == '5.0') {
            $html = '<li><i class="fas fa-star"></i></li><li><i class="fas fa-star"></i></li><li><i class="fas fa-star"></i></li><li><i class="fas fa-star"></i></li><li><i class="fas fa-star"></i></li>';
        }

        return $html;
    }

    public static function getSubRating($type,$id){
        $getratingCount = StoreRatingReview::where('store_id', $id)->count();
        $getratingData = StoreRatingReview::where('store_id', $id)->sum($type);
        $totalRating = 0;
        if($getratingCount != 0) {
            $totalRating = $getratingData / $getratingCount;
        }
        return number_format($totalRating,1);
    }

    public static function checkCancelRatio($id,$appointment){
        $data = StoreProfile::where('id',$id)->first();
        $appo = ServiceAppoinment::where('id',$appointment)->first();
        $today = \Carbon\Carbon::now()->format('Y-m-d');
        $cDay = '';

        if($data['cancellation_day'] == 'day'){
            $cDay = \Carbon\Carbon::parse($appo['appo_date'])->subDays($data['cancellation_deadline'])->format('Y-m-d');
        } elseif ($data['cancellation_day'] == 'day'){
            $cDay = \Carbon\Carbon::parse($appo['appo_date'])->subHour($data['cancellation_deadline'])->format('Y-m-d');
        }
        if($today < $cDay){
           return 'yes';
        } else {
           return  'no';
        }



    }
}
