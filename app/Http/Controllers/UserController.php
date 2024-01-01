<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use Illuminate\Http\Request;

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
}
