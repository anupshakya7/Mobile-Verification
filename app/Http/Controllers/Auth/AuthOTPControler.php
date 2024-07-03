<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserOTP;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthOTPControler extends Controller
{
    public function login(){
        return view('auth.OTPLogin');
    }

    public function generate(Request $request){
        $request->validate([
            'mobile_no'=>'required|exists:users,mobile_no',
        ]);

        $userOTP = $this->generateOTP($request->mobile_no);
        $userOTP->sendSMS($request->mobile_no); //send otp

        return redirect()->route('otp.verification',$userOTP->user_id)->with('success','OTP has been sent on your Mobile Number!');
    }   

    public function generateOTP($mobile_no){
        $user = User::where('mobile_no',$mobile_no)->first();
        $userOTP = UserOTP::where('user_id',$user->id)->latest()->first();

        $now = now();
        if($userOTP && $now->isBefore($userOTP->expiry_at)){
            return $userOTP;
        }

        return UserOTP::create([
            'user_id'=>$user->id,
            'otp'=> rand(123456,999999),
            'expiry_at' => $now->addMinutes(10)
        ]);
    }

    public function verification($user_id){
        return view('auth.OTPVerification')->with(['user_id'=> $user_id]);
    }

    public function loginWithOTP(Request $request){
        $request->validate([
            'otp'=>'required',
            'user_id'=>'required|exists:users,id'
        ]);

        $userOTP = UserOTP::where('user_id',$request->user_id)->where('otp',$request->otp)->first();
        $now = now();
        if(!$userOTP){
            return redirect()->back()->with('error','Your OTP is not Correct');
        }elseif($userOTP && $now->isAfter($userOTP->expiry_at)){
            return redirect()->back()->with('error','Your OTP has been Expired');
        }

        $user = User::whereId($request->user_id)->first();

        if($user){
            $userOTP->update([
                'expiry_at'=>now()
            ]);

            Auth::login($user);
            return redirect('/home');
        }

        return redirect()->route('otp.login')->with('error','Your OTP is not correct');
    }
}
