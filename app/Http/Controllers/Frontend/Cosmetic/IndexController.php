<?php

namespace App\Http\Controllers\Frontend\Cosmetic;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Favourite;
use App\Models\Service;
use App\Models\Store\Advantages;
use App\Models\Store\Parking;
use App\Models\Store\PublicTransportation;
use App\Models\StoreCategory;
use App\Models\StoreEmp;
use App\Models\StoreEmpService;
use App\Models\StoreEmpTimeslot;
use App\Models\StoreGallery;
use App\Models\StoreProfile;
use App\Models\StoreRatingReview;
use App\Models\StoreTiming;
use Illuminate\Http\Request;
use URL;
use Auth;
use DB;

class IndexController extends Controller
{
    /**
     * Main Page
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $data = Category::where('category_type', 'Cosmetics')->where('status', 'active')->where('main_category', null)->get();
        $Catering = Category::where('category_type', 'Catering')->where('status', 'active')->where('main_category', null)->get();

        return view('Front.Cosmetic.index', compact('data','Catering'));
    }

    public function getSubCat(Request $request){
        $data = $request->all();
        $category  = Category::where('main_category', $data['cat'])->pluck('name','id');
        // $stores = StoreProfile::join('store_categories','store_categories.store_id','store_profiles.store_id')->where('category_id',$id)->pluck('store_name','id');
        return $category;

    }

    public function getStores(Request $request){
        $data = $request->all();
        $stores = StoreProfile::join('store_categories','store_categories.store_id','store_profiles.id')->where('store_categories.category_id',$data['cat'])->orWhere('store_categories.category_id',$data['sub_cat'])->pluck('store_profiles.store_name','store_profiles.id');
        return $stores;

    }

    // public function search(Request $request){
    //     $data = $request->all();
    //     $data = StoreProfile::join('store_categories','store_categories.store_id','store_profiles.id')->where('store_categories.category_id',$data['categories'])->orWhere('store_categories.category_id',$data['sab_cat'])->orWhere('store_profiles.id',$data['stores'])->orWhere('store_profiles.zipcode',$data['postal_code'])->get();

    //     $recommanded = StoreProfile::where('is_recommended','yes')->get();

    //     return view('Front.Cosmetic.cosmeticArea',compact('data','recommanded'));
    // }

    /**
     * Costmetic Area
     * @param Request $request
     * @param null $slug
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function search(Request $request, $slug = null)
    {

        $category = Category::where('category_type', 'Cosmetics')->where('status', 'active')->where('main_category', null)->get();
        $getCategory_id = '';
        $categories = $request['categories'];
        $sub_cat = $request['sub_cat'];
        $pincode = $request['postal_code'];
        $date = $request['date'];
        $stores = $request['stores'];

        $maxprice = Service::where('status', 'active')->max('price');


        $day = \Carbon\Carbon::parse($date)->format('l');


        $subCategory = Category::where('category_type', 'Cosmetics')->where('status', 'active')->where('main_category', '!=', null)->get();


        $searchCategory = Category::where('name', 'LIKE', "%{$category}%")->pluck('id')->all();

        $getStore = StoreProfile::leftjoin('store_categories', 'store_categories.store_id', '=', 'store_profiles.id');

        $getStore = $getStore->leftjoin('services', 'services.store_id', '=', 'store_profiles.id');

        if (!empty($date)) {
            $getStore = $getStore->leftjoin('store_timings', 'store_timings.store_id', '=', 'store_profiles.id');
        }

        if (!empty($stores)) {
            $getStore = $getStore->orWhere('store_profiles.store_name', 'LIKE' ,'%'.$stores.'%');
        }

        if (!empty($categories)) {
            $getStore = $getStore->orWhere('store_categories.category_id', $categories);
        }

        if (!empty($sub_cat)) {
            $getStore = $getStore->orWhere('store_categories.category_id', $sub_cat);
        }

        if (!empty($date)) {
            $getStore = $getStore->orWhere('store_timings.day', $day)->where('store_timings.is_off', null);
        }

        if (!empty($pincode)) {
            $getStore = $getStore->where('store_profiles.zipcode', 'LIKE', "%{$pincode}%");
        }

        if(!empty($ids)){
            
        }

        $getStore = $getStore->where('store_profiles.store_active_plan', 'business')->orderBy('store_profiles.id', 'DESC')
            ->select('store_profiles.*')
            ->where('store_profiles.store_status', 'active')
            ->distinct()
            ->get();
        // dd($getStore);

        foreach ($getStore as $row) {
            $row->categories = StoreCategory::where('store_id', $row->id)->get();
            $categories = StoreCategory::where('store_id', $row->id)->groupBy('category_id')->pluck('category_id')->all();
            $row->rating = \BaseFunction::finalRating($row->id);
            $row->ratingCount = \BaseFunction::finalCount($row->id);
            foreach (@$row->storeCategory as $key => $cat) {
                @$cat->CategoryData;
            }

            $row->rating = \BaseFunction::finalRating($row->id);
            $row->rating_count = @$row->storeRated->count();
            $row->isFavorite = 'false';
            if (Auth::check()) {
                $favor = Favourite::where('user_id', Auth::user()->id)->where('store_id', $row->id)->first();
                if (!empty($favor)) {
                    $row->isFavorite = 'true';
                } else {
                    $row->isFavorite = 'false';
                }
            }
        }


        $recommandedforyou = StoreProfile::leftjoin('store_categories', 'store_categories.store_id', '=', 'store_profiles.id');
        if (!empty($categories)) {
            $recommandedforyou = $recommandedforyou->where('store_categories.category_id', $categories);
        }

        $recommandedforyou = $recommandedforyou->where('store_profiles.store_status', 'active')->where('store_profiles.is_recommended', 'yes')->select('store_profiles.*')->distinct()->inRandomOrder()->get();
        
        foreach ($recommandedforyou as $row) {
            $row->rating = \BaseFunction::finalRating($row->id);
            $row->ratingCount = \BaseFunction::finalCount($row->id);
        }

        // $new_store = StoreProfile::where('store_profiles.store_status', 'active')->where('store_profiles.created_at','>=' ,\Carbon\Carbon::now()->subDays('-15'))->select('store_profiles.*')->distinct()->inRandomOrder()->get();
        $new_store = StoreProfile::where('store_profiles.store_status', 'active')->orderBy('store_profiles.created_at','desc')->select('store_profiles.*')->distinct()->inRandomOrder()->get();
        
        foreach ($new_store as $row) {
            $row->rating = \BaseFunction::finalRating($row->id);
            $row->ratingCount = \BaseFunction::finalCount($row->id);
        }

        $high_rate = StoreProfile::leftjoin('store_rating_reviews', 'store_rating_reviews.store_id', '=', 'store_profiles.id');
        $high_rate = $high_rate->where('store_profiles.store_status', 'active')->select('store_profiles.*')->orderBy('store_rating_reviews.total_avg_rating','desc')->distinct()->inRandomOrder()->get();
        
        foreach ($new_store as $row) {
            $row->rating = \BaseFunction::finalRating($row->id);
            $row->ratingCount = \BaseFunction::finalCount($row->id);
        }

        $category = Category::where('status', 'active')->where('main_category', null)->pluck('name','id');

        if($request->is_ajax){
            return $getStore;
        }
        return view('Front.Cosmetic.cosmeticArea', compact('category', 'getCategory_id', 'getStore', 'slug', 'recommandedforyou','new_store' , 'subCategory', 'maxprice','high_rate'));
    }

    /**
     * Costmetic View
     * @param null $slug
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
    public function cosmeticView($slug = null)
    {
        if (empty($slug)) {
            return redirect('cosmetic-area');
        }
        $store = StoreProfile::where('slug', $slug)->first();
        $storeTiming = StoreTiming::where('store_id', $store['id'])->get();
        $storeGallery = StoreGallery::where('store_id', $store['id'])->get();
        $storeCategory = StoreCategory::where('store_id', $store['id'])->get();
        $today = ucfirst(\Carbon\Carbon::now()->timezone('+1')->format('l'));
        $storeToday = StoreTiming::where('day', $today)->where('store_id', $store['id'])->first();
        $service = Service::where('store_id', $store['id'])->where('status', 'active')->get();
        $serviceList = ['' => 'Select Service'] + Service::where('store_id', $store['id'])->where('status', 'active')->pluck('service_name', 'id')->all();
        $advantages = Advantages::where('store_id', $store['id'])->where('status', 'active')->get();
        $transport = PublicTransportation::where('store_id', $store['id'])->where('status', 'active')->get();
        $parking = Parking::where('store_id', $store['id'])->where('status', 'active')->get();
        $expert = StoreEmp::where('store_id', $store['id'])->where('status', 'active')->distinct('id')->get();
        $feedback = StoreRatingReview::where('store_id', $store['id'])->get();
        $store['rating'] = \BaseFunction::finalRating($store['id']);
        $rating['service_rate'] = \BaseFunction::getSubRating('service_rate', $store['id']);
        $rating['ambiente'] = \BaseFunction::getSubRating('ambiente', $store['id']);
        $rating['preie_leistungs_rate'] = \BaseFunction::getSubRating('preie_leistungs_rate', $store['id']);
        $rating['wartezeit'] = \BaseFunction::getSubRating('wartezeit', $store['id']);
        $rating['atmosphare'] = \BaseFunction::getSubRating('atmosphare', $store['id']);
        $time = \Carbon\Carbon::now()->timezone('+1')->format('H:i');
        $sstatus = 'off';
        if (\Carbon\Carbon::parse($storeToday['start_time'])->format("H:i") <= $time && \Carbon\Carbon::parse($storeToday['end_time'])->format("H:i") >= $time) {
            $sstatus = 'on';
        }

        $cas = array();
        foreach ($service as $row) {
            $cas[$row->category_id][] = $row;
        }
        $catlist = array();

        foreach ($storeCategory as $row) {
            $catlist[] = @$row->CategoryData->name;
        }
        $store['isFavorite'] = 'false';

        if (Auth::check()) {
            $favor = Favourite::where('user_id', Auth::user()->id)->where('store_id', $store['id'])->first();
            if (!empty($favor)) {
                $store['isFavorite'] = 'true';
            } else {
                $store['isFavorite'] = 'false';
            }
        }


        return view('Front.Cosmetic.about', compact('store', 'storeTiming', 'storeCategory', 'storeGallery', 'slug', 'today', 'storeToday', 'cas', 'serviceList',
            'catlist', 'advantages', 'transport', 'parking', 'expert', 'feedback', 'rating','sstatus'));
    }

    /**
     * Filter
     * @param Request $request
     * @return array
     */
    public function filter(Request $request)
    {
        $range = $request['range'];
        $category = $request['category'];
        $zipcode = $request['zipcode'];
        $discount = $request['discount'];
        $booking = $request['booking'];
        $rating = $request['rating'];
        $expensive = $request['expensive'];
        $slug = $request['slug'];

        if(empty($category) && !empty($slug)){
            $category = Category::where('slug',$slug)->pluck('id')->all();
        }

        $getStore = StoreProfile::leftjoin('store_categories', 'store_categories.store_id', '=', 'store_profiles.id')
            ->leftjoin('services', 'services.store_id', '=', 'store_profiles.id')
            ->leftjoin('store_rating_reviews', 'store_rating_reviews.store_id', '=', 'store_profiles.id');

        if (!empty($zipcode)) {
            $getStore = $getStore->where('store_profiles.zipcode', $zipcode);
        }

        if (!empty($booking)) {
            $getStore = $getStore->where('store_profiles.store_active_plan', 'business');
        }

        if (!empty($discount)) {
            $getStore = $getStore->where('services.discount_type', '!=', 'null');
        }

        if (!empty($category)) {
            $getStore = $getStore->whereIn('store_categories.category_id', $category);
        }

        if (!empty($range)) {
            $getStore = $getStore->where('services.price', '<=', $range);
        }

        $getStore = $getStore->orderBy('store_profiles.id', 'DESC')
            ->select('store_profiles.*')
            ->where('store_profiles.store_status', 'active')
            ->distinct()
            ->get();

        foreach ($getStore as $row) {
            $categories = StoreCategory::where('store_id', $row->id)->get();

            $c = array();
            foreach ($categories as $item) {
                $c[] = @$item->CategoryData->name;
            }

            $row->categories = $c;
            $row->expensive = count(explode("€", $row->is_value)) - 1;

            if (file_exists('storage/app/public/store/' . $row->store_profile) && $row->store_profile != '') {
                $row->store_profile = URL::to('storage/app/public/store/' . $row->store_profile);
            } else {
                $row->store_profile = URL::to('storage/app/public/default/default_store.jpeg');
            }


            $row->rating = \BaseFunction::finalRating($row->id);
            $row->ratingCount = \BaseFunction::finalCount($row->id);
            $row->isFavorite = 'false';
            if (Auth::check()) {
                $favor = Favourite::where('user_id', Auth::user()->id)->where('store_id', $row->id)->first();
                if (!empty($favor)) {
                    $row->isFavorite = 'true';
                } else {
                    $row->isFavorite = 'false';
                }
            }
        }

        if ($expensive != 5 && !empty($expensive)) {
            $finalData = [];
            foreach ($getStore as $row) {
                if ($expensive >= $row->expensive) {
                    $finalData[] = $row;
                }
            }
            $getStore = $finalData;

        }

        if (!empty($rating)) {
            $finalData = [];
            foreach ($getStore as $row) {
                if ($row->rating <= $rating) {
                    $finalData[] = $row;
                }
            }
            $getStore = $finalData;
//            $getStore = $getStore->where('store_rating_reviews.total_avg_rating', '<=', $rating);
        }

        if (count($getStore) > 0) {
            return ['status' => 'true', 'data' => $getStore];
        } else {
            return ['status' => 'false', 'data' => []];
        }

    }

