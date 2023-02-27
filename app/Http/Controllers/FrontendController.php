<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Service;
use App\Models\User;
use App\Models\Coupon;
use App\Models\Setting;
use App\Http\Resources\API\CategoryResource;
use App\Http\Resources\API\ServiceResource;
use App\Http\Resources\API\UserResource;
use App\Http\Resources\API\ServiceDetailResource;
use App\Http\Resources\API\BookingRatingResource;
use App\Http\Resources\API\CouponResource;

class FrontendController extends Controller
{
    public function index(){
        $locale = app()->getLocale();
        return view('auth.login',compact('locale'));
    }

    public function termAndCondition(){

        $termAndCondition = Setting::where('type','terms_condition')->pluck('value')->first();

        $locale = app()->getLocale();
        return view('frontend.term_and_condition',compact('locale','termAndCondition'));
    }

    public function privacyPolicy(){

        $privacyPolicy = Setting::where('type','privacy_policy')->pluck('value')->first();
        $locale = app()->getLocale();
        return view('frontend.privacy_policy',compact('locale','privacyPolicy'));
    }
}
