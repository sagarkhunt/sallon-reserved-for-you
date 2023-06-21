<?php

namespace App\Http\Controllers\Frontend\Payment;

use App\Http\Controllers\Controller;
use App\Models\Payment as Payments;
use App\Models\PaymentMethodInfo;
use App\Models\Service;
use App\Models\ServiceAppoinment;
use App\Models\StoreEmp;
use App\Models\User;
use Illuminate\Http\Request;
use Auth;
use Session;
use Redirect;
use PayPal\Api\Amount;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Rest\ApiContext;
use PayPal\Api\Refund;
use URL;
use Mail;


class PaymentController extends Controller
{
    public function checkout(Request $request)
    {

        $data = $request->all();
        $serviceDetails = Service::where('id', $data['final_service'])->first();

        $amount = \BaseFunction::finalPrice($serviceDetails['id']);

        if ($data['payment'] == 'stripe') {
            $stripe = new \Stripe\StripeClient('sk_test_hHiwaXoEWblwzGpUgMsR9W0M00GwOAS2NB');

            if (Auth::check()) {
                if (Auth::user()->customer_id == '') {
                    $customer = $stripe->customers->create([
                        'name' => $request['name'],
                        'email' => Auth::user()->email,
                        'address' => [
                            'line1' => $request['address']
                        ]
                    ]);
                    $update = User::where('id', Auth::user()->id)->update(['customer_id' => $customer['id']]);
                    $c_id = $customer['id'];
                } else {
                    $c_id = Auth::user()->customer_id;
                }
            } else {
                $customer = $stripe->customers->create([
                    'name' => $request['name'],
                    'email' => $request['email']
                ]);
                $c_id = $customer['id'];
            }


            $card = $stripe->customers->createSource(
                $c_id,
                ['source' => $request['stripeToken']]
            );

            $charge = $stripe->charges->create([
                'amount' => $amount * 100,
                'currency' => 'eur',
                'customer' => $c_id,
                'description' => 'R4U ' . $serviceDetails['service_name'],
            ]);

            $charge_id = $charge['id'];
            $payment_method = 'stripe';

        }
        elseif ($data['payment'] == 'paypal') {
            $charge_id = '';
            $payment_method = 'paypal';

        }
        elseif ($data['payment'] == 'cash') {

            $charge_id = 'Cash';
            $payment_method = 'cash';
        }
        elseif ($data['payment'] == 'applepay') {
            $charge_id = '';
            $payment_method = 'applepay';

        }
        elseif ($data['payment'] == 'klarna') {
            $stripe = new \Stripe\StripeClient('sk_test_hHiwaXoEWblwzGpUgMsR9W0M00GwOAS2NB');

            if (Auth::check()) {
                if (Auth::user()->customer_id == '') {
                    $customer = $stripe->customers->create([
                        'name' => $request['name'],
                        'email' => Auth::user()->email,
                        'address' => [
                            'line1' => $request['address']
                        ]
                    ]);
                    $update = User::where('id', Auth::user()->id)->update(['customer_id' => $customer['id']]);
                    $c_id = $customer['id'];
                } else {
                    $c_id = Auth::user()->customer_id;
                }
            } else {
                $customer = $stripe->customers->create([
                    'name' => $request['name'],
                    'email' => $request['email']
                ]);
                $c_id = $customer['id'];
            }
            $redirectUrl = URL::to('karla-payment-success');

            $intent = $stripe->paymentIntents->create([
                'confirm' => true,
                'amount' => $amount * 100,
                'currency' => 'eur',
                'payment_method_types' => ['sofort'],
                'payment_method_data' => [
                    'type' => 'sofort',
                    'sofort' => [
                        'country' => 'DE',
                    ],
                ],
                'return_url' => $redirectUrl,
            ]);

            $charge_id = $intent['id'];
            $payment_method = 'klarna';
        }


        $appointmentData['store_id'] = $serviceDetails['store_id'];
        $appointmentData['store_emp_id'] = $data['final_employee'];
        $appointmentData['service_id'] = $serviceDetails['id'];
        $appointmentData['service_name'] = $serviceDetails['service_name'];
        $appointmentData['appo_date'] = \Carbon\Carbon::parse($data['final_date']);
        $appointmentData['appo_time'] = \Carbon\Carbon::parse($data['final_time'])->format('H:i');
        $appointmentData['status'] = 'booked';
        $appointmentData['order_id'] = \BaseFunction::orderNumber();
        $appointmentData['price'] = $amount;
        if ($data['usertype'] == 'guest') {
            $getEmail = User::where('email',$data['user_email'])->first();
            if(!empty($getEmail)){
                $appointmentData['user_id'] = $getEmail['id'];
            }
            $appointmentData['first_name'] = $data['user_f_name'];
            $appointmentData['last_name'] = $data['user_l_name'];
            $appointmentData['email'] = $data['user_email'];
            $appointmentData['phone_number'] = $data['user_phone'];
        } else {
            $appointmentData['user_id'] = Auth::user()->id;
        }
        $appointmentData['appointment_type'] = 'system';

        $appointment = new ServiceAppoinment();
        $appointment->fill($appointmentData);
        if ($appointment->save()) {

            if ($data['usertype'] != 'guest') {
                $paymentinfo['user_id'] = Auth::user()->id;
            }
            $paymentinfo['store_id'] = $appointment['store_id'];
            $paymentinfo['service_id'] = $appointment['service_id'];
            $paymentinfo['order_id'] = $appointment['order_id'];
            $paymentinfo['payment_id'] = $charge_id;
            $paymentinfo['total_amount'] = $appointment['price'];

            if ($data['payment'] == 'stripe') {
                $paymentinfo['status'] = 'succeeded';
            } elseif ($data['payment'] == 'paypal') {
                $paymentinfo['status'] = 'pending';
            } elseif ($data['payment'] == 'cash') {
                $paymentinfo['status'] = 'succeeded';
            } elseif ($data['payment'] == 'klarna') {
                $paymentinfo['status'] = 'pending';
            }

            $paymentinfo['appoinment_id'] = $appointment['id'];
            $paymentinfo['payment_method'] = $payment_method;
            $paymentinfo['payment_type'] = 'withdrawn';

            $paymentDatas = new PaymentMethodInfo();
            $paymentDatas->fill($paymentinfo);
            $paymentDatas->save();

            $paymentSuccess['service_name'] = $serviceDetails['service_name'];
            $paymentSuccess['image'] = URL::to('storage/app/public/service/' . $serviceDetails['image']);
            $paymentSuccess['store_name'] = @$appointment['storeDetaials']['store_name'];
            $paymentSuccess['emp_name'] = StoreEmp::where('id',$appointment['store_emp_id'])->value('emp_name');
            $paymentSuccess['date'] = \Carbon\Carbon::parse($appointment['appo_date'])->format('d-m-Y');
            $paymentSuccess['time'] = $appointment['appo_time'];
            $paymentSuccess['price'] = $appointment['price'];
            $paymentSuccess['order_id'] = $appointment['order_id'];
            $paymentSuccess['payment_type'] = $payment_method;

            \Session::put('payment_data', $paymentSuccess);

            if ($data['usertype'] == 'guest') {
                $mailName = $data['user_f_name'] . ' ' . $data['user_l_name'];
                $mailemail = $data['user_email'];
            } else {
                $mailName = Auth::user()->first_name . ' ' . Auth::user()->last_name;
                $mailemail = Auth::user()->email;
            }

            if ($data['payment'] == 'klarna') {
                if ($intent->status == 'requires_action' && $intent->next_action->type == 'redirect_to_url') {

                    if ($data['usertype'] == 'guest') {
                        $mailName = $data['user_f_name'] . ' ' . $data['user_l_name'];
                        $mailemail = $data['user_email'];
                    } else {
                        $mailName = Auth::user()->first_name . ' ' . Auth::user()->last_name;
                        $mailemail = Auth::user()->email;
                    }

                    $title = 'New Appointment Booked';
                    $title1 = 'Your Appointment has been booked';
                    $image = URL::to('storage/app/public/service/' . $serviceDetails['image']);
                    $service = $serviceDetails['service_name'];
                    $store = @$appointment['storeDetaials']['store_name'];
                    $expert = StoreEmp::where('id',$appointment['store_emp_id'])->value('emp_name');
                    $date =\Carbon\Carbon::parse($appointment['appo_date'])->format('d M,Y');
                    $time =$appointment['appo_time'];
                    $price =$appointment['price'];
                    $payment_type =$payment_method;
                    $order_id =$appointment['order_id'];
                    $payment_Id =$charge_id;
                    $status =$paymentDatas['status'];

                    $data_mail = ['title' => $title,'title1'=>$title1 ,'email' => $mailemail, 'name' => $mailName,'image'=>$image,'service'=>$service,'store'=>$store,'expert'=>$expert,
                        'date'=>$date,'time'=>$time,'price'=>$price,'payment_type'=>$payment_type,'order_id'=>$order_id,'payment_id'=>$payment_Id,'status'=>$status];

                    Session::put('payment_id', $intent['id']);
                    Session::put('appointmentId', $appointment['id']);
                    Session::put('datamail', $data_mail);
                    Session::put('store_id', $appointment['store_id']);

                    $url = $intent->next_action->redirect_to_url->url;
                    return redirect($url);
                } else {
                    $slug = \BaseFunction::getSlug($appointment['store_id']);
                    Session::put('payment_status', 'failed');
                    return redirect('cosmetic/' . $slug);
                }
            }
            if ($data['payment'] == 'paypal') {
                $pdata[0] = new Item();
                $pdata[0]->setName($appointment['service_name'])/** item name **/
                ->setCurrency('EUR')
                    ->setQuantity(1)
                    ->setPrice($appointment['price']);

                $this->_api_context = new ApiContext(new OAuthTokenCredential(
                    'AVW3rqhQztKPMYoPPY6FlbN94RC9jT7_9qyOKD5_EZ0vxccekchb-SAm-3EsEJDYrwIbTOu9OzRTNraz',
                    'ECcTdZ_KybPvviJL9GJjEBFW7ixio2MhV-eKZuUkssk3Zf0u-bbNfJ6j6Qk8YvNqgn0TK4G3of6yNZpC'));

                $this->_api_context->setConfig(
                    array(
                        'log.LogEnabled' => true,
                        'log.FileName' => 'PayPal.log',
                        'log.LogLevel' => 'DEBUG',
                        'mode' => 'sandbox'
                    )
                );
                $item_list = new ItemList();
                $item_list->setItems($pdata);

                $payer = new Payer();
                $payer->setPaymentMethod('paypal');

                $amount = new Amount();
                $amount->setTotal($appointment['price']);
                //                 $amount->setTotal(2);
                $amount->setCurrency('EUR');

                $transaction = new Transaction();
                $transaction->setAmount($amount)
                    ->setItemList($item_list)
                    ->setDescription('Item Description');

                $redirectUrls = new RedirectUrls();
                $redirectUrls->setReturnUrl(route('payment.success'))
                    ->setCancelUrl(route('payment.cancel'));

                $payment = new Payment();
                $payment->setIntent('sale')
                    ->setPayer($payer)
                    ->setTransactions(array($transaction))
                    ->setRedirectUrls($redirectUrls);


                try {
                    $title = 'New Appointment Booked';
                    $title1 = 'Your Appointment has been booked';
                    $image = URL::to('storage/app/public/service/' . $serviceDetails['image']);
                    $service = $serviceDetails['service_name'];
                    $store = @$appointment['storeDetaials']['store_name'];
                    $expert = StoreEmp::where('id',$appointment['store_emp_id'])->value('emp_name');
                    $date =\Carbon\Carbon::parse($appointment['appo_date'])->format('d M,Y');
                    $time =$appointment['appo_time'];
                    $price =$appointment['price'];
                    $payment_type =$payment_method;
                    $order_id =$appointment['order_id'];
                    $payment_Id =$charge_id;
                    $status =$paymentDatas['status'];

                    $data_mail = ['title' => $title,'title1'=>$title1 ,'email' => $mailemail, 'name' => $mailName,'image'=>$image,'service'=>$service,'store'=>$store,'expert'=>$expert,
                        'date'=>$date,'time'=>$time,'price'=>$price,'payment_type'=>$payment_type,'order_id'=>$order_id,'payment_id'=>$payment_Id,'status'=>$status];
                   //                     $payment->create($apiContext);
                    $payment->create($this->_api_context);
                    Session::put('paypal_payment_id', $payment->getId());
                    Session::put('appointmentId', $appointment['id']);
                    Session::put('datamail', $data_mail);
                    Session::put('store_id', $appointment['store_id']);
                    PaymentMethodInfo::where('id', $paymentDatas->id)->update(['payment_id' => $payment->getId()]);

                    return redirect($payment->getApprovalLink());
                } catch (PayPalConnectionException $ex) {

                    $slug = \BaseFunction::getSlug($appointment['store_id']);
                    Session::put('payment_status', 'failed');
                    return redirect('cosmetic/' . $slug);
                }

            }
            else {

                \Session::put('payment_status', 'success');

                $slug = \BaseFunction::getSlug($appointment['store_id']);

                $title = 'New Appointment Booked';
                $title1 = 'Your Appointment has been booked';
                $image = URL::to('storage/app/public/service/' . $serviceDetails['image']);
                $service = $serviceDetails['service_name'];
                $store = @$appointment['storeDetaials']['store_name'];
                $expert = StoreEmp::where('id',$appointment['store_emp_id'])->value('emp_name');
                $date =\Carbon\Carbon::parse($appointment['appo_date'])->format('d M,Y');
                $time =$appointment['appo_time'];
                $price =$appointment['price'];
                $payment_type =$payment_method;
                $order_id =$appointment['order_id'];
                $payment_Id =$charge_id;
                $status =$paymentDatas['status'];

                $data_mail = ['title' => $title, 'email' => $mailemail, 'name' => $mailName,'image'=>$image,'service'=>$service,'store'=>$store,'expert'=>$expert,
                'date'=>$date,'time'=>$time,'price'=>$price,'payment_type'=>$payment_type,'order_id'=>$order_id,'payment_id'=>$payment_Id,'status'=>$status];
                if(!empty($data_mail['email'])){
                    try {
                        Mail::send('email.booking', $data_mail, function ($message) use ($data_mail) {
                            $message->from('punit.radhe@gmail.com', env('MAIL_FROM_NAME',"Reserved4you"))->subject($data_mail['title']);
                            $message->to($data_mail['email']);
                        });
                    } catch (\Swift_TransportException $e) {
                        \Log::debug($e);
                    }
                }



                return redirect('cosmetic/' . $slug);

            }


        }


    }

