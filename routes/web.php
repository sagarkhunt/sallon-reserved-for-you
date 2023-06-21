<?php
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'Frontend\Cosmetic\IndexController@index');

Route::get('cosmetic', 'Frontend\Cosmetic\IndexController@index');

Route::get('become-partner', function () {
    return view('Front.Cosmetic.becomePartner');
});

Route::get('get_sub_cat', 'Frontend\Cosmetic\IndexController@getSubCat');
Route::get('get_stores', 'Frontend\Cosmetic\IndexController@getStores');

Route::get('cosmetic-area/{slug}', 'Frontend\Cosmetic\IndexController@cosmeticArea');
Route::post('cosmetic-area', 'Frontend\Cosmetic\IndexController@search');
Route::get('cosmetic/{slug}', 'Frontend\Cosmetic\IndexController@cosmeticView');
Route::post('service-filter', 'Frontend\Cosmetic\IndexController@filter');
Route::post('service-short-by', 'Frontend\Cosmetic\IndexController@shortBy');
Route::post('get-employee', 'Frontend\Cosmetic\IndexController@getEmployee');
Route::post('get-employee-list', 'Frontend\Cosmetic\IndexController@getEmployeeList');
Route::post('get-datepicker', 'Frontend\Cosmetic\IndexController@getDatepicker');
Route::post('get-timeslot', 'Frontend\Cosmetic\IndexController@getTimeslot');
Route::post('get-available-time', 'Frontend\Cosmetic\IndexController@getAvailableTime');
Route::post('get-available-time-direct', 'Frontend\Cosmetic\IndexController@getAvailableTimeDirect');
Route::post('get-available-emp', 'Frontend\Cosmetic\IndexController@getAvailableEmp');
Route::post('get-service-details', 'Frontend\Cosmetic\IndexController@getServiceDetails');
Route::post('get-booking-data', 'Frontend\Cosmetic\IndexController@getBookingData');
Route::post('get-service-data', 'Frontend\Cosmetic\IndexController@getServiceData');
Route::post('get-search-data', 'Frontend\Cosmetic\IndexController@searchBar');
Route::post('get-search-service', 'Frontend\Cosmetic\IndexController@searchBarSearvice');
Route::post('get-sub-category', 'Frontend\Cosmetic\IndexController@getSubCategory');
Route::get('recommended-for-you', 'Frontend\Cosmetic\IndexController@recommendedYou');



Route::post('get-employee-data', 'Frontend\Cosmetic\IndexController@getEmployeeData');
Route::post('get-service-value-data', 'Frontend\Cosmetic\IndexController@getServiceValueData');
Route::post('rating-filter', 'Frontend\Cosmetic\IndexController@ratingFilter');


Route::get('cosmetic-advantages', function () {
    return view('Front.Cosmetic.cosmeticAdvantages');
});

Route::get('auth/google', 'Frontend\AuthController@redirectToGoogle');
Route::get('auth/google/callback', 'Frontend\AuthController@handleGoogleCallback');

Route::get('auth/facebook', 'Frontend\AuthController@redirectToFacebook');
Route::get('auth/facebook/callback', 'Frontend\AuthController@handleFacebookCallback');


Route::post('user-login', 'Frontend\AuthController@doLogin');
Route::post('user-register', 'Frontend\AuthController@doRegister');
Route::post('user-forgot', 'Frontend\AuthController@forgotPassword');

Route::group(['middleware' => 'UserAuth'], function () {
    Route::get('user-profile', 'Frontend\User\UserController@index');
    Route::get('user-logout', 'Frontend\AuthController@logout');
    Route::post('update-profile', 'Frontend\User\UserController@updateProfile');
    Route::post('favorite-store', 'Frontend\User\UserController@favoriteStore');
    Route::post('change-profile', 'Frontend\User\UserController@changeProfile');
    Route::post('get-appointment-data', 'Frontend\User\UserController@getAppointmentData');
    Route::post('cancel-appointment', 'Frontend\User\UserController@cancelAppontment');
});

Route::post('submit-rating', 'Frontend\User\UserController@submitRating');

Route::post('submit-payment-booking', 'Frontend\Payment\PaymentController@checkout');