    /**
     * Shotby Dropdown
     * @param Request $request
     * @return array
     */
    public function shortBy(Request $request)
    {
        $short = $request['value'];
        $discount = $request['discount'];
        $booking = $request['booking'];
        $category = '';
        $slug = $request['slug'];

        if(empty($category) && !empty($slug)){
            $category = Category::where('slug',$slug)->pluck('id')->all();
        }

        $getStore = StoreProfile::leftjoin('store_categories', 'store_categories.store_id', '=', 'store_profiles.id')
            ->leftjoin('services', 'services.store_id', '=', 'store_profiles.id');

        if ($short == 'recommanded') {
            $getStore = $getStore->where('store_profiles.is_recommended', 'yes');
        }


        if (!empty($category)) {
            $getStore = $getStore->whereIn('store_categories.category_id', $category);
        }

        if ($short == 'new') {
            $getStore = $getStore->orderBy('store_profiles.id', 'DESC');
        }

        if (!empty($booking)) {
            $getStore = $getStore->where('store_profiles.store_active_plan', 'business');
        }

        if (!empty($discount)) {
            $getStore = $getStore->where('services.discount_type', '!=', 'null');
        }


        $getStore = $getStore->select('store_profiles.*')
            ->where('store_profiles.store_status', 'active')
            ->distinct()
            ->get();
        $shorting = [];
        $rating = [];
        foreach ($getStore as $row) {
            $categories = StoreCategory::where('store_id', $row->id)->get();

            $c = array();
            foreach ($categories as $item) {
                $c[] = @$item->CategoryData->name;
            }
            $row->categories = $c;

            if (file_exists('storage/app/public/store/' . $row->store_profile) && $row->store_profile != '') {
                $row->store_profile = URL::to('storage/app/public/store/' . $row->store_profile);
            } else {
                $row->store_profile = URL::to('storage/app/public/default/default_store.jpeg');
            }


            $row->rating = \BaseFunction::finalRating($row->id);
            $row->ratingCount = \BaseFunction::finalCount($row->id);
            $row->isFavorite = 'false';
            if (Auth::check()) {
                $favor = Favourite::where('user_id', Auth::user()->id)->where('store_id', $row->id)->first();
                if (!empty($favor)) {
                    $row->isFavorite = 'true';
                } else {
                    $row->isFavorite = 'false';
                }
            }

            if ($short == 'low_price') {
                $row->price = Service::where('store_id', $row->id)->min('price');

            }
            if ($short == 'high_price') {
                $row->price = Service::where('store_id', $row->id)->max('price');
            }

            if ($short == 'low_price' || $short == 'high_price') {
                if ($row->price != null) {
                    $shorting[] = $row;
                }
            }

            if ($short == 'best_rating') {
                if ($row->rating != null) {
                    $rating[] = $row;
                }
            }


        }

        if ($short == 'low_price') {
            $keys = array_column($shorting, 'price');

            array_multisort($keys, SORT_ASC, $shorting);
            $getStore = $shorting;
        }

        if ($short == 'high_price') {
            $keys = array_column($shorting, 'price');

            array_multisort($keys, SORT_DESC, $shorting);
            $getStore = $shorting;
        }

        if ($short == 'best_rating') {
            $keys = array_column($rating, 'rating');

            array_multisort($keys, SORT_DESC, $rating);
            $getStore = $rating;
        }


        if (count($getStore) > 0) {
            return ['status' => 'true', 'data' => $getStore];
        } else {
            return ['status' => 'false', 'data' => []];
        }
    }

