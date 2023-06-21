<?php

namespace App\Http\Controllers\Api\User\PaymentDetails;

use App\Http\Controllers\Controller;
use App\Models\Payment as Payments;
use App\Models\PaymentMethodInfo;
use App\Models\Service;
use App\Models\ServiceAppoinment;
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
use URL;
use Validator;

class PaymentController extends Controller
{
    public function withdrawPayment(Request $request)
    {

        $data = $request->all();
        // $stripe = new \Stripe\StripeClient(
        //         'sk_test_hHiwaXoEWblwzGpUgMsR9W0M00GwOAS2NB'
        //       );;
        //     $token = $stripe->tokens->create([
        //         'card' => [
        //             'number' => $data['card_number'],
        //             'exp_month' => $data['ex_month'],
        //             'exp_year' => $data['ex_year'],
        //             'cvc' => $data['cvv']
        //         ],
        //     ]);
        //     if (!isset($token['id'])) {
        //         return response()->json(['ResponseCode' => 0, 'ResponseText' => 'Something went wrong', 'ResponseData' => null], 400);
        //     }
        //     $token = $token['id'];
        //     dd($token);
        
        if ($data['payment_method'] == 'stripe') {
            $rule = [
                'payment_method' => 'required',
                'card_number' => 'required|min:16',
                'card_holder_name' => 'required',
                'card_holder_email' => 'required',
                'cvv' => 'required',
                'postal_code' => 'required',
                'ex_month' => 'required',
                'ex_year' => 'required',
                'stripeToken'=>'required'
            ];
            $message = [
                'payment_method.required' => 'payment_method is required',
                'card_number.required' => 'Card Number is required',
                'card_number.min' => 'card number Must be 16 Characters',
                'card_holder_name.required' => 'card holder name is required',
                'cvv.required' => 'cvv is required',
                'postal_code.required' => 'postal code is required',
                'ex_month.required' => 'ex date is required',
                'ex_year.required' => 'ex year is required',
                'stripeToken.required' => 'stripe token is required',
            ];
            $validate = Validator::make($request->all(), $rule, $message);

            if ($validate->fails()) {
                return response()->json(['ResponseCode' => 0, 'ResponseText' => 'Validation Fails', 'ResponseData' => $validate->errors()->all()], 422);
            }
        }
        $serviceDetails = Service::where('id', $data['service_id'])->first();

        $amount = \BaseFunction::finalPrice($serviceDetails['id']);
        
        $userDetails = User::with('userAddress')->where('id',$data['user']['user_id'])->first();
        
        if ($data['payment_method'] == 'stripe') {            
            $stripe = new \Stripe\StripeClient('sk_test_hHiwaXoEWblwzGpUgMsR9W0M00GwOAS2NB');

            if ($userDetails) {
                if ($userDetails->customer_id == '') {
                    $customer = $stripe->customers->create([
                        'name' => $data['card_holder_name'],
                        'email' => $data['card_holder_email'],
                        'address' => [
                            'line1' => $userDetails['userAddress'] == null ? null :$userDetails['userAddress']['address'] 
                        ]
                    ]);
                    $update = User::where('id', $userDetails->id)->update(['customer_id' => $customer['id']]);
                    $c_id = $customer['id'];
                } else {
                    $c_id = $userDetails->customer_id;
                }
            } else {
                $$customer = $stripe->customers->create([
                    'name' => $data['card_holder_name'],
                    'email' => $data['card_holder_email']
                ]);
                $c_id = $customer['id'];
            }


            $card = $stripe->customers->createSource(
                $c_id,
                ['source' => $data['stripeToken']]
            );

            $charge = $stripe->charges->create([
                'amount' => $amount * 100,
                'currency' => 'eur',
                'customer' => $c_id,
                'description' => 'R4U ' . $data['service_name'],
            ]);

            $charge_id = $charge['id'];
            $payment_method = 'stripe';

        }
        elseif ($data['payment_method'] == 'paypal') {
            $charge_id = '';
            $payment_method = 'paypal';            
        }
        elseif ($data['payment_method'] == 'cash') {

            $charge_id = 'Cash';
            $payment_method = 'cash';
        }
        elseif ($data['payment_method'] == 'applepay') {
            $charge_id = '';
            $payment_method = 'applepay';

        }
        elseif ($data['payment_method'] == 'klarna') {
            $stripe = new \Stripe\StripeClient('sk_test_hHiwaXoEWblwzGpUgMsR9W0M00GwOAS2NB');
            
            if ($userDetails) {
                if ($userDetails->customer_id == '') {
                    $customer = $stripe->customers->create([
                        'name' => $userDetails['first_name'] == null ? null :$userDetails['first_name'],
                        'email' => $userDetails['last_name'] == null ? null :$userDetails['last_name'],
                        'address' => [
                            'line1' => $userDetails['address'] == null ? null :$userDetails['address'] 
                        ]
                    ]);
                    $update = User::where('id', $userDetails->id)->update(['customer_id' => $customer['id']]);
                    $c_id = $customer['id'];
                } else {
                    $c_id = $userDetails->customer_id;
                    
                }
            } else {            
                $$customer = $stripe->customers->create([
                    'name' => $userDetails['first_name'] == null ? null :$userDetails['first_name'],
                    'email' => $userDetails['last_name'] == null ? null :$userDetails['last_name'],
                ]);
                $c_id = $customer['id'];
            }
            $redirectUrl = URL::to('api/v1/user/karla-payment-success');
            
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
        $appointmentData['store_emp_id'] = $data['emp_id'];
        $appointmentData['service_id'] = $serviceDetails['id'];
        $appointmentData['service_name'] = $serviceDetails['service_name'];
        $appointmentData['appo_date'] = \Carbon\Carbon::parse($data['date']);
        $appointmentData['appo_time'] = \Carbon\Carbon::parse($data['time'])->format('H:i:s');
        $appointmentData['status'] = 'booked';
        $appointmentData['order_id'] = \BaseFunction::orderNumber();
        $appointmentData['price'] = $amount;
        
        if ($userDetails['user_type'] == 'guest') {
            $getEmail = User::where('email',$userDetails['email'])->first();            
            if(!empty($getEmail)){
                $appointmentData['user_id'] = $getEmail['id'];
            }            
            $appointmentData['first_name'] = $userDetails['first_name'];
            $appointmentData['last_name'] = $userDetails['last_name'];
            $appointmentData['email'] = $userDetails['email']; 
            $appointmentData['phone_number'] = $userDetails['phone_number'];
        } else {
            $appointmentData['user_id'] = $data['user']['user_id'];
        }
        $appointmentData['appointment_type'] = 'system';

        $appointment = new ServiceAppoinment();
        $appointment->fill($appointmentData);
        if ($appointment->save()) {            
            if ($userDetails['user_type'] != 'guest') {
                $paymentinfo['user_id'] = $data['user']['user_id'];
            }
            $paymentinfo['store_id'] = $appointment['store_id'];
            $paymentinfo['service_id'] = $appointment['service_id'];
            $paymentinfo['order_id'] = $appointment['order_id'];
            $paymentinfo['payment_id'] = $charge_id;
            $paymentinfo['total_amount'] = $appointment['price'];
            if ($data['payment_method'] == 'stripe') {
                $paymentinfo['status'] = 'succeeded';
            } elseif ($data['payment_method'] == 'paypal') {
                $paymentinfo['status'] = 'pending';
            } elseif ($data['payment_method'] == 'cash') {
                $paymentinfo['status'] = 'succeeded';
            } elseif ($data['payment_method'] == 'klarna') {
                $paymentinfo['status'] = 'pending';
            }

            $paymentinfo['appoinment_id'] = $appointment['id'];
            $paymentinfo['payment_method'] = $payment_method;
            $paymentinfo['payment_type'] = 'withdrawn';

            $paymentDatas = new PaymentMethodInfo();
            $paymentDatas->fill($paymentinfo);
            $paymentDatas->save();

            if ($data['payment_method'] == 'klarna') {
                
                if ($intent->status == 'requires_action' && $intent->next_action->type == 'redirect_to_url') {

                    if ($userDetails['usertype'] == 'guest') {
                        $mailName = $userDetails['first_name'] . ' ' . $userDetails['last_name'];
                        $mailemail = $userDetails['email'];
                    } else {
                        $mailName = $userDetails['first_name'] . ' ' . $userDetails['last_name'];
                        $mailemail = $userDetails['email'];
                    }

                    // $title = 'New Appointment Booked';
                    // $title1 = 'Your Appointment has been booked';
                    // $data_mail = ['title' => $title, 'title1' => $title1, 'email' => $mailemail, 'name' => $mailName];

                    // Session::put('payment_id', $intent['id']);
                    // Session::put('appointmentId', $appointment['id']);
                    // Session::put('datamail', $data_mail);
                    // Session::put('store_id', $appointment['store_id']);
                    $message = 'Your Appointment has been booked';
                    $url = $intent->next_action->redirect_to_url->url;
                    $data = [
                        'redirect_to_url' =>$url,
                        'appoinment_id'=>$appointment['id']
                    ];
                    // return redirect($url);
                    return response()->json(['ResponseCode' => 1, 'ResponseText' => $message, 'ResponseData' => $data], 200);

                } else {
                    // $slug = \BaseFunction::getSlug($appointment['store_id']);
                    // Session::put('payment_status', 'failed');
                    // return redirect('cosmetic/' . $slug);
                    $message = 'Your Appointment has been failed';
                    return response()->json(['ResponseCode' => 1, 'ResponseText' => $message, 'ResponseData' => NULL], 200);
                }
            }
            if ($data['payment_method'] == 'paypal') {

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
                $redirectUrls->setReturnUrl(URL::to('api/v1/user/paypal/payment/success'))
                    ->setCancelUrl(URL::to('api/v1/user/paypal/cancel'));

                $payment = new Payment();
                $payment->setIntent('sale')
                    ->setPayer($payer)
                    ->setTransactions(array($transaction))
                    ->setRedirectUrls($redirectUrls);

                if ($userDetails['user_type'] == 'guest') {
                    $mailName = $userDetails['first_name'] . ' ' . $userDetails['last_name'];
                    $mailemail = $userDetails['email'];
                } else {
                    $mailName = $userDetails['first_name'] . ' ' . $userDetails['last_name'];
                    $mailemail = $userDetails['email'];
                }
                try {
                    $title = 'New Appointment Booked';
                    $title1 = 'Your Appointment has been booked';
                    $data_mail = ['title' => $title, 'title1' => $title1, 'email' => $mailemail, 'name' => $mailName];

                   //                     $payment->create($apiContext);
                    $payment->create($this->_api_context);
                    PaymentMethodInfo::where('id', $paymentDatas->id)->update(['payment_id' => $payment->getId()]);
                    $message = 'Your Appointment has been booked';
                    $data = [
                        'redirect_to_url' =>$payment->getApprovalLink(),                        
                        // 'paypal_payment_id'=> $payment->getId(),
                        'appointmentId'=>$appointment['id'],
                        'datamail'=>$data_mail,
                        'store_id'=>$appointment['store_id']
                    ];
                    // dd($data);
                    // return redirect($url);
                    return response()->json(['ResponseCode' => 1, 'ResponseText' => $message, 'ResponseData' => $data], 200);

                    return redirect($payment->getApprovalLink());
                } catch (PayPalConnectionException $ex) {

                    $slug = \BaseFunction::getSlug($appointment['store_id']);
                    Session::put('payment_status', 'failed');
                    return redirect('cosmetic/' . $slug);
                }

            } 
            if ($data['payment_method'] == 'cash') {
                return response()->json(['ResponseCode' => 1, 'ResponseText' => 'Your Payment has been Successful!', 'ResponseData' => 'succeeded'], 200);
            }
            if ($data['payment_method'] == 'stripe') {
                if ($charge['status'] == 'succeeded') {
                    return response()->json(['ResponseCode' => 1, 'ResponseText' => 'Your Payment has been Successful!', 'ResponseData' => true], 200);
                }
                return response()->json(['ResponseCode' => 0, 'ResponseText' => 'Your Payment has Failed!', 'ResponseData' => false], 200);
            }
            else {                
                return response()->json(['ResponseCode' => 1, 'ResponseText' => 'Your Payment has been Successful!', 'ResponseData' => true], 200);
                // if ($charge['status'] == 'succeeded') {
                // }
                // return response()->json(['ResponseCode' => 0, 'ResponseText' => 'Your Payment has Failed!', 'ResponseData' => false], 200);                              
            }


        }


    }

    public function success(Request $request)
    {        
        $this->_api_context = new ApiContext(new OAuthTokenCredential(
            'AVW3rqhQztKPMYoPPY6FlbN94RC9jT7_9qyOKD5_EZ0vxccekchb-SAm-3EsEJDYrwIbTOu9OzRTNraz',
            'ECcTdZ_KybPvviJL9GJjEBFW7ixio2MhV-eKZuUkssk3Zf0u-bbNfJ6j6Qk8YvNqgn0TK4G3of6yNZpC'));
            
            $payment_id = request('paymentId');
            $oredr_number = request('appointmentId');
            $data_mail = request('datamail');
            $store = request('store_id');            
        
        $slug = \BaseFunction::getSlug($store);

        if (empty($request->PayerID) || empty($request->token) || empty($payment_id) || empty($oredr_number)) {
            return response()->json(['ResponseCode' => 0, 'ResponseText' => 'Invalid request.!', 'ResponseData' => null], 400);
            // return back()->with('errors', ['Invalid request.']);
        }

        $payment = Payment::get($payment_id, $this->_api_context);

        $execution = new PaymentExecution();
        $execution->setPayerId($request->PayerID);

        $result = $payment->execute($execution, $this->_api_context);

        if ($result->getState() == 'approved') {
            PaymentMethodInfo::where('appoinment_id', $oredr_number)->where('payment_id', $payment_id)->update(['status' => 'succeeded']);
            // Session::put('payment_status', 'success');
            $status = "succeeded";
        } else {
            PaymentMethodInfo::where('appoinment_id', $oredr_number)->where('payment_id', $payment_id)->update(['status' => 'failed']);
            $status = "failed";
            // Session::put('payment_status', 'failed');
        }

        try {
            //            Mail::send('email.report_user', $data_mail, function ($message) use ($data_mail) {
            //                $message->from('info@burrardpharma.com', "Burrard Pharma")->subject($data_mail['title1']);
            //                $message->to($data_mail['email']);
            //            });

            return response()->json(['ResponseCode' => 1, 'ResponseText' => 'Your Payment has been Successful!', 'ResponseData' => $status], 200);
        } catch (\Swift_TransportException $e) {
            \Log::debug($e);
            return response()->json(['ResponseCode' => 0, 'ResponseText' => 'Something went wrong', 'ResponseData' => null], 400);
        }
    }

    public function cancel()
    {
        return response()->json(['ResponseCode' => 0, 'ResponseText' => 'Your Payment has been Cancled!', 'ResponseData' => ''], 200);
        // $store = Session::get('store_id');

        // Session::forget('paypal_payment_id');
        // Session::forget('orderNumber');
        // Session::forget('datamail');
        // Session::forget('store_id');

        // $slug = \BaseFunction::getSlug($store);

        // Session::put('payment_status', 'failed');
        // return redirect('cosmetic/' . $slug);
    }

    public function klarnaSuccess(Request $request)
    {

        $data = $request->all();         
        // dd($data['payment_intent']);       
        // $payment_id = Session::get('payment_id');
        // $oredr_number = Session::get('appointmentId');
        // $data_mail = Session::get('datamail');
        // $store = Session::get('store_id');

        // Session::forget('payment_id');
        // Session::forget('orderNumber');
        // Session::forget('datamail');
        // Session::forget('store_id');

        // $slug = \BaseFunction::getSlug($store);

        if ($data['redirect_status'] == 'succeeded') {
            PaymentMethodInfo::where('appoinment_id',$data['appoinment_id'])->where('payment_id', $data['payment_intent'])->update(['status' => 'succeeded']);
            $status = $data['redirect_status'];
        } else {
            PaymentMethodInfo::where('appoinment_id',$data['appoinment_id'])->where('payment_id', $data['payment_intent'])->update(['status' => 'failed']);
            $status = $data['redirect_status'];
        }

        try {
            //Mail::send('email.report_user', $data_mail, function ($message) use ($data_mail) {
            //   $message->from('info@burrardpharma.com', "Burrard Pharma")->subject($data_mail['title1']);
            //   $message->to($data_mail['email']);
            // });
            if ($status == 'succeeded') {                
                return response()->json(['ResponseCode' => 1, 'ResponseText' => 'Your Payment has been Successful!', 'ResponseData' => $status], 200);
            }
            return response()->json(['ResponseCode' => 0, 'ResponseText' => 'Your Payment has Failed!', 'ResponseData' => $status], 200);

        } catch (\Swift_TransportException $e) {
            \Log::debug($e);
            return response()->json(['ResponseCode' => 0, 'ResponseText' => 'Something went wrong', 'ResponseData' => null], 400);
        }

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