Route::get('/cancel', 'Frontend\Payment\PaymentController@cancel')->name('payment.cancel');
Route::get('/payment/success', 'Frontend\Payment\PaymentController@success')->name('payment.success');
Route::get('/karla-payment-success', 'Frontend\Payment\PaymentController@Karlasuccess')->name('karla.success');

Route::get('klarna', 'Frontend\Payment\PaymentController@Klarna');

Route::get('apple-pay', 'Frontend\Payment\PaymentController@applePay');


Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::group(['prefix' => 'master-admin', 'namespace' => 'Admin'], function () {
    Route::get('login', 'AuthenticationController@login');
    Route::post('login', 'AuthenticationController@doLogin');
    Route::group(['middleware' => 'AdminAuth'], function () {
        Route::get('/', 'DashboardController@index');

        Route::get('logout', 'AuthenticationController@logout');

        Route::resource('users', 'UserController');
        Route::get('users/{id}/destroy', 'UserController@destroy');
        Route::get('users/{id}/status', 'UserController@statusChange');

        Route::resource('service-provider', 'ServiceProviderController');
        Route::get('service-provider/{id}/destroy', 'ServiceProviderController@destroy');
        Route::get('service-provider/{id}/status', 'ServiceProviderController@statusChange');

        Route::resource('admin', 'AdminController');
        Route::get('admin/{id}/destroy', 'AdminController@destroy');
        Route::get('admin/{id}/status', 'AdminController@statusChange');

        Route::resource('category', 'CategoryController');
        Route::get('category/{id}/destroy', 'CategoryController@destroy');
        Route::get('category/{id}/status', 'CategoryController@statusChange');

        Route::resource('cosmetics-category', 'CosmeticsCategoryController');
        Route::get('cosmetics-category/{id}/destroy', 'CosmeticsCategoryController@destroy');
        Route::get('cosmetics-category/{id}/status', 'CosmeticsCategoryController@statusChange');

        Route::resource('store-profile', 'StoreProfileController');
        Route::get('store-profile/{id}/destroy', 'StoreProfileController@destroy');
        Route::post('store-profile/category', 'StoreProfileController@changeCategory');


        Route::resource('store-profile/{id}/service', 'ServiceController');
        Route::get('store-profile/{store_id}/service/{id}/destroy', 'ServiceController@destroy');
        Route::post('service/category', 'ServiceController@changeCategory');

        Route::resource('store-profile/{id}/advantages', 'Store\AdvantagesController');
        Route::get('store-profile/{store_id}/advantages/{id}/destroy', 'Store\AdvantagesController@destroy');

        Route::resource('store-profile/{id}/public-transportation', 'Store\PublicTransportationController');
        Route::get('store-profile/{store_id}/public-transportation/{id}/destroy', 'Store\PublicTransportationController@destroy');

        Route::resource('store-profile/{id}/parking', 'Store\ParkingController');
        Route::get('store-profile/{store_id}/parking/{id}/destroy', 'Store\ParkingController@destroy');

        Route::resource('plans', 'PlanController');
        Route::get('plans/{id}/destroy', 'PlanController@destroy');
        Route::get('plans/{id}/status', 'PlanController@statusChange');

        Route::resource('payment-info', 'PaymentController');
        Route::resource('appointment-list', 'AppointmentController');
    });

});

