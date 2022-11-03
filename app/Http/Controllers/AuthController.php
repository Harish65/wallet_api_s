<?php

    
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Validator;
use App\Models\User;
use Illuminate\Support\Facades\Log;
class AuthController extends Controller

{

        public function register(Request $request){

        $post_data = $request->validate([
                'name'=>'required|string',
                'email'=>'required|string|email|unique:users',
                'password'=>'required|min:8'
        ]);
 
            $user = User::create([
            'name' => $post_data['name'],
            'email' => $post_data['email'],
            'password' => Hash::make($post_data['password']),
            ]);
 
            $token = $user->createToken('authToken')->plainTextToken;
 
            return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            ]);
        }
 
         /**
     * Validate the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function userLogin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'         => 'required|email|exists:users,email',
            'password'      => 'required|min:6',
        ]);
   
        if($validator->fails()){
            return response()->json($validator->errors(), 422); 
        }
        try{
            if($user = User::where(['email' => $request->email])->first() ) {
                    if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){ 
                         $success['user'] =  $user;
                         $success['_token'] =  auth()->user()->createToken('Wallet')->plainTextToken;
                        return response()->json(['Success' => $success] , 200);
                    } else { 
                        return response()->json(['Message' => 'Email and Password is Invalid.'] , 200);
                    }         
            } else { 
                return response()->json(['Message' => 'Email and Password is Invalid.'] , 200);
            }  
        }catch (Exception $e){
            return response()->json(['Message'=> $e->getMessage()] , 500);
        }
        
    }

    }

