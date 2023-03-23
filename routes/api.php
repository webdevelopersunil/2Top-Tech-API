<?php

use App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Controllers\API;
use API\Provider\InvoiceController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\PartRequestAPIController;
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
/*
normal api_token
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
*/


Route::get('type-list',[API\CommanController::class,'getTypeList']);
Route::get('get-cuisines',[API\CommanController::class,'getCuisines']);
Route::get('services',[API\ServiceController::class,'getServices']);
Route::get('cuisines',[API\CommanController::class,'getCuisines']);

Route::get('stripe-webhook',[App\Http\Controllers\API\StripeController::class,'webhook']);

Route::get('countries',[ API\CommanController::class, 'getCountryList' ]);
Route::get('states',[ API\CommanController::class, 'getStateList']);
Route::post('city-list',[ API\CommanController::class, 'getCityList' ]);


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Restaurant Registration
Route::post('restaurant/register',[App\Http\Controllers\API\Restaurant\RestaurantController::class, 'register']);
// Technician Registration
Route::post('technician/register',[App\Http\Controllers\API\Provider\ProviderController::class, 'register']);

Route::post('login',[API\User\UserController::class,'login']);

Route::post('forgot-password',[ API\User\UserController::class,'forgotPassword']);
Route::post('verify-otp', [ API\User\UserController::class, 'verifyOtp' ] );
Route::post('create-new-password', [ API\User\UserController::class, 'createNewPassword' ] );


// ---------------Old-Routes---------------
Route::post('social-login',[ API\User\UserController::class, 'socialLogin' ]);
Route::post('contact-us', [ API\User\UserController::class, 'contactUs' ] );
Route::get('user-detail',[API\User\UserController::class, 'userDetail']);
Route::get('user-list',[API\User\UserController::class, 'userList']);
// ---------------Old-Routes---------------


Route::group(['prefix' => 'restaurant','middleware' => ['auth:sanctum','role.validate:restaurant']], function () {

    Route::get('chat/users',[App\Http\Controllers\API\ChatController::class,'roChatUsers']);

    Route::post('todo/create',[API\Restaurant\ToDoController::class,'store']);
    Route::get('todo',[API\Restaurant\ToDoController::class,'index']);
    Route::post('todo/status',[API\Restaurant\ToDoController::class,'updateStatus']);

    Route::get('dashboard',[API\Restaurant\DashboardController::class,'index']);
    Route::post('email-verify-resend', [App\Http\Controllers\API\Restaurant\RestaurantController::class, 'emailVerifyResend'] )->middleware(['throttle:5,1']);
    //Update Restaurant Profile Detail
    Route::post('profile',[API\Restaurant\RestaurantController::class,'updateProfile']);
    Route::get('profile',[API\Restaurant\RestaurantController::class,'profile']);
    Route::get('provider-profile/{uuid}',[API\Restaurant\RestaurantController::class,'providerProfile']);

    //Restaurant Jobs Applications Management
    Route::post('job',[API\Restaurant\RestaurantJobController::class,'jobPost']);
    Route::put('update-job/{uuid}', [API\Restaurant\RestaurantJobController::class,'updateJob']);
    Route::get('job/{uuid}',[API\Restaurant\RestaurantJobController::class,'jobDetail']);
    Route::post('job/cancel',[API\Restaurant\RestaurantJobController::class,'jobCancel']);
    Route::get('jobs',[API\Restaurant\RestaurantJobController::class,'jobs']);
    Route::get('job/applications/{uuid}',[API\Restaurant\RestaurantJobController::class,'getJobApplications']);

    Route::post('booking/completion',[API\Restaurant\BookingController::class,'bookingCompletion']);
    Route::get('payment/list',[API\Restaurant\InvoiceController::class,'paymentList']);

    Route::get('invoice/{uuid}',[API\Restaurant\InvoiceController::class,'invoiceView']);

    //Equipment Manage
    Route::resource('equipment', App\Http\Controllers\API\EquipmentAPIController::class);
    Route::resource('maintanance_frequencies', App\Http\Controllers\API\MaintananceFrequencyAPIController::class);

    // Company Subscription
    Route::post('plan/subscription',[App\Http\Controllers\API\StripeController::class,'craeteSubscription']);

    //Sending offer to Provider applied job application
    Route::post('send/offer',[API\Restaurant\RestaurantJobController::class,'sendOffer']);
    // Route::post('refer',[API\Restaurant\RestaurantController::class,'refer']);
    Route::get('invoices/pending',[API\Restaurant\InvoiceController::class,'restaurantPendingInvoice']);

    Route::post('notification-list',[API\NotificationController::class,'notificationList']);

    Route::post('cancel/subscription',[API\StripeController::class,'cancelSubscription']);
    Route::post('card/list',[API\StripeController::class,'cardLists']);
    Route::post('update/card',[API\StripeController::class,'updateCard']);
});

