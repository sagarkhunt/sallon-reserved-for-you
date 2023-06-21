<?php

namespace App\Http\Controllers\ServiceProvider;

use App\Http\Controllers\Controller;
use App\Models\PaymentMethodInfo;
use App\Models\Service;
use App\Models\ServiceAppoinment;
use App\Models\StoreProfile;
use Illuminate\Http\Request;
use Auth;
use URL;

class AppointmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $store_id = session('store_id');

        if (empty($store_id)) {
            $getStore = StoreProfile::where('user_id', Auth::user()->id)->pluck('id')->all();
        } else {
            $getStore = StoreProfile::where('user_id', Auth::user()->id)->where('id', $store_id)->pluck('id')->all();
        }

        $date = \Carbon\Carbon::now()->format('Y-m-d');

        $order = ServiceAppoinment::whereIn('store_id', $getStore)
            ->whereDate('appo_date', '>=', $date)
            ->WhereNotIn('status', ['cancel', 'completed'])
            ->orderBy('appo_date', 'DESC')->get();
        $calander = [];
        foreach ($order as $row) {
            $row->payment_method = PaymentMethodInfo::where('appoinment_id', $row->id)->value('payment_method');
            $calander[] = array('title'=>$row->service_name,'start'=>date('Y-m-d H:i:s', strtotime("$row->appo_date  $row->appo_time")));
        }
        $pastOrder = ServiceAppoinment::whereIn('store_id', $getStore)
            ->where(function ($query) use ($date) {
                $query->wheredate('appo_date', '<', $date)
                    ->orWhereIn('status', ['cancel', 'completed']);
            })->orderBy('appo_date', 'DESC')->get();
        $oCalander = [];
        foreach ($pastOrder as $row) {
            $row->payment_method = PaymentMethodInfo::where('appoinment_id', $row->id)->value('payment_method');
            $oCalander[] = array('title'=>$row->service_name,'start'=>date('Y-m-d H:i:s', strtotime("$row->appo_date  $row->appo_time")));
        }

        $service = Service::whereIn('store_id', $getStore)->where('status', 'active')->get();

        return view('ServiceProvider.Appointment.index', compact('order', 'pastOrder', 'service','oCalander','calander'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createAppointment(Request $request)
    {

        $data = $request->all();
        $serviceDetails = Service::where('id', $data['choose_service'])->first();
        $amount = \BaseFunction::finalPrice($serviceDetails['id']);

        $appointmentData['store_id'] = $serviceDetails['store_id'];
        $appointmentData['store_emp_id'] = $data['choose_expert'];
        $appointmentData['service_id'] = $serviceDetails['id'];
        $appointmentData['service_name'] = $serviceDetails['service_name'];
        $appointmentData['appo_date'] = \Carbon\Carbon::parse($data['date']);
        $appointmentData['appo_time'] = \Carbon\Carbon::parse($data['service_time'])->format('H:i');
        $appointmentData['status'] = 'booked';
        $appointmentData['order_id'] = \BaseFunction::orderNumber();
        $appointmentData['price'] = $amount;
        $appointmentData['appointment_type'] = 'service provider';
        $appointmentData['first_name'] = $data['first_name'];
        $appointmentData['last_name'] = $data['last_name'];
        $appointmentData['email'] = $data['email'];
        $appointmentData['phone_number'] = $data['phone_number'];

        $appointment = new ServiceAppoinment();
        $appointment->fill($appointmentData);
        if ($appointment->save()) {
            return redirect('service-provider/appointment');
        }
    }

    public function getAppointmentData(Request $request)
    {
        $data = $request->all();

        $appointment = ServiceAppoinment::where('id', $data['appointment_data'])->first();
        $appointment['emp_name'] = $appointment['employeeDetails']['emp_name'];
        if (!empty($appointment['user_id'])) {
            $appointment['user_name'] = @$appointment['userDetails']['first_name'] . ' ' . @$appointment['userDetails']['last_name'];
        } else {
            $appointment['user_name'] = @$appointment['first_name'] . ' ' . @$appointment['last_name'];
        }
        $appointment['service_image'] = URL::to('storage/app/public/service/' . $appointment['serviceDetails']['image']);

        if (!empty($appointment)) {
            return ['status' => 'true', 'data' => $appointment];
        } else {
            return ['status' => 'false', 'data' => []];
        }
    }

    public function postpondAppointment(Request $request)
    {
        $id = $request['appointment_id'];
        $date = $request['date'];
        $time = $request['time'];

        $appointmentData['appo_date'] = \Carbon\Carbon::parse($date);
        $appointmentData['appo_time'] = \Carbon\Carbon::parse($time)->format('H:i');
        $appointmentData['status'] = 'reschedule';

        $appointment = ServiceAppoinment::where('id', $id)->update($appointmentData);
        if ($appointment) {
            return ['status' => 'true', 'data' => null];
        } else {
            return ['status' => 'false', 'data' => null];
        }
    }

    public function cancelAppointment(Request $request)
    {
        $data = $request->all();

        $appointment_id = $request['appointment_id'];
        $message = $request['reason'];

        $serviceAppointment = ServiceAppoinment::where('id', $appointment_id)->first();

        $updateAppointment = ServiceAppoinment::where('id', $appointment_id)->update(['cancel_reson' => $message, 'status' => 'cancel']);

        if ($updateAppointment) {

            $payment = PaymentMethodInfo::where('appoinment_id', $appointment_id)->first();
            if (!empty($payment)) {
                if ($payment['payment_method'] == 'stripe' || $payment['payment_method'] == 'klarna') {
                    $stripe = new \Stripe\StripeClient('sk_test_hHiwaXoEWblwzGpUgMsR9W0M00GwOAS2NB');
                    if ($payment['payment_method'] == 'stripe') {
                        $refund = $stripe->refunds->create([
                            'charge' => $payment['payment_id'],
                        ]);
                        $updatePayment = PaymentMethodInfo::where('id', $payment['id'])->update(['refund_id' => $refund['id'], 'status' => 'refund']);
                    } elseif ($payment['payment_method'] == 'klarna') {
                        $refund = $stripe->refunds->create([
                            'payment_intent' => $payment['payment_id'],
                        ]);
                        $updatePayment = PaymentMethodInfo::where('id', $payment['id'])->update(['refund_id' => $refund['id'], 'status' => 'refund']);
                    }
                }
            }

            return redirect('service-provider/appointment');
        }
    }

    public function searchAppointment(Request $request)
    {
        $search = $request['search'];

        $store_id = session('store_id');
        $date = \Carbon\Carbon::now()->format('Y-m-d');
        if (empty($store_id)) {
            $getStore = StoreProfile::where('user_id', Auth::user()->id)->pluck('id')->all();
        } else {
            $getStore = StoreProfile::where('user_id', Auth::user()->id)->where('id', $store_id)->pluck('id')->all();
        }

        $searchAppointment = ServiceAppoinment::leftjoin('services', 'services.id', '=', 'service_appoinments.service_id')
            ->leftjoin('store_emps', 'store_emps.id', '=', 'service_appoinments.store_emp_id')
            ->leftjoin('store_profiles', 'store_profiles.id', '=', 'service_appoinments.store_id')
            ->leftjoin('users', 'users.id', '=', 'service_appoinments.user_id');

        if(!empty($search)){
            $searchAppointment = $searchAppointment->where(function ($query) use ($search) {
                $query->where('services.service_name', 'LIKE', "%{$search}%")
                    ->orWhere('service_appoinments.order_id', 'LIKE', "%{$search}%")
                    ->orWhere('store_profiles.store_name', 'LIKE', "%{$search}%")
                    ->orWhere('store_emps.emp_name', 'LIKE', "%{$search}%")
                    ->orWhere('service_appoinments.status', 'LIKE', "%{$search}%")
                    ->orWhere('service_appoinments.first_name', 'LIKE', "%{$search}%")
                    ->orWhere('service_appoinments.last_name', 'LIKE', "%{$search}%")
                    ->orWhere('users.first_name', 'LIKE', "%{$search}%")
                    ->orWhere('users.last_name', 'LIKE', "%{$search}%");

            });
        }

        $searchAppointment = $searchAppointment->whereIn('service_appoinments.store_id', $getStore)
            ->wheredate('service_appoinments.appo_date', '>=', $date)
            ->WhereNotIn('service_appoinments.status', ['cancel', 'completed'])
            ->select('service_appoinments.*','store_emps.emp_name','store_profiles.store_name','users.first_name as ufname','users.last_name as ulname')
            ->orderBy('service_appoinments.appo_date', 'DESC')
            ->distinct()
            ->get();

        foreach ($searchAppointment as $row) {
            $row->categoryName = @$row->serviceDetails->CategoryData->name;
            $row->payment_method = PaymentMethodInfo::where('appoinment_id', $row->id)->value('payment_method');
            $row->appo_date = \Carbon\Carbon::parse($row->appo_date)->format('d M, Y');
            $row->appo_time = \Carbon\Carbon::parse($row->appo_time)->format('H:i');
            if(file_exists(storage_path('app/public/service/'.@$row->serviceDetails->image)) && @$row->serviceDetails->image != ''){
                    $row->service_image = URL::to('storage/app/public/service/'.@$row->serviceDetails->image);
            } else {
                $row->service_image = URL::to('storage/app/public/default/store_default.png');
            }

        }


        if (!empty($searchAppointment)) {
            return ['status' => 'true', 'data' => $searchAppointment];
        } else {
            return ['status' => 'false', 'data' => []];
        }
    }

    public function searchAppointmentRecent(Request $request)
    {
        $search = $request['search'];

        $store_id = session('store_id');
        $date = \Carbon\Carbon::now()->format('Y-m-d');
        if (empty($store_id)) {
            $getStore = StoreProfile::where('user_id', Auth::user()->id)->pluck('id')->all();
        } else {
            $getStore = StoreProfile::where('user_id', Auth::user()->id)->where('id', $store_id)->pluck('id')->all();
        }

        $searchAppointment = ServiceAppoinment::leftjoin('services', 'services.id', '=', 'service_appoinments.service_id')
            ->leftjoin('store_emps', 'store_emps.id', '=', 'service_appoinments.store_emp_id')
            ->leftjoin('store_profiles', 'store_profiles.id', '=', 'service_appoinments.store_id')
            ->leftjoin('users', 'users.id', '=', 'service_appoinments.user_id');

        if(!empty($search)){
            $searchAppointment = $searchAppointment->where(function ($query) use ($search) {
                $query->where('services.service_name', 'LIKE', "%{$search}%")
                    ->orWhere('service_appoinments.order_id', 'LIKE', "%{$search}%")
                    ->orWhere('store_profiles.store_name', 'LIKE', "%{$search}%")
                    ->orWhere('store_emps.emp_name', 'LIKE', "%{$search}%")
                    ->orWhere('service_appoinments.status', 'LIKE', "%{$search}%")
                    ->orWhere('service_appoinments.first_name', 'LIKE', "%{$search}%")
                    ->orWhere('service_appoinments.last_name', 'LIKE', "%{$search}%")
                    ->orWhere('users.first_name', 'LIKE', "%{$search}%")
                    ->orWhere('users.last_name', 'LIKE', "%{$search}%");

            });
        }

        $searchAppointment = $searchAppointment->whereIn('service_appoinments.store_id', $getStore)
            ->where(function ($query) use ($date) {
                $query->wheredate('service_appoinments.appo_date', '<', $date)
                    ->orWhereIn('service_appoinments.status', ['cancel', 'completed']);
            })
            ->select('service_appoinments.*','store_emps.emp_name','store_profiles.store_name','users.first_name as ufname','users.last_name as ulname')
            ->orderBy('service_appoinments.appo_date', 'DESC')
            ->distinct()
            ->get();

        foreach ($searchAppointment as $row) {
            $row->categoryName = @$row->serviceDetails->CategoryData->name;
            $row->payment_method = PaymentMethodInfo::where('appoinment_id', $row->id)->value('payment_method');
            $row->appo_date = \Carbon\Carbon::parse($row->appo_date)->format('d M, Y');
            $row->appo_time = \Carbon\Carbon::parse($row->appo_time)->format('H:i');
            if(file_exists(storage_path('app/public/service/'.@$row->serviceDetails->image)) && @$row->serviceDetails->image != ''){
                    $row->service_image = URL::to('storage/app/public/service/'.@$row->serviceDetails->image);
            } else {
                $row->service_image = URL::to('storage/app/public/default/store_default.png');
            }

        }


        if (!empty($searchAppointment)) {
            return ['status' => 'true', 'data' => $searchAppointment];
        } else {
            return ['status' => 'false', 'data' => []];
        }
    }
}