Route::group(['prefix' => 'service-provider', 'namespace' => 'ServiceProvider'], function () {
    Route::get('login', 'AuthenticationController@login');
    Route::post('login', 'AuthenticationController@doLogin');
    Route::post('register', 'AuthenticationController@doRegister');

    Route::group(['middleware' => 'ServiceAuth'], function () {
        Route::get('/', 'DashboardController@index');
        Route::get('logout', 'AuthenticationController@logout');

        Route::get('/service-list', 'ServiceListController@index');
        Route::post('/get-service', 'ServiceListController@getService');
        Route::post('service/category', 'ServiceListController@changeCategory');
        Route::post('add-service', 'ServiceListController@addService');
        Route::post('edit-service', 'ServiceListController@editService');
        Route::post('update-service', 'ServiceListController@updateService');
        Route::post('remove-service', 'ServiceListController@removeService');


        Route::get('employee-list', 'EmployeeController@index');
        Route::post('employee/category', 'EmployeeController@getService');
        Route::post('add-employee', 'EmployeeController@addEmployee');
        Route::post('edit-employee', 'EmployeeController@editEmployee');
        Route::post('update-employee', 'EmployeeController@updateEmployee');
        Route::post('remove-employee', 'EmployeeController@removeEmployee');

        Route::get('appointment', 'AppointmentController@index');
        Route::post('create-appointment', 'AppointmentController@createAppointment');
        Route::post('get-appointment-data', 'AppointmentController@getAppointmentData');
        Route::post('postpond-appointment', 'AppointmentController@postpondAppointment');
        Route::post('cancel-appointment', 'AppointmentController@cancelAppointment');
        Route::post('search-appointment', 'AppointmentController@searchAppointment');
        Route::post('search-appointment-recent', 'AppointmentController@searchAppointmentRecent');

        Route::get('wallet', 'WalletController@index');


        Route::get('online-store', 'StoreController@index');
        Route::post('update-store', 'StoreController@store');
        Route::post('update-store-profile', 'StoreController@changeProfile');
        Route::post('update-store-banner', 'StoreController@changeBannerProfile');
        Route::post('update-store-gallery', 'StoreController@changeBannerGallery');
        Route::get('remove-image/{id}/gallery', 'StoreController@removeImageGallery');

        Route::post('set-store', 'DashboardController@setSession');
    });
});


Route::get('cosmetic-advantages', function () {
    return view('Front.Cosmetic.cosmeticAdvantages');
});

Route::get('auth/google', 'Frontend\AuthController@redirectToGoogle');
Route::get('auth/google/callback', 'Frontend\AuthController@handleGoogleCallback');

Route::get('auth/facebook', 'Frontend\AuthController@redirectToFacebook');
Route::get('auth/facebook/callback', 'Frontend\AuthController@handleFacebookCallback');


Route::post('user-login', 'Frontend\AuthController@doLogin');
Route::post('user-register', 'Frontend\AuthController@doRegister');
Route::post('user-forgot', 'Frontend\AuthController@forgotPassword');

Route::group(['middleware' => 'UserAuth'], function () {
    Route::get('user-profile', 'Frontend\User\UserController@index');
    Route::get('user-logout', 'Frontend\AuthController@logout');
    Route::post('update-profile', 'Frontend\User\UserController@updateProfile');
    Route::post('favorite-store', 'Frontend\User\UserController@favoriteStore');
    Route::post('change-profile', 'Frontend\User\UserController@changeProfile');
    Route::post('get-appointment-data', 'Frontend\User\UserController@getAppointmentData');
    Route::post('cancel-appointment', 'Frontend\User\UserController@cancelAppontment');
});

Route::post('submit-rating','Frontend\User\UserController@submitRating');

Route::post('submit-payment-booking', 'Frontend\Payment\PaymentController@checkout');

Route::get('/cancel', 'Frontend\Payment\PaymentController@cancel')->name('payment.cancel');
Route::get('/payment/success', 'Frontend\Payment\PaymentController@success')->name('payment.success');
Route::get('/karla-payment-success', 'Frontend\Payment\PaymentController@Karlasuccess')->name('karla.success');

Route::get('klarna', 'Frontend\Payment\PaymentController@Klarna');

