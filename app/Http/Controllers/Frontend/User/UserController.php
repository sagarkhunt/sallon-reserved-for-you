<?php

namespace App\Http\Controllers\Frontend\User;

use App\Http\Controllers\Controller;
use App\Models\Favourite;
use App\Models\PaymentMethodInfo;
use App\Models\ServiceAppoinment;
use App\Models\StoreCategory;
use App\Models\StoreProfile;
use App\Models\StoreRatingReview;
use Illuminate\Http\Request;
use Auth;
use File;
use URL;
use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        $date = \Carbon\Carbon::now()->format('Y-m-d');

        $pastAppointment = ServiceAppoinment::where('user_id', Auth::user()->id)
            ->where(function ($query) use ($date) {
                $query->wheredate('appo_date', '<', $date)
                    ->orWhereIn('status',['cancel','completed']);
            })
        ->orderBy('appo_date','DESC')->get();

        foreach ($pastAppointment as $row){
            $row->isAPP = \BaseFunction::checkCancelRatio($row->store_id,$row->id);
            $row->payment_method = PaymentMethodInfo::where('appoinment_id',$row->id)->value('payment_method');
            $row->empName = @$row->employeeDetails->emp_name;
            $row->serviceTime = @$row->serviceDetails->duration_of_service;
        }


        $upcommingAppointment = ServiceAppoinment::where('user_id', Auth::user()->id)->wheredate('appo_date', '>=', $date)->WhereNotIn('status',['cancel','completed'])->orderBy('appo_date','DESC')->get();
        $calander = [];
        foreach ($upcommingAppointment as $row){
            $row->isAPP = \BaseFunction::checkCancelRatio($row->store_id,$row->id);
            $row->payment_method = PaymentMethodInfo::where('appoinment_id',$row->id)->value('payment_method');
            $row->empName = @$row->employeeDetails->emp_name;
            $row->serviceTime = @$row->serviceDetails->duration_of_service;
            $calander[] = array('title'=>$row->service_name,'start'=>date('Y-m-d H:i:s', strtotime("$row->appo_date  $row->appo_time")));
        }


        $favorites = Favourite::leftjoin('store_profiles','store_profiles.id','=','favourites.store_id')
            ->leftjoin('store_categories', 'store_categories.store_id', '=', 'store_profiles.id')
            ->where('favourites.user_id', Auth::user()->id)
            ->select('store_profiles.*')
            ->where('store_profiles.store_status', 'active')
            ->distinct('store_profiles.id')
            ->get();

        foreach ($favorites as $row) {
            $row->categories = StoreCategory::where('store_id', $row->id)->get();
            $row->rating = \BaseFunction::finalRating($row->id);
            $row->ratingCount = \BaseFunction::finalCount($row->id);
            if(Auth::check()){
                $favor = Favourite::where('user_id',Auth::user()->id)->where('store_id',$row->id)->first();
                if(!empty($favor)){
                    $row->isFavorite = 'true';
                } else {
                    $row->isFavorite = 'false';
                }
            }
        }


        return view('Front.User.index', compact('pastAppointment', 'upcommingAppointment','favorites','calander'));
    }

    public function updateProfile(Request $request)
    {
        $this->validate($request, [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:users,email,' . Auth::user()->id,
        ]);

        $data = $request->all();

        $data = $request->except('_token');

        $update = User::where('id', Auth::user()->id)->update($data);

        if ($update) {
            return ['status' => 'true', 'data' => []];
        } else {
            return ['status' => 'false', 'data' => []];
        }
    }

    public function favoriteStore(Request $request)
    {
        $id = $request['id'];

        $checkFavorite = Favourite::where('user_id', Auth::user()->id)->where('store_id', $id)->first();

        if (!empty($checkFavorite)) {
            $remove = Favourite::where('user_id', Auth::user()->id)->where('store_id', $id)->delete();

            if ($remove) {
                return ['status' => 'true', 'data' => 'remove'];
            }
        } else {
            $data['user_id'] = Auth::user()->id;
            $data['store_id'] = $request['id'];
            $favStore = new Favourite();
            $favStore->fill($data);
            if ($favStore->save()) {
                return ['status' => 'true', 'data' => 'add'];
            }
        }

        return ['status' => 'false', 'data' => 'add'];
    }

    public function changeProfile(Request $request){
        if ($request->file('profile_image')) {

            $oldimage = User::where('id', Auth::user()->id)->value('profile_pic');

            if (!empty($oldimage)) {

                File::delete('storage/app/public/user/' . $oldimage);
            }

            $file = $request->file('profile_image');
            $filename = 'user-' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(storage_path('app/public/user/'), $filename);
            $data['profile_pic'] = $filename;
        }

        $update = User::where('id',Auth::user()->id)->update($data);

        if($update){
            return redirect('user-profile');
        }
    }

    public function getAppointmentData(Request $request){
        $getAppointment = ServiceAppoinment::where('id',$request['id'])->first();
        if(!empty($getAppointment)){

            $getAppointment['service_image'] = URL::to('storage/app/public/service/'.@$getAppointment['serviceDetails']['image']);
        }

        if (!empty($getAppointment) > 0) {
            return ['status' => 'true', 'data' => $getAppointment];
        } else {
            return ['status' => 'false', 'data' => []];
        }
    }

    public function cancelAppontment(Request $request){
        $data = $request->all();
        $appointment_id = $request['appointment_id'];
        $message = $request['cancel_reason'];

        $serviceAppointment =ServiceAppoinment::where('id',$appointment_id)->first();

        $updateAppointment = ServiceAppoinment::where('id',$appointment_id)->update(['cancel_reson'=>$message,'status'=>'cancel']);

        if($updateAppointment){

            $payment = PaymentMethodInfo::where('appoinment_id',$appointment_id)->first();
            if($payment['payment_method'] == 'stripe' || $payment['payment_method'] == 'klarna'){
                $stripe = new \Stripe\StripeClient('sk_test_hHiwaXoEWblwzGpUgMsR9W0M00GwOAS2NB');
                if($payment['payment_method'] == 'stripe') {
                    $refund = $stripe->refunds->create([
                        'charge' => $payment['payment_id'],
                    ]);
                    $updatePayment = PaymentMethodInfo::where('id',$payment['id'])->update(['refund_id'=>$refund['id'],'status'=>'refund']);
                } elseif ($payment['payment_method'] == 'klarna'){
                    $refund = $stripe->refunds->create([
                        'payment_intent' => $payment['payment_id'],
                    ]);
                    $updatePayment = PaymentMethodInfo::where('id',$payment['id'])->update(['refund_id'=>$refund['id'],'status'=>'refund']);
                }



            }

            return redirect('user-profile');

        }
    }

    public function submitRating(Request $request){
        $data = $request->all();
        
        $rating = $data['service_rate'] + $data['ambiente'] + $data['preie_leistungs_rate'] + $data['wartezeit'] + $data['atmosphare'];
        $data['total_avg_rating'] = number_format($rating / 5, 1);
        $data['user_id'] = Auth::user()->id;

        $finalRating = new StoreRatingReview();
        $finalRating->fill($data);
        if($finalRating->save()){
            $store = StoreProfile::where('id', $data['store_id'])->first();

            return redirect('cosmetic/'.$store['slug']);
        }

    }
}