    /**
     * Get Employee
     * @param Request $request
     * @return array
     */
    public function getEmployee(Request $request)
    {
        $service = $request['service'];
        $date = $request['date'];
        $time = $request['time'];

        $day = \Carbon\Carbon::parse($date)->format('l');

        $employeeList = StoreEmp::leftjoin('store_emp_categories', 'store_emp_categories.store_emp_id', '=', 'store_emps.id')
            ->leftjoin('store_emp_timeslots', 'store_emp_timeslots.store_emp_id', '=', 'store_emps.id')
            ->leftjoin('store_emp_services', 'store_emp_services.store_emp_id', '=', 'store_emps.id')
            ->where('store_emp_services.service_id', $service)
            ->where('store_emp_timeslots.day', $day)
            ->where('store_emp_timeslots.is_off', '!=', 'on')
//            ->where('store_emp_timeslots.start_time', '>=', $time)
//            ->where('store_emp_timeslots.end_time', '<=', $time)
            ->select('store_emps.*')
            ->distinct('store_emps.id')
            ->get();

        foreach ($employeeList as $row) {

            if (file_exists('storage/app/public/store/employee/' . $row->image) && $row->image != '') {
                $row->image = URL::to('storage/app/public/store/employee/' . $row->image);
            } else {
                $row->image = URL::to('storage/app/public/default/default-user.png');
            }

        }

        if (count($employeeList) > 0) {
            return ['status' => 'true', 'data' => $employeeList];
        } else {
            return ['status' => 'false', 'data' => []];
        }

    }