    public function success(Request $request)
    {
        $this->_api_context = new ApiContext(new OAuthTokenCredential(
            'AVW3rqhQztKPMYoPPY6FlbN94RC9jT7_9qyOKD5_EZ0vxccekchb-SAm-3EsEJDYrwIbTOu9OzRTNraz',
            'ECcTdZ_KybPvviJL9GJjEBFW7ixio2MhV-eKZuUkssk3Zf0u-bbNfJ6j6Qk8YvNqgn0TK4G3of6yNZpC'));

        $payment_id = Session::get('paypal_payment_id');
        $oredr_number = Session::get('appointmentId');
        $data_mail = Session::get('datamail');
        $store = Session::get('store_id');

        Session::forget('paypal_payment_id');
        Session::forget('orderNumber');
        Session::forget('datamail');
        Session::forget('store_id');

        $slug = \BaseFunction::getSlug($store);

        if (empty($request->PayerID) || empty($request->token) || empty($payment_id) || empty($oredr_number)) {
            return back()->with('errors', ['Invalid request.']);
        }

        $payment = Payment::get($payment_id, $this->_api_context);

        $execution = new PaymentExecution();
        $execution->setPayerId($request->PayerID);

        $result = $payment->execute($execution, $this->_api_context);

        if ($result->getState() == 'approved') {
            PaymentMethodInfo::where('appoinment_id', $oredr_number)->where('payment_id', $payment_id)->update(['status' => 'succeeded']);
            Session::put('payment_status', 'success');
        } else {
            PaymentMethodInfo::where('appoinment_id', $oredr_number)->where('payment_id', $payment_id)->update(['status' => 'failed']);
            Session::put('payment_status', 'failed');
        }

        try {

            if(!empty($data_mail['email'])) {
                $data_mail['status'] = 'succeeded';
                Mail::send('email.booking', $data_mail, function ($message) use ($data_mail) {
                    $message->from('punit.radhe@gmail.com', env('MAIL_FROM_NAME', "Reserved4you"))->subject($data_mail['title']);
                    $message->to($data_mail['email']);
                });
            }

            return redirect('cosmetic/' . $slug);
        } catch (\Swift_TransportException $e) {
            \Log::debug($e);
//            return redirect('payment-sucess')->with('success', ['Payment Successful.']);
            return redirect('cosmetic/' . $slug);
        }
    }

