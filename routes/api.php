<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
/**
 * Customer route.
 */
Route::group(['namespace' => 'Api\User','prefix'=>'v1/user/'],function(){
    Route::group(['namespace' => 'Auth'], function () {
        Route::post('guest-user', 'AuthenticationController@guestUser');
        Route::post('login', 'AuthenticationController@authentication');
        Route::post('register', 'AuthenticationController@register');
        Route::post('forgot-password', 'AuthenticationController@forgotPassword');
        Route::POST('social-login', 'AuthenticationController@socialLogin');

        Route::group(['middleware' => 'ApiAuth'], function () {
            Route::get('logout', 'AuthenticationController@logout');
            Route::POST('change-password','AuthenticationController@changePassword');
        });

    });

    Route::group(['namespace' => 'User','middleware' => 'ApiAuth'], function () {
        Route::get('get-user-profile', 'UserController@index');
        Route::post('update-user-profile', 'UserController@updateProfile');
        //add user address
        Route::get('address-list','UserController@list');
        Route::post('add-address','UserController@store');
        Route::post('update-address','UserController@update');
        Route::post('delete-address','UserController@destroy');
        
        //user rating for store
        Route::post('user-store-rating','UserController@userRating');
    });
    Route::get('choose-service', 'User\UserController@selectService');
    //,'middleware' => 'ApiAuth'
    Route::group(['namespace' => 'ServiceProvider','middleware' => 'ApiAuth'], function () {
        Route::get('get-service-provider-list', 'ServiceProviderController@index');
        Route::post('get-service-provider-list', 'ServiceProviderController@serviceProviderView');
        Route::post('store-feed-back','ServiceProviderController@storeFeedBack');
        Route::post('store-category','ServiceProviderController@storeCategory');
        Route::post('store-category-services','ServiceProviderController@storeCategoryServices');
        Route::post('store-employee','ServiceProviderController@storeEmployee');
        Route::POST('filter','ServiceProviderController@filterService');
        //user for fileter screen
        Route::get('all-category','ServiceProviderController@allCategoryList');

        Route::post('all-service','ServiceProviderController@allService');
        Route::post('all-store','ServiceProviderController@allStore');
        Route::post('all-recommended-store','ServiceProviderController@allRecommendedStore');

        //store-suggestion
        Route::post('store-suggestion','ServiceProviderController@storeSuggetion');

        //sorting
        Route::post('service-short-by', 'ServiceProviderController@shortBy');

        
    });

    //conatact us 
    Route::group(['namespace' => 'Contactus','middleware' =>'ApiAuth'],function (){
        Route::post('contact-us','ContactUsController@store');
    });

    //Favourites 
    Route::group(['namespace' => 'Favourites','middleware' =>'ApiAuth'],function (){        
        Route::POST('favorites-list','FavouritesController@list');
        Route::POST('add-store-favorites','FavouritesController@store');
        Route::POST('remove-store-favorites','FavouritesController@removeFavourite');
    });

    //Booking
    //,'middleware' =>'ApiAuth'
    Route::group(['namespace' => 'Booking','middleware' =>'ApiAuth'],function (){                
        Route::POST('get-service-expert-details','BookingController@serviceExpertDetails');                             
        Route::POST('booking-time-available','BookingController@bookingTimeAvailable'); 
        Route::post('store-booking-available-time-direct', 'BookingController@getAvailableTimeDirect');     
        Route::post('get-available-emp-service', 'BookingController@getAvailableEmpService');      
    });

    //Payment
    Route::group(['namespace' => 'PaymentDetails','middleware' =>'ApiAuth'],function (){ 
        Route::POST('withdraw-payment','PaymentController@withdrawPayment');
    });
    Route::get('/paypal/cancel', 'PaymentDetails\PaymentController@cancel');
    Route::get('/paypal/payment/success', 'PaymentDetails\PaymentController@success');
    Route::get('karla-payment-success', 'PaymentDetails\PaymentController@klarnaSuccess');          

    //My Order
    //, 'middleware' => 'ApiAuth'
    Route::group(['namespace' => 'MyOrder','middleware' =>'ApiAuth'],function (){
        Route::get('my-order-list','OrderController@orderList');
        Route::post('order-date-postponed','OrderController@orderDatePostPoned');
        Route::post('order-cancellation-reason','OrderController@orderCancellationReason');
    });

});

