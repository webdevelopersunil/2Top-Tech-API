<?php

namespace App\Http\Controllers;

use Illuminate\Auth\Events\Verified;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Models\User;

class VerifyEmailController extends Controller
{

    public function __invoke(Request $request): RedirectResponse
    {
        $user = User::find($request->route('id'));

        if ($user->hasVerifiedEmail()) {
        	dd("in 1st  if of verify");
            return redirect(env('FRONT_URL') . '/email/verify/already-success');
        }
        
        if ($user->markEmailAsVerified()) {
        	dd("in 2nd if of verify");
            event(new Verified($user));
        }else{
        	dd("in 2nd else of verify");
        }

        return redirect(env('FRONT_URL') . '/email/verify/success');
    }

 //    public function verifyEmail(\Illuminate\Foundation\Auth\EmailVerificationRequest $request)
	// {
	//     $request->fulfill();
	//     return response()->json(['code' => 200, 'message' => "Verified successfully"], 200);
	// }
}