    /**
     * Get Employee List
     * @param Request $request
     * @return array
     */
    public function getEmployeeList(Request $request)
    {
        $service = $request['service'];

        $employeeList = StoreEmp::leftjoin('store_emp_categories', 'store_emp_categories.store_emp_id', '=', 'store_emps.id')
            ->leftjoin('store_emp_timeslots', 'store_emp_timeslots.store_emp_id', '=', 'store_emps.id')
            ->leftjoin('store_emp_services', 'store_emp_services.store_emp_id', '=', 'store_emps.id')
            ->where('store_emp_services.service_id', $service)
            ->select('store_emps.*')
            ->distinct('store_emps.id')
            ->get();

        foreach ($employeeList as $row) {
            if (file_exists('storage/app/public/store/employee/' . $row->image) && $row->image != '') {
                $row->image = URL::to('storage/app/public/store/employee/' . $row->image);
            } else {
                $row->image = URL::to('storage/app/public/default/default-user.png');
            }
        }

        if (count($employeeList) > 0) {
            return ['status' => 'true', 'data' => $employeeList];
        } else {
            return ['status' => 'false', 'data' => []];
        }
    }

    /**
     * Get Datepicker time
     * @param Request $request
     * @return array
     */
    public function getDatepicker(Request $request)
    {
        $emp = $request['emp'];

        $timeSlot = StoreEmpTimeslot::where('store_emp_id', $emp)->get();
        $offDay = [];
        $i = 0;
        foreach ($timeSlot as $row) {
            if ($row->is_off == 'on') {
                $offDay[] = $i;
            }
            $i++;
        }

        return ['status' => 'true', 'data' => $offDay];

    }