//Service provider route.
Route::group(['namespace' => 'Api\ServiceProvider','prefix'=>'v1/provider/'],function(){

    Route::group(['namespace' => 'Auth'], function () {
        Route::post('login', 'AuthController@login');        
        Route::post('forgot-password', 'AuthController@forgotPassword');
        

        Route::group(['middleware' => 'ProviderAuth'], function () {
            Route::get('all-store','AuthController@providerAllStore');
            Route::get('logout', 'AuthController@logout');
        });

    });

    //dashbord
    Route::group(['namespace' => 'Dashboard','middleware' => 'ProviderAuth'], function () {
        Route::get('dashboard', 'DashboardController@dashboard');
    });

    //service list
    Route::group(['namespace' => 'ServiceList','middleware' => 'ProviderAuth'], function () {
        Route::POST('service-list', 'ServiceListController@serviceList');
        Route::GET('service-list-category', 'ServiceListController@serviceListCategory');
        Route::POST('service-add', 'ServiceListController@serviceStore');
        Route::POST('service-edit', 'ServiceListController@serviceUpdate');
        Route::POST('service-delete', 'ServiceListController@serviceDelete');
        Route::post('store-service-category','ServiceListController@storeCategory');
    });

    //Employee list save and update 
    Route::group(['namespace' => 'Employee','middleware' => 'ProviderAuth'], function () {
        Route::POST('employee-list', 'EmployeeListController@employeeList');
        Route::POST('employee-add', 'EmployeeListController@employeeStore');        
        Route::POST('employee-view', 'EmployeeListController@employeeView');        
        Route::POST('employee-edit', 'EmployeeListController@employeeUpdate');
        Route::POST('employee-delete', 'EmployeeListController@employeeDelete');

        //store category and service route
        Route::post('store-category','EmployeeListController@storeCategory');
        Route::post('store-service','EmployeeListController@storeService');
    });

    //Appoinment list 
    Route::group(['namespace' => 'Appointment','middleware' => 'ProviderAuth'], function () {
        Route::POST('appoinment-list', 'AppointmentController@appoinmentList');        
        Route::POST('order-list', 'AppointmentController@orderList');        
        Route::POST('appoinment-date-postpone', 'AppointmentController@appoinmentDatePostpone');        
        Route::POST('appoinment-cancel', 'AppointmentController@appoinmentCancel');    
        Route::POST('booking-time-available-provider','AppointmentController@bookingTimeAvailable'); 
        Route::post('store-booking-available-time-direct', 'AppointmentController@getAvailableTimeDirectStore');   
        Route::post('add-new-appoinment','AppointmentController@providerAddNewAppoinment');
        Route::post('get-store-employee','AppointmentController@getEmployee');
        Route::post('get-available-emp-service', 'AppointmentController@getAvailableEmpService');
    });

    //My Wallet route 
    Route::group(['namespace' => 'MyWallet','middleware' => 'ProviderAuth'], function () {
        Route::post('wallet-details','WalletController@walletDetails');
    });    

    //Store Profile Route
    Route::group(['namespace' => 'StoreProfile','middleware' =>'ProviderAuth'],function(){
        Route::get('get-store-detail','StoreProfileController@getStoreDetails');
        Route::POST('update-store-detail','StoreProfileController@updateStore');
        Route::POST('update-store-gallery','StoreProfileController@updateStoreGallery');        
        Route::POST('delete-store-gallery-image','StoreProfileController@deleteGalleryImage');
    });

    //feedbak route
    Route::group(['namespace'=>'FeedBacks','middleware' =>'ProviderAuth'],function(){
        Route::get('store-rating','FeedBackController@storeRating');
    });
});



