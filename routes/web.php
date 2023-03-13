<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\JobController;
use App\Http\Controllers\TaxController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\SliderController;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\EarningController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\FrontendController;
use App\Http\Controllers\HandymanController;
use App\Http\Controllers\ProviderController;
use App\Http\Controllers\DocumentsController;
use App\Http\Controllers\EquipmentController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\ServiceFaqController;
use App\Http\Controllers\PartRequestController;
use App\Http\Controllers\SubCategoryController;
use App\Http\Controllers\VerifyEmailController;
use App\Http\Controllers\HandymanTypeController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProviderTypeController;
use App\Http\Controllers\RatingReviewController;
use App\Http\Controllers\EmailTemplateController;
use App\Http\Controllers\HandymanPayoutController;
use App\Http\Controllers\PaymentGatewayController;
use App\Http\Controllers\ProviderPayoutController;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\ProviderDocumentController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use App\Http\Controllers\ProviderAddressMappingController;

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
require __DIR__.'/auth.php';
Route::get('/', [FrontendController::class, 'index'])->name('frontend.index');
Route::group(['prefix' => 'auth'], function() {
    Route::get('login', [HomeController::class, 'authLogin'])->name('auth.login');
    Route::get('register', [HomeController::class, 'authRegister'])->name('auth.register');
    Route::get('recover-password', [HomeController::class, 'authRecoverPassword'])->name('auth.recover-password');
    Route::get('confirm-email', [HomeController::class, 'authConfirmEmail'])->name('auth.confirm-email');
    Route::get('lock-screen', [HomeController::class, 'authlockScreen'])->name('auth.lock-screen');
});

Route::get('term-condition',[ FrontendController::class, 'termAndCondition']);
Route::get('privacy-policy',[ FrontendController::class, 'privacyPolicy']);

Route::get('lang/{locale}', [HomeController::class,'lang'])->name('switch-language');

Route::get('/email/verify/{id}/{hash}', [VerifyEmailController::class, '__invoke'])
    ->middleware(['signed', 'throttle:6,1'])
    ->name('verification.verify');

Route::get('verified-success', [HomeController::class, 'verifiedSuccess'])->name('auth.verified-success');