    /**
     * Get service Details
     * @param Request $request
     * @return array
     */
    public function getServiceDetails(Request $request)
    {
        $id = $request['service'];

        $data = Service::where('id', $id)->first();
        $data['price'] = \BaseFunction::finalPrice($data['id']);

        if (file_exists('storage/app/public/service/' . $data['image']) && $data['image'] != '') {
            $data['image'] = URL::to('storage/app/public/service/' . $data['image']);
        } else {
            $data['image'] = URL::to('storage/app/public/default/store_default.png');
        }

        if (!empty($data)) {
            return ['status' => 'true', 'data' => $data];
        } else {
            return ['status' => 'false', 'data' => []];
        }


    }

    /**
     * Get Time Slot
     * @param Request $request
     * @return array
     */
    public function getTimeslot(Request $request)
    {
        $data = $request->all();

        $time = \BaseFunction::bookingTimeAvailable(\Carbon\Carbon::parse($data['date'])->format('Y-m-d'), $data['emp'], $data['service']);

        if (count($time) > 0) {
            return ['status' => 'true', 'data' => $time];
        } else {
            return ['status' => 'false', 'data' => []];
        }

    }

    /**
     * Get Available Time
     * @param Request $request
     * @return array
     */
    public function getAvailableTime(Request $request)
    {
        $data = $request->all();

        $time = \BaseFunction::bookingAvailableTime($data['date'], $data['service']);

        if (count($time) > 0) {
            return ['status' => 'true', 'data' => $time];
        } else {
            return ['status' => 'false', 'data' => []];
        }
    }

