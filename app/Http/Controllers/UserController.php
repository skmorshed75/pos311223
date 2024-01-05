<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use App\Mail\OTPMail;
use App\Helper\JWTToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

use function Psy\debug;

class UserController extends Controller
{
    function UserRegistration(Request $request){
        try{
            User::create([
                'firstName' => $request->input('firstName'),
                'lastName' => $request->input('lastName'),
                'email' => $request->input('email'),
                'mobile' => $request->input('mobile'),
                'password' => $request->input('password')
            ]);
    
            return response()->json([
                'status' => 'success',
                'message' => 'User Registration Successful'
            ],200);
        }
        catch(Exception $e){
            return response()->json([
                'status' => 'fail',
                //'message' => 'User Registration Failed'
                'message' => $e->getMessage()
            ],400);
        };
    }

    function UserLogin(Request $request){
        $count = User::where('email','=', $request->input('email'))
            ->where('password','=', $request->input('password'))
            ->count();

        if($count == 1){
            //User Login and issue JWT Token
            $token = JWTToken::CreateToken($request->input('email'));

            return response()->json([
                'status' => 'success',
                'message' => 'User Login is Successful',
                'token' => $token

            ]);

        } else {
            return response()->json([
                'status' => 'failed',
                'message' => 'unauthorised'
            ], 200);
        };

            

    }

    //OTP SENDING & VERIFICATION
    function SendOTPCode(Request $request){
        $email = $request->input('email');
        $otp = rand(1000,9999);
        $count = User::where('email','=',$email)->count();
        if($count == 1){
            //OTP Send
            Mail::to($email)->send(new OTPMail($otp));
            //Insert OTP code into table
            User::where('email','=',$email)->update(['otp'=>$otp]);

            return response()->json([
                'status' => 'success',
                'message' => '4 digit OTP Code sent to your email address'
            ], 200);
        } else {
            return response()->json([
                'status' => 'failed',
                'message' => 'unauthorised',
 
            ],200);
        }
    }

    //VERIFY OTP AND EMAIL
    function VerifyOTP(Request $request){  
        $email = $request->input('email');
        $otp = $request->input('otp');

        $count = User::where('email','=',$email)
        ->where('otp','=',$otp)
        ->count();
      
        if($count == 1){
            //Database Update
            User::where('email','=',$email)->update(['otp'=>'0']);

            //Password Reset Token Issue
            $token = JWTToken::CreateTokenResetPassword($request->input('email'));
            return response()->json([
                "status"=>"success",
                "message"=>"OTP Verification is successful",
                'token' => $token
            ],200);

        } else {

            return response()->json([
                "status" => "failed",
                "message" => "OTP Verification is failed"
            ],200);

        }
    }
}