Route::get('apple-pay', 'Frontend\Payment\PaymentController@applePay');


Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::group(['prefix' => 'master-admin', 'namespace' => 'Admin'], function () {
    Route::get('login', 'AuthenticationController@login');
    Route::post('login', 'AuthenticationController@doLogin');
    Route::group(['middleware' => 'AdminAuth'], function () {
        Route::get('/', 'DashboardController@index');

        Route::get('logout', 'AuthenticationController@logout');

        Route::resource('users', 'UserController');
        Route::get('users/{id}/destroy', 'UserController@destroy');
        Route::get('users/{id}/status', 'UserController@statusChange');

        Route::resource('service-provider', 'ServiceProviderController');
        Route::get('service-provider/{id}/destroy', 'ServiceProviderController@destroy');
        Route::get('service-provider/{id}/status', 'ServiceProviderController@statusChange');

        Route::resource('admin', 'AdminController');
        Route::get('admin/{id}/destroy', 'AdminController@destroy');
        Route::get('admin/{id}/status', 'AdminController@statusChange');

        Route::resource('category', 'CategoryController');
        Route::get('category/{id}/destroy', 'CategoryController@destroy');
        Route::get('category/{id}/status', 'CategoryController@statusChange');

        Route::resource('cosmetics-category', 'CosmeticsCategoryController');
        Route::get('cosmetics-category/{id}/destroy', 'CosmeticsCategoryController@destroy');
        Route::get('cosmetics-category/{id}/status', 'CosmeticsCategoryController@statusChange');

        Route::resource('store-profile', 'StoreProfileController');
        Route::get('store-profile/{id}/destroy', 'StoreProfileController@destroy');
        Route::post('store-profile/category', 'StoreProfileController@changeCategory');


        Route::resource('store-profile/{id}/service', 'ServiceController');
        Route::get('store-profile/{store_id}/service/{id}/destroy', 'ServiceController@destroy');
        Route::post('service/category', 'ServiceController@changeCategory');

        Route::resource('store-profile/{id}/advantages', 'Store\AdvantagesController');
        Route::get('store-profile/{store_id}/advantages/{id}/destroy', 'Store\AdvantagesController@destroy');

        Route::resource('store-profile/{id}/public-transportation', 'Store\PublicTransportationController');
        Route::get('store-profile/{store_id}/public-transportation/{id}/destroy', 'Store\PublicTransportationController@destroy');

        Route::resource('store-profile/{id}/parking', 'Store\ParkingController');
        Route::get('store-profile/{store_id}/parking/{id}/destroy', 'Store\ParkingController@destroy');

        Route::resource('plans', 'PlanController');
        Route::get('plans/{id}/destroy', 'PlanController@destroy');
        Route::get('plans/{id}/status', 'PlanController@statusChange');

        Route::resource('payment-info', 'PaymentController');
        Route::resource('appointment-list', 'AppointmentController');
    });

});

Route::group(['prefix' => 'service-provider', 'namespace' => 'ServiceProvider'], function () {
    Route::get('login', 'AuthenticationController@login');
    Route::post('login', 'AuthenticationController@doLogin');
    Route::post('register', 'AuthenticationController@doRegister');

    Route::group(['middleware' => 'ServiceAuth'], function () {
        Route::get('/', 'DashboardController@index');
        Route::get('logout', 'AuthenticationController@logout');

        Route::get('/service-list', 'ServiceListController@index');
        Route::post('/get-service', 'ServiceListController@getService');
        Route::post('service/category', 'ServiceListController@changeCategory');
        Route::post('add-service', 'ServiceListController@addService');
        Route::post('edit-service', 'ServiceListController@editService');
        Route::post('update-service', 'ServiceListController@updateService');
        Route::post('remove-service', 'ServiceListController@removeService');


        Route::get('employee-list', 'EmployeeController@index');
        Route::post('employee/category', 'EmployeeController@getService');
        Route::post('add-employee', 'EmployeeController@addEmployee');
        Route::post('edit-employee', 'EmployeeController@editEmployee');
        Route::post('update-employee', 'EmployeeController@updateEmployee');

        Route::get('appointment', 'AppointmentController@index');

        Route::get('wallet', 'WalletController@index');


        Route::get('online-store', 'StoreController@index');
        Route::post('update-store', 'StoreController@store');
        Route::post('update-store-profile', 'StoreController@changeProfile');
        Route::post('update-store-banner', 'StoreController@changeBannerProfile');
        Route::post('update-store-gallery', 'StoreController@changeBannerGallery');
        Route::get('remove-image/{id}/gallery', 'StoreController@removeImageGallery');

        Route::post('set-store', 'DashboardController@setSession');
    });
});

/**
 * Cron Job Urls
 */
Route::get('current-appointment','Cron\CronController@activeAppointment');
Route::get('complete-appointment','Cron\CronController@completeAppointment');