    /**
     * Get Available Time Direct
     * @param Request $request
     * @return array
     */
    public function getAvailableTimeDirect(Request $request)
    {
        $data = $request->all();
        $time = \BaseFunction::bookingAvailableTime(\Carbon\Carbon::parse($data['date'])->format('Y-m-d'), $data['service']);

        if (count($time) > 0) {
            return ['status' => 'true', 'data' => $time];
        } else {
            return ['status' => 'false', 'data' => []];
        }
    }

    /**
     * Get Available Employee
     * @param Request $request
     * @return array
     */
    public function getAvailableEmp(Request $request)
    {
        $data = $request->all();

        $emp = \BaseFunction::getEmpFromTime(\Carbon\Carbon::createFromFormat('d-m-Y', $data['date'])->format('Y-m-d'), $data['service'], $data['time']);
        if (count($emp) > 0) {
            return ['status' => 'true', 'data' => $emp];
        } else {
            return ['status' => 'false', 'data' => []];
        }
    }

    /**
     * Get Booking Data
     * @param Request $request
     * @return array
     */
    public function getBookingData(Request $request)
    {

        $value = $request['value'];
        $discount = $request['discount'];
        $slug = $request['slug'];

        if(empty($category) && !empty($slug)){
            $category = Category::where('slug',$slug)->pluck('id')->all();
        }

        $getStore = StoreProfile::leftjoin('store_categories', 'store_categories.store_id', '=', 'store_profiles.id')
            ->leftjoin('services', 'services.store_id', '=', 'store_profiles.id')
            ->leftjoin('store_rating_reviews', 'store_rating_reviews.store_id', '=', 'store_profiles.id');


        if (!empty($value) && $value == 'yes') {
            $getStore = $getStore->where('store_profiles.store_active_plan', 'business');
        }

        if (!empty($category)) {
            $getStore = $getStore->whereIn('store_categories.category_id', $category);
        }


        if (!empty($discount)) {
            $getStore = $getStore->where('services.discount_type', '!=', 'null');
        }


        $getStore = $getStore->orderBy('store_profiles.id', 'DESC')
            ->select('store_profiles.*')
            ->where('store_profiles.store_status', 'active')
            ->distinct()
            ->get();

        foreach ($getStore as $row) {
            $categories = StoreCategory::where('store_id', $row->id)->get();

            $c = array();
            foreach ($categories as $item) {
                $c[] = @$item->CategoryData->name;
            }
            $row->categories = $c;

            if (file_exists('storage/app/public/store/' . $row->store_profile) && $row->store_profile != '') {
                $row->store_profile = URL::to('storage/app/public/store/' . $row->store_profile);
            } else {
                $row->store_profile = URL::to('storage/app/public/default/default_store.jpeg');
            }


            $row->rating = \BaseFunction::finalRating($row->id);
            $row->ratingCount = \BaseFunction::finalCount($row->id);
            $row->isFavorite = 'false';
            if (Auth::check()) {
                $favor = Favourite::where('user_id', Auth::user()->id)->where('store_id', $row->id)->first();
                if (!empty($favor)) {
                    $row->isFavorite = 'true';
                } else {
                    $row->isFavorite = 'false';
                }
            }
        }

        if (count($getStore) > 0) {
            return ['status' => 'true', 'data' => $getStore];
        } else {
            return ['status' => 'false', 'data' => []];
        }
    }

