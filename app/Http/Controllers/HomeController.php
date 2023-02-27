<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Slider;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\Service;
use App\Models\Setting;
use App\Models\Category;
use App\Models\Provider;
use App\Models\AppSetting;
use App\Models\JobBooking;
use App\Models\ServiceFaq;
use Illuminate\Http\Request;
use App\Models\HandymanPayout;
use App\Models\ProviderPayout;
use App\Models\ProviderDocument;
use App\Models\RestaurantJob;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class HomeController extends Controller
{
     /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $setting_data = Setting::select('value')->where('type','dashboard_setting')->where('key','dashboard_setting')->first();
        $data['dashboard_setting']  =   !empty($setting_data) ? json_decode($setting_data->value) : [];
        $date = \Carbon\Carbon::today()->subDays(30);

        $data['handyman_dashboard_setting']  =   !empty($handyman_setting_data) ? json_decode($handyman_setting_data->value) : [];
        $data['dashboard'] = [
            'count_total_booking'               => 100,
            'count_total_service'               => Service::myService()->count(),
            'count_total_provider'              => Provider::count(),
            'new_customer'                      => (new User)->getLatestCompanies(),
            'new_provider'                      => (new User)->getLatestProviders(),
            'upcomming_booking'                 => [],
            'top_services_list'                 => [],
            'bookings_count'                    => JobBooking::whereIn('status',['Complete','In-Progress'])->where('created_at','<=',$date)->count(),
            'job_count'                         => RestaurantJob::where('created_at','>=',$date)->count(),
            'count_handyman_pending_booking'    => 100,
            'count_handyman_complete_booking'   => 100,
            'count_handyman_cancelled_booking'  => 100
        ];

        $data['category_chart'] = [
            // 'chartdata'     => Booking::myBooking()->showServiceCount()->take(4)->get()->pluck('count_pid'),
            // 'chartlabel'    => Booking::myBooking()->showServiceCount()->take(4)->get()->pluck('service.category.name')
            'chartdata'     => 10,
            'chartlabel'    => 10
        ];

        return $this->adminDashboard($data);
    }

    /**
     * Admin Dashboard
     *
     * @param $data
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function adminDashboard($data)
    {
        $show="false";
        $dashboard_setting = Setting::where('type','dashboard_setting')->first();

        if($dashboard_setting == null){
            $show="true";
        }
        return view('dashboard.dashboard',compact('data','show'));
    }
    public function providerDashboard($data)
    {
        $show="false";
        $provider_dashboard_setting = Setting::where('type','provider_dashboard_setting')->first();

        if($provider_dashboard_setting == null){
            $show="true";
        }
        return view('dashboard.provider-dashboard',compact('data','show'));
    }
    public function handymanDashboard($data)
    {
        $show="false";
        $handyman_dashboard_setting = Setting::where('type','handyman_dashboard_setting')->first();

        if($handyman_dashboard_setting == null){
            $show="true";
        }
        return view('dashboard.handyman-dashboard',compact('data','show'));
    }
    public function userDashboard($data)
    {
        return view('dashboard.user-dashboard',compact('data'));
    }
    public function changeStatus(Request $request)
    {
        if(demoUserPermission()){
            $message = __('messages.demo_permission_denied');
            $response = [
                'status'    => false,
                'message'   => $message
            ];

            return comman_custom_response($response);
        }
        $type = $request->type;
        $message_form = __('messages.item');
        $message = trans('messages.update_form',['form' => trans('messages.status')]);
        switch ($type) {
            case 'role':
                    $role = \App\Models\Role::find($request->id);
                    $role->status = $request->status;
                    $role->save();
                    break;
            case 'category_status' :

                    $category = \App\Models\Category::find($request->id);
                    $category->status = $request->status;
                    $category->save();
                    break;
            case 'category_featured' :
                    $message_form = __('messages.category');
                    $category = \App\Models\Category::find($request->id);
                    $category->is_featured = $request->status;
                    $category->save();
                    break;
            case 'service_status' :
                $service = \App\Models\Service::find($request->id);
                $service->status = $request->status;
                $service->save();
                break;
            case 'coupon_status' :
                $coupon = \App\Models\Coupon::find($request->id);
                $coupon->status = $request->status;
                $coupon->save();
                break;
            case 'document_status' :
                $document = \App\Models\Documents::find($request->id);
                $document->status = $request->status;
                $document->save();
                break;
            case 'document_required' :
                $message_form = __('messages.document');
                $document = \App\Models\Documents::find($request->id);
                $document->is_required = $request->status;
                $document->save();
                break;
            case 'provider_is_verified' :
                $message_form = __('messages.providerdocument');
                $document = \App\Models\ProviderDocument::find($request->id);
                $document->is_verified = $request->status;
                $document->save();
                break;
            case 'tax_status' :
                $tax = \App\Models\Tax::find($request->id);
                $tax->status = $request->status;
                $tax->save();
                break;
            case 'provideraddress_status' :
                $provideraddress = \App\Models\ProviderAddressMapping::find($request->id);
                $provideraddress->status = $request->status;
                $provideraddress->save();
                break;
            case 'slider_status' :
                $slider = \App\Models\Slider::find($request->id);
                $slider->status = $request->status;
                $slider->save();
                break;
            case 'servicefaq_status' :
                $servicefaq = \App\Models\ServiceFaq::find($request->id);
                $servicefaq->status = $request->status;
                $servicefaq->save();
                break;
            case 'wallet_status' :
                $wallet = \App\Models\Wallet::find($request->id);
                $wallet->status = $request->status;
                $wallet->save();
                break;
            case 'subcategory_status' :
                $subcategory = \App\Models\SubCategory::find($request->id);
                $subcategory->status = $request->status;
                $subcategory->save();
                break;
            case 'subcategory_featured' :
                $message_form = __('messages.subcategory');
                $subcategory = \App\Models\SubCategory::find($request->id);
                $subcategory->is_featured = $request->status;
                $subcategory->save();
                break;
            case 'emailtemplates_status' :
                $message_form = __('messages.subcategory');
                $subcategory = \App\Models\EmailTemplate::find($request->id);
                $subcategory->status = $request->status;
                $subcategory->save();
                break;
            default:
                    $message = 'error';
                break;
        }
        if($request->has('is_featured') && $request->is_featured == 'is_featured'){
            $message =  __('messages.added_form',['form' => $message_form ]);
            if($request->status == 0){
                $message = __('messages.remove_form',['form' => $message_form ]);
            }
        }
        if($request->has('is_required') && $request->is_required == 'is_required'){
            $message =  __('messages.added_form',['form' => $message_form ]);
            if($request->status == 0){
                $message = __('messages.remove_form',['form' => $message_form ]);
            }
        }
        if($request->has('provider_is_verified') && $request->provider_is_verified == 'provider_is_verified'){
            $message =  __('messages.added_form',['form' => $message_form ]);
            if($request->status == 0){
                $message = __('messages.remove_form',['form' => $message_form ]);
            }
        }
        return comman_custom_response(['message'=> $message , 'status' => true]);
    }

    public function getAjaxList(Request $request)
    {
        $items = array();
        $value = $request->q;
        $auth_user = authSession();
        switch ($request->type) {
            case 'permission':
                $items = \App\Models\Permission::select('id','name as text')->whereNull('parent_id');
                if($value != ''){
                    $items->where('name', 'LIKE', $value.'%');
                }
                $items = $items->get();
                break;
            case 'category':
                $items = \App\Models\Category::select('id','name as text')->where('status',1);

                if($value != ''){
                    $items->where('name', 'LIKE', '%'.$value.'%');
                }

                $items = $items->get();
                break;
            case 'provider':
                    $items = \App\Models\User::select('id','display_name as text')
                    ->where('user_type','provider')
                    ->where('status',1);

                    if($value != ''){
                        $items->where('display_name', 'LIKE', $value.'%');
                    }

                    $items = $items->get();
                    break;

            case 'user':
                $items = \App\Models\User::select('id','display_name as text')
                ->where('user_type','user')
                ->where('status',1);

                if($value != ''){
                    $items->where('display_name', 'LIKE', $value.'%');
                }

                $items = $items->get();
                break;

            case 'handyman':
                $items = \App\Models\User::select('id','display_name as text')
                ->where('user_type','handyman')
                ->where('status',1);

                if(isset($request->provider_id)){
                    $items->where('provider_id', $request->provider_id);
                }

                if(isset($request->booking_id)){
                    $booking_data = Booking::find($request->booking_id);

                    $service_address = $booking_data->handymanByAddress;
                    if($service_address != null)
                    {
                        $items->where('service_address_id', $service_address->id);
                    }
                }

                if($value != ''){
                    $items->where('display_name', 'LIKE', $value.'%');
                }

                $items = $items->get();
                break;
            case 'service':
                        $items = \App\Models\Service::select('id','name as text')->where('status',1);

                        if($value != ''){
                            $items->where('name', 'LIKE', '%'.$value.'%');
                        }
                        if(isset($request->provider_id)){
                            $items->where('provider_id', $request->provider_id);
                        }

                        $items = $items->get();
                        break;
            case 'providertype':
                        $items = \App\Models\ProviderType::select('id','name as text')
                            ->where('status',1);

                        if($value != ''){
                            $items->where('name', 'LIKE', $value.'%');
                        }

                        $items = $items->get();
                        break;
            case 'coupon':
                        $items = \App\Models\Coupon::select('id','code as text')->where('status',1);

                        if($value != ''){
                            $items->where('code', 'LIKE', '%'.$value.'%');
                        }

                        $items = $items->get();
                        break;
            case 'country':
                $items = \App\Models\Country::select('id','name as text');

                    if($value != ''){
                        $items->where('name', 'LIKE', $value.'%');
                    }
                    $items = $items->get();
                    break;
            case 'state':
                $items = \App\Models\State::select('id','name as text');
                    if(isset($request->country_id)){
                        $items->where('country_id', $request->country_id);
                    }
                    if($value != ''){
                        $items->where('name', 'LIKE', $value.'%');
                    }
                    $items = $items->get();
                    break;
            case 'city':
                $items = \App\Models\City::select('id','name as text');
                    if(isset($request->state_id)){
                        $items->where('state_id', $request->state_id);
                    }
                    if($value != ''){
                        $items->where('name', 'LIKE', $value.'%');
                    }
                    $items = $items->get();
                    break;
            case 'booking_status':
                $items = \App\Models\BookingStatus::select('id','label as text');

                    if($value != ''){
                        $items->where('label', 'LIKE', $value.'%');
                    }
                    $items = $items->get();
                    break;
            case 'currency':
                    $items = \DB::table('countries')->select(\DB::raw('id id,CONCAT(name , " ( " , symbol ," ) ") text'));

                    $items->whereNotNull('symbol')->where('symbol','!=','');
                    if($value != ''){
                        $items->where('name', 'LIKE', $value.'%')->orWhere('currency_code','LIKE',$value.'%');
                    }
                    $items = $items->get();
                    break;
            case 'country_code':
                    $items = \DB::table('countries')->select(\DB::raw('code id,name text'));
                    if($value != ''){
                        $items->where('name', 'LIKE', $value.'%')->orWhere('code','LIKE',$value.'%');
                    }
                    $items = $items->get();
                    break;

            case 'time_zone':
                $items = timeZoneList();

                foreach ($items as $k => $v) {

                    if($value !=''){
                        if (strpos($v, $value) !== false) {

                        } else {
                            unset($items[$k]);
                        }
                    }
                }

                $data = [];
                $i = 0;
                foreach ($items as $key => $row){
                    $data[$i] = [
                        'id'    => $key,
                        'text'  => $row,
                    ];
                    $i++;
                }
                $items = $data;
                break;
            case 'provider_address':
                $provider_id = !empty($request->provider_id) ? $request->provider_id : $auth_user->id;
                $items = \App\Models\ProviderAddressMapping::select('id','address as text','latitude','longitude','status')->where('provider_id', $provider_id)->where('status',1);
                $items = $items->get();
                break;

            case 'provider_tax':
                $provider_id = !empty($request->provider_id) ? $request->provider_id : $auth_user->id;
                $items = \App\Models\Tax::select('id','title as text')->where('status',1);
                $items = $items->get();
                break;

            case 'documents':
                $items = \App\Models\Documents::select('id','name','status' ,'is_required', \DB::raw('(CASE WHEN is_required = 1 THEN CONCAT(name," * ") ELSE CONCAT(name,"") END) AS text'))->where('status',1);
                if($value != ''){
                    $items->where('name', 'LIKE', $value.'%');
                }
                $items = $items->get();
                break;
            case 'handymantype':
                $items = \App\Models\HandymanType::select('id','name as text')
                    ->where('status',1);

                if($value != ''){
                    $items->where('name', 'LIKE', $value.'%');
                }

                $items = $items->get();
                break;
            case 'subcategory_list':
                $category_id = !empty($request->category_id) ? $request->category_id : '';
                $items = \App\Models\SubCategory::select('id','name as text')->where('category_id', $category_id)->where('status',1);
                $items = $items->get();
                break;
            default :
                break;
        }
        return response()->json(['status' => 'true', 'results' => $items]);
    }

    public function removeFile(Request $request){
        if(demoUserPermission()){
            $message = __('messages.demo_permission_denied');
            $response = [
                'status'    => false,
                'message'   => $message
            ];

            return comman_custom_response($response);
        }

        $type = $request->type;
        $data = null;
        switch ($type) {
            case 'slider_image':
                $data = Slider::find($request->id);
                $message = __('messages.msg_removed',['name' => __('messages.slider') ]);
                break;
            case 'profile_image':
                    $data = User::find($request->id);
                    $message = __('messages.msg_removed',['name' => __('messages.profile_image') ]);
                    break;
            case 'service_attachment':
                $media = Media::find($request->id);
                $media->delete();
                $message = __('messages.msg_removed',['name' => __('messages.attachments')] );
                break;
            case 'category_image':
                $data = Category::find($request->id);
                $message = __('messages.msg_removed',['name' => __('messages.category') ]);
                break;
            case 'provider_document':
                $data = ProviderDocument::find($request->id);
                $message = __('messages.msg_removed',['name' => __('messages.providerdocument') ]);
                break;
            case 'booking_attachment':
                $media = Media::find($request->id);
                $media->delete();
                $message = __('messages.msg_removed',['name' => __('messages.attachments')] );
                break;
            default:
                $data = AppSetting::find($request->id);
                $message = __('messages.msg_removed',['name' => __('messages.image')] );
            break;
        }

        if($data != null){
            $data->clearMediaCollection($type);
        }

        $response = [
            'status'    => true,
            'image'     => getSingleMedia($data,$type),
            'id'        => $request->id,
            'preview'   => $type."_preview",
            'message'   => $message
        ];

        return comman_custom_response($response);
    }

    public function lang($locale)
    {
        \App::setLocale($locale);
        session()->put('locale', $locale);

        return redirect()->back();
    }

    function authLogin()
    {
        return view('auth.login');
    }
    function authRegister()
    {
        return view('auth.register');
    }

    function authRecoverPassword()
    {
        return view('auth.forgot-password');
    }

    function authConfirmEmail()
    {
        return view('auth.verify-email');
    }

    function verifiedSuccess()
    {
        return view('auth.verified-success');
    }
}
