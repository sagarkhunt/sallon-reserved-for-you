<?php

return [
	'image' => [
		'emp_image' => 'app/public/storeEmp/',
	],	
	'sendboxDetails' =>[
		'mode'    => env('PAYPAL_MODE', 'sandbox'), // Can only be 'sandbox' Or 'live'. If empty or invalid, 'live' will be used.
		'sandbox' => [
			'username'    => env('PAYPAL_SANDBOX_API_USERNAME'),
			'password'    => env('PAYPAL_SANDBOX_API_PASSWORD'),
			'secret'      => env('PAYPAL_SANDBOX_API_SECRET'),
			'certificate' => env('PAYPAL_SANDBOX_API_CERTIFICATE', ''),
			'app_id'      => '4EVRVLWJJVDM2', // Used for testing Adaptive Payments API in sandbox mode
		],                
		'payment_action' => 'Sale', // Can only be 'Sale', 'Authorization' or 'Order'
		'currency'       => env('PAYPAL_CURRENCY', 'USD'),
		'billing_type'   => 'MerchantInitiatedBilling',
		'notify_url'     => '', // Change this accordingly for your application.
		'locale'         => '', // force gateway language  i.e. it_IT, es_ES, en_US ... (for express checkout only)
		'validate_ssl'   => true, // Validate SSL when creating api client.
	],
	'project_mode' => 'production',   // production, live
	'commission' => 5.00,
];