    /**
     * get Service Data
     * @param Request $request
     * @return array
     */
    public function getServiceData(Request $request)
    {
        $service = $request['service'];
        $emp = $request['employee_list'];

        $serviceData = Service::where('id', $service)->first();

        if (file_exists('storage/app/public/service/' . $serviceData['image'] ) && $serviceData['image']  != '') {
            $serviceData['image'] = URL::to('storage/app/public/service/' . $serviceData['image'] );
        } else {
            $serviceData['image'] = URL::to('storage/app/public/default/store_default.png');
        }

        $serviceData['emp_name'] = StoreEmp::where('id', $emp)->value('emp_name');
        $serviceData['discount_price'] = \BaseFunction::finalPrice($serviceData['id']);

        if (!empty($serviceData)) {
            return ['status' => 'true', 'data' => $serviceData];
        } else {
            return ['status' => 'false', 'data' => []];
        }
    }

    /**
     * Main page search bar
     * @param Request $request
     * @return array
     */
    public function searchBar(Request $request)
    {

        $search = $request['search'];
        $getStores = StoreProfile::where('store_name', 'LIKE', "%{$search}%")
            ->orderBy('id', 'DESC')
            ->where('store_status', 'active')
            ->distinct()
            ->get();

        $getService = Service::where('service_name', 'LIKE', "%{$search}%")
            ->orderBy('id', 'DESC')
            ->where('status', 'active')
            ->distinct()
            ->get();

        $getCategory = Category::where('name', 'LIKE', "%{$search}%")
            ->orderBy('id', 'DESC')
            ->where('status', 'active')
            ->where('category_type', 'Cosmetics')
            ->distinct()
            ->get();

        $getStore = [];
        foreach ($getStores as $row) {
            $row->url = 'store';
            $row->search_name = $row->store_name;
            $getStore[] = $row;
        }


        foreach ($getService as $row) {
            $row->url = 'service';
            $row->search_name = $row->service_name;
            $getStore[] = $row;
        }
        foreach ($getCategory as $row) {
            $row->url = 'category';
            $row->search_name = $row->name;
            $getStore[] = $row;
        }


        if (!empty($getStore)) {
            return ['status' => 'true', 'data' => $getStore];
        } else {
            return ['status' => 'false', 'data' => []];
        }
    }

    /**
     * Costmatic area search bar
     * @param Request $request
     * @return array
     */
    public function searchBarSearvice(Request $request)
    {
        $search = $request['search'];
        $getStores = StoreProfile::where('store_name', 'LIKE', "%{$search}%")
            ->orderBy('id', 'DESC')
            ->where('store_status', 'active')
            ->distinct()
            ->get();

        $getService = Service::where('service_name', 'LIKE', "%{$search}%")
            ->orderBy('id', 'DESC')
            ->where('status', 'active')
            ->distinct()
            ->get();

        $getCategory = Category::where('name', 'LIKE', "%{$search}%")
            ->orderBy('id', 'DESC')
            ->where('status', 'active')
            ->where('category_type', 'Cosmetics')
            ->distinct()
            ->get();

        $getStore = [];
        foreach ($getStores as $row) {
            $row->url = 'store';
            $row->search_name = $row->store_name;
            $getStore[] = $row;
        }


        foreach ($getService as $row) {
            $row->url = 'service';
            $row->search_name = $row->service_name;
            $getStore[] = $row;
        }
        foreach ($getCategory as $row) {
            $row->url = 'category';
            $row->search_name = $row->name;
            $getStore[] = $row;
        }

        if (!empty($getStore)) {
            return ['status' => 'true', 'data' => $getStore];
        } else {
            return ['status' => 'false', 'data' => []];
        }
    }