Route::group(['middleware' => ['auth', 'verified']], function()
{

    Route::get('changeStatus', [ HomeController::class, 'changeStatus'])->name('changeStatus');

    Route::get('changeProviderStatus/{id?}/{status?}', [ ProviderController::class, 'changeProviderStatus'])->name('changeProviderStatus');

    Route::resource('category', CategoryController::class);
    Route::post('category-action',[CategoryController::class, 'action'])->name('category.action');






    // Resend link to verify email
    Route::post('/email/verify/resend', function (Request $request) {
        $request->user()->sendEmailVerificationNotification();
        return back()->with('message', 'Verification link sent!');
    })->middleware(['auth:api', 'throttle:6,1'])->name('verification.send');

    // admin routes
    Route::group(['prefix' => 'admin'], function () {

        Route::resource('user_management', UserManagementController::class);
        Route::resource('service', ServiceController::class);
        Route::post('service-action',[ServiceController::class, 'action'])->name('service.action');
        Route::resource('provider', ProviderController::class);
        Route::resource('part-request', PartRequestController::class);
        Route::get('part-request-status-update/{id?}/{status?}', [ PartRequestController::class, 'partRequestStatusUpdate'])->name('partRequestStatusUpdate');
        Route::resource('jobs', JobController::class);
        Route::resource('provideraddress', ProviderAddressMappingController::class);
        Route::get('provider/list/{status?}', [ProviderController::class,'index'])->name('provider.pending');
        Route::post('provider-action',[ProviderController::class, 'action'])->name('provider.action');
        Route::resource('providertype', ProviderTypeController::class);
        Route::post('providertype-action',[ProviderTypeController::class, 'action'])->name('providertype.action');
        Route::resource('user', CustomerController::class);
        Route::post('user-action',[CustomerController::class, 'action'])->name('user.action');
        Route::resource('equipment', EquipmentController::class);
        Route::get('equipment/list/{id?}', [EquipmentController::class,'index'])->name('equipment.list');
        Route::post('equipment-action',[EquipmentController::class, 'action'])->name('equipment.action');
        Route::resource('tax', TaxController::class);
        Route::get('/home', [HomeController::class, 'index'])->name('home');
        Route::group(['namespace' => '' ], function () {
            Route::resource('permission',PermissionController::class);
            Route::get('permission/add/{type}',[PermissionController::class,'addPermission'])->name('permission.add');
            Route::post('permission/save',[PermissionController::class,'savePermission'])->name('permission.save');
        });
        Route::get('pages/term-condition',[ SettingController::class, 'termAndCondition'])->name('term-condition');
        Route::post('term-condition-save',[ SettingController::class, 'saveTermAndCondition'])->name('term-condition-save');
        Route::get('pages/privacy-policy',[ SettingController::class, 'privacyPolicy'])->name('privacy-policy');
        Route::post('privacy-policy-save',[ SettingController::class, 'savePrivacyPolicy'])->name('privacy-policy-save');
        Route::get('setting/{page?}',[ SettingController::class, 'settings'])->name('setting.index');
        Route::post('settings/save',[ SettingController::class , 'settingsUpdates'])->name('settingsUpdates');
        Route::resource('role', RoleController::class);
        Route::resource('emailtemplates', EmailTemplateController::class);
        Route::post('emailTemplates-action',[EmailTemplateController::class, 'action'])->name('emailTemplates.action');
    });


    Route::resource('handyman', HandymanController::class);
    Route::get('handyman/list/{status?}', [HandymanController::class,'index'])->name('handyman.pending');
    Route::post('handyman-action',[HandymanController::class, 'action'])->name('handyman.action');
    Route::resource('coupon', CouponController::class);
    Route::post('coupons-action',[CouponController::class, 'action'])->name('coupon.action');
    // Route::resource('booking', BookingController::class);
    // Route::post('booking-save', [ App\Http\Controllers\BookingController::class, 'store' ] )->name('booking.save');
    // Route::post('booking-action',[BookingController::class, 'action'])->name('booking.action');
    Route::resource('slider', SliderController::class);
    Route::post('slider-action',[SliderController::class, 'action'])->name('slider.action');
    Route::resource('payment', PaymentController::class);
    Route::post('save-payment',[App\Http\Controllers\API\PaymentController::class, 'savePayment'])->name('payment.save');


    // Route::get('booking-assign-form/{id}',[BookingController::class,'bookingAssignForm'])->name('booking.assign_form');
    // Route::post('booking-assigned',[BookingController::class,'bookingAssigned'])->name('booking.assigned');

    // Setting

    // Route::post('/layout-page',[ SettingController::class, 'layoutPage'])->name('layout_page');
    Route::post('/layout-page',[ SettingController::class, 'layoutPage'])->name('layout_page');

    Route::post('save-app-download',[ SettingController::class , 'saveAppDownloadSetting'])->name('saveAppDownload');
    Route::post('dashboard-setting',[ SettingController::class , 'dashboardtogglesetting'])->name('togglesetting');
    Route::post('provider-dashboard-setting',[ SettingController::class , 'providerdashboardtogglesetting'])->name('providertogglesetting');
    Route::post('handyman-dashboard-setting',[ SettingController::class , 'handymandashboardtogglesetting'])->name('handymantogglesetting');
    Route::post('config-save',[ SettingController::class , 'configUpdate'])->name('configUpdate');


    Route::post('env-setting', [ SettingController::class , 'envChanges'])->name('envSetting');
    Route::post('update-profile', [ SettingController::class , 'updateProfile'])->name('updateProfile');
    Route::post('change-password', [ SettingController::class , 'changePassword'])->name('changePassword');

    Route::get('notification-list',[ NotificationController::class ,'notificationList'])->name('notification.list');
    Route::get('notification-counts',[ NotificationController::class ,'notificationCounts'])->name('notification.counts');
    Route::get('notification',[ NotificationController::class ,'index'])->name('notification.index');

    Route::post('remove-file', [ App\Http\Controllers\HomeController::class, 'removeFile' ] )->name('remove.file');
    Route::post('get-lang-file', [ App\Http\Controllers\LanguageController::class, 'getFile' ] )->name('getLangFile');
    Route::post('save-lang-file', [ App\Http\Controllers\LanguageController::class, 'saveFileContent' ] )->name('saveLangContent');



    Route::resource('document', DocumentsController::class);
    Route::post('document-action',[DocumentsController::class, 'action'])->name('document.action');

    Route::resource('providerdocument', ProviderDocumentController::class);
    Route::post('providerdocument-action',[ProviderDocumentController::class, 'action'])->name('providerdocument.action');

    Route::resource('ratingreview', RatingReviewController::class);
    Route::post('ratingreview-action',[RatingReviewController::class, 'action'])->name('ratingreview.action');

    Route::post('/payment-layout-page',[ PaymentGatewayController::class, 'paymentPage'])->name('payment_layout_page');
    Route::post('payment-settings/save',[ PaymentGatewayController::class , 'paymentsettingsUpdates'])->name('paymentsettingsUpdates');
    Route::post('get_payment_config',[ PaymentGatewayController::class , 'getPaymentConfig'])->name('getPaymentConfig');

    Route::get('earning',[EarningController::class,'index'])->name('earning');
    Route::get('earning-data',[EarningController::class,'setEarningData'])->name('earningData');

    Route::get('handyman-earning',[EarningController::class,'handymanEarning'])->name('handymanEarning');
    Route::get('handyman-earning-data',[EarningController::class,'handymanEarningData'])->name('handymanEarningData');

    Route::resource('providerpayout', ProviderPayoutController::class);
    Route::get('providerpayout/create/{id}', [ProviderPayoutController::class,'create'])->name('providerpayout.create');
    Route::post('sidebar-reorder-save',[ SettingController::class, 'sequenceSave'])->name('reorderSave');

    Route::resource('handymanpayout', HandymanPayoutController::class);
    Route::get('handymanpayout/create/{id}', [HandymanPayoutController::class,'create'])->name('handymanpayout.create');

    Route::resource('handymantype', HandymanTypeController::class);
    Route::post('handymantype-action',[HandymanTypeController::class, 'action'])->name('handymantype.action');

    Route::resource('servicefaq', ServiceFaqController::class);
    Route::post('send-push-notification', [ SettingController::class , 'sendPushNotification'])->name('sendPushNotification');
    Route::post('save-earning-setting', [ SettingController::class , 'saveEarningTypeSetting'])->name('saveEarningTypeSetting');

    Route::resource('wallet', WalletController::class);
    Route::resource('subcategory', SubCategoryController::class);
    Route::post('subcategory-action',[SubCategoryController::class, 'action'])->name('subcategory.action');

    Route::resource('plans', PlanController::class);

});
Route::get('/ajax-list',[HomeController::class, 'getAjaxList'])->name('ajax-list');

Route::get('generator_builder', '\InfyOm\GeneratorBuilder\Controllers\GeneratorBuilderController@builder')->name('io_generator_builder');

Route::get('field_template', '\InfyOm\GeneratorBuilder\Controllers\GeneratorBuilderController@fieldTemplate')->name('io_field_template');

Route::get('relation_field_template', '\InfyOm\GeneratorBuilder\Controllers\GeneratorBuilderController@relationFieldTemplate')->name('io_relation_field_template');

Route::post('generator_builder/generate', '\InfyOm\GeneratorBuilder\Controllers\GeneratorBuilderController@generate')->name('io_generator_builder_generate');

Route::post('generator_builder/rollback', '\InfyOm\GeneratorBuilder\Controllers\GeneratorBuilderController@rollback')->name('io_generator_builder_rollback');

Route::post(
    'generator_builder/generate-from-file',
    '\InfyOm\GeneratorBuilder\Controllers\GeneratorBuilderController@generateFromFile'
)->name('io_generator_builder_generate_from_file');