Route::group(['prefix' => 'technician','middleware' => ['auth:sanctum','role.validate:provider']], function () {

    Route::get('chat/users',[App\Http\Controllers\API\ChatController::class,'providerChatUsers']);

    Route::post('email-verify-resend', [App\Http\Controllers\API\Restaurant\RestaurantController::class, 'emailVerifyResend'] );

    //Update Technician Profile Detail
    Route::post('profile',[API\Provider\ProviderController::class,'updateProfile']);
    Route::get('profile',[API\Provider\ProviderController::class,'profile']);
    Route::get('provider-rate',[API\Provider\ProviderController::class,'providerRate']);

    //Jobs API Technician
    Route::get('jobs',[API\Provider\ProviderJobController::class,'jobs']);
    Route::get('job/{uuid}',[API\Provider\ProviderJobController::class,'jobDetail']);

    Route::resource('equipment', App\Http\Controllers\API\EquipmentAPIController::class);

    Route::get('dashboard',[API\Provider\DashboardController::class,'index']);

    Route::resource('equipment/request', PartRequestAPIController::class);
    Route::get('equipment-request-list', [App\Http\Controllers\API\PartRequestAPIController::class, 'getList']);

    //Provider Payment Methods
    Route::resource('provider_payment_methods', App\Http\Controllers\API\ProviderPaymentMethodAPIController::class);
    Route::get('provider/payment/methods', [App\Http\Controllers\API\ProviderPaymentMethodAPIController::class, 'getAccountDetail']);
    Route::get('invoice/{uuid}',[App\Http\Controllers\API\Provider\InvoiceController::class,'invoiceView']);
    Route::group(['prefix' => 'job'], function () {

        //Jobs Applications Management
        Route::post('application',[API\Provider\ProviderJobController::class,'applyJob']);
        Route::post('application/offer',[API\Provider\ProviderJobController::class,'applicationAccept']);

    });

    Route::get('bookings',[API\Provider\ProviderJobController::class,'bookingList']);

    //Booking Management
    Route::post('start/tracking',[API\Provider\BookingController::class,'startTracking']);
    Route::get('tracking/status/{uuid}',[API\Provider\BookingController::class,'trackingStatus']);

    //Manage Work - Log
    Route::post('update/work-log',[API\Provider\BookingController::class,'updateWorkLog']);
    Route::post('work-log',[API\Provider\BookingController::class,'workLog']);

    Route::post('booking/invoice',[API\Provider\BookingController::class,'bookingInvoice']);
    Route::get('booking/invoice',[API\Provider\BookingController::class,'bookingInvoicesList']);

    Route::post('notification-list',[API\NotificationController::class,'notificationList']);

});



Route::group(['middleware' => ['auth:sanctum']], function () {

    //For File Uploading
    Route::post('file-upload',[API\FileUploadingController::class,'upload']);
    Route::post('delete/file',[API\FileUploadingController::class,'deleteFile']);

    Route::post('/static-data', API\StaticDataController::class);

    Route::post('refer',[App\Http\Controllers\API\CommanController::class,'refer']);

    Route::post('service',[API\ServiceController::class, 'create']);
    Route::get('logout',[ API\User\UserController::class, 'logout' ]);
    Route::post('save-provider-bank',[API\User\UserController::class,'saveProviderBank']);

    Route::post('user-update-status',[API\User\UserController::class, 'userStatusUpdate']);
    Route::post('change-password',[API\User\UserController::class, 'changePassword']);
    Route::post('delete-user-account',[API\User\UserController::class, 'deleteUserAccount']);
    Route::post('delete-account',[API\User\UserController::class, 'deleteAccount']);

    Route::resource('features', App\Http\Controllers\API\FeatureAPIController::class);
    Route::get('plans',[ App\Http\Controllers\API\PlansController::class,'index']);

    Route::post('rating-review', [API\RatingReviewController::class, 'store']);

    Route::get('test-notification',[API\NotificationController::class,'testNotifications']);

    //Test Case For Testing Notification through FCM
    Route::get('test/token',function () {
        (new \App\Http\Service\FCMPushNotificationService)->testNotification();
        return True;
    });

});