    /**
     * Recommandation View
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function recommendedYou()
    {
        $getStore = StoreProfile::leftjoin('store_categories', 'store_categories.store_id', '=', 'store_profiles.id');

        $getStore = $getStore->join('services', 'services.store_id', '=', 'store_profiles.id');

        $getStore = $getStore->where('store_profiles.store_active_plan', 'business')
            ->orderBy('store_profiles.id', 'DESC')
            ->select('store_profiles.*')
            ->where('store_profiles.store_status', 'active')
            ->where('is_recommended', 'yes')
            ->distinct()
            ->get();

        foreach ($getStore as $row) {
            $row->categories = StoreCategory::where('store_id', $row->id)->get();
            $categories = StoreCategory::where('store_id', $row->id)->groupBy('category_id')->pluck('category_id')->all();
            $row->rating = \BaseFunction::finalRating($row->id);
            $row->ratingCount = \BaseFunction::finalCount($row->id);
            $row->isFavorite = 'false';
            if (Auth::check()) {
                $favor = Favourite::where('user_id', Auth::user()->id)->where('store_id', $row->id)->first();
                if (!empty($favor)) {
                    $row->isFavorite = 'true';
                } else {
                    $row->isFavorite = 'false';
                }
            }
        }
        return view('Front.Cosmetic.recommended', compact('getStore'));

    }

    /**
     * Get EMp Data
     * @param Request $request
     * @return array
     */
    public function getEmployeeData(Request $request)
    {
        $id = $request['emp'];
        $data = StoreEmp::where('id', $id)->first();

        if (file_exists('storage/app/public/store/employee/' . $data['image']) && $data['image'] != '') {
            $data['image'] = URL::to('storage/app/public/store/employee/' . $data['image']);
        } else {
            $data['image'] = URL::to('storage/app/public/default/default-user.png');
        }

        if (!empty($data)) {
            return ['status' => 'true', 'data' => $data];
        } else {
            return ['status' => 'false', 'data' => []];
        }
    }

    /**
     * Get service value data
     * @param Request $request
     * @return array
     */
    public function getServiceValueData(Request $request)
    {
        $id = $request['emp'];


        $service = Service::leftjoin('store_emp_services', 'store_emp_services.service_id', '=', 'services.id')
            ->where('store_emp_services.store_emp_id', $id)
            ->select('services.*')->get();
        foreach ($service as $row) {
            if (file_exists('storage/app/public/service/' . $row->image) && $row->image != '') {
                $row->image = URL::to('storage/app/public/service/' . $row->image);
            } else {
                $row->image = URL::to('storage/app/public/default/store_default.png');
            }

        }

        if (!empty($service)) {
            return ['status' => 'true', 'data' => $service];
        } else {
            return ['status' => 'false', 'data' => []];
        }
    }

    /**
     * Rating Filter
     * @param Request $request
     * @return array
     */
    public function ratingFilter(Request $request)
    {
        $filter = $request['value'];
        $store = $request['store_id'];

        $feedback = StoreRatingReview::where('store_id', $store);

        if (!empty($filter)) {
            $feedback = $feedback->where('total_avg_rating', '<=', $filter);
        }

        $feedback = $feedback->get();

        foreach ($feedback as $row) {
            if (file_exists(storage_path('app/public/user/' . @$row->userDetails->profile_pic)) && @$row->userDetails->profile_pic != '') {
                $row->profile_pic = URL::to('storage/app/public/user/' . @$row->userDetails->profile_pic);
            } else {
                $row->profile_pic = URL::to('storage/app/public/default/default-user.png');
            }

            $row->user_name = $row->userDetails->first_name . ' ' . $row->userDetails->last_name;
            $row->time = \Carbon\Carbon::parse($row->updated_at)->diffForHumans();
        }

        if (count($feedback) > 0) {
            return ['status' => 'true', 'data' => $feedback];
        } else {
            return ['status' => 'false', 'data' => []];
        }
    }

    /**
     * Get Subcategory
     * @param Request $request
     * @return array
     */
    public function getSubCategory(Request $request)
    {
        $value = $request['value'];

        $category = Category::where('main_category', $value)->where('status', 'active')->get();
        foreach ($category as $row) {

            if (file_exists('storage/app/public/category/' . $row->image) && $row->image != '') {
                $row->images = URL::to('storage/app/public/category/' . $row->image);
                $row->imageFile = file_get_contents(URL::to('storage/app/public/category/' . $row->image));
            } else {
                $row->images = URL::to('storage/app/public/default/store_default.png');
                $row->imageFile = json_decode(file_get_contents(URL::to('storage/app/public/default/store_default.png')));
            }
        }

        if (!empty($category)) {
            return ['status' => 'true', 'data' => $category];
        } else {
            return ['status' => 'false', 'data' => []];
        }
    }

}