    public function cancel()
    {
        $store = Session::get('store_id');

        Session::forget('paypal_payment_id');
        Session::forget('orderNumber');
        Session::forget('datamail');
        Session::forget('store_id');

        $slug = \BaseFunction::getSlug($store);

        Session::put('payment_status', 'failed');
        return redirect('cosmetic/' . $slug);
    }

    public function Karlasuccess(Request $request)
    {

        $data = $request->all();

        $payment_id = Session::get('payment_id');
        $oredr_number = Session::get('appointmentId');
        $data_mail = Session::get('datamail');
        $store = Session::get('store_id');

        Session::forget('payment_id');
        Session::forget('orderNumber');
        Session::forget('datamail');
        Session::forget('store_id');

        $slug = \BaseFunction::getSlug($store);

        if ($data['redirect_status'] == 'succeeded') {
            PaymentMethodInfo::where('appoinment_id', $oredr_number)->where('payment_id', $payment_id)->update(['status' => 'succeeded']);
            Session::put('payment_status', 'success');
        } else {
            PaymentMethodInfo::where('appoinment_id', $oredr_number)->where('payment_id', $payment_id)->update(['status' => 'failed']);
            Session::put('payment_status', 'failed');
        }

        try {

            if(!empty($data_mail['email'])) {
                $data_mail['status'] = 'succeeded';
                Mail::send('email.booking', $data_mail, function ($message) use ($data_mail) {
                    $message->from('punit.radhe@gmail.com', env('MAIL_FROM_NAME', "Reserved4you"))->subject($data_mail['title']);
                    $message->to($data_mail['email']);
                });
            }
            return redirect('cosmetic/' . $slug);
        } catch (\Swift_TransportException $e) {
            \Log::debug($e);
//            return redirect('payment-sucess')->with('success', ['Payment Successful.']);
            return redirect('cosmetic/' . $slug);
        }

    }

    public function Klarna()
    {
        return view('Front.test');
    }

    public function applePay(){
        $stripe = new \Stripe\StripeClient('sk_test_hHiwaXoEWblwzGpUgMsR9W0M00GwOAS2NB');

        $intent = $stripe->paymentIntents->create([
            'amount' => 1099,
            'currency' => 'eur',
            // Verify your integration in this guide by including this parameter
            'metadata' => ['integration_check' => 'accept_a_payment'],
        ]);
        dd($intent);

    }


}
