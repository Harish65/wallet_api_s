<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class UsersController extends Controller{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }


    /**
     * Deposite money
     * @request
     * @return response 
    */
    // public function deposite(Request $request){
    //     $validator = Validator::make($request->all(), [
    //         'owner_id' => 'required | numeric',
    //         'owner_type' => 'required',
    //         'balance' => 'required|numeric|between:0,99.99'
    //     ]);
    //     if($validator->fails()){
    //         return response()->json($validator->errors(), 422);
    //     }
    //     try{
    //         $user = User::find($request->owner_id);  
    //         if($user){
    //             if($request->balance >= 3 && $request->balance <= 100 ) {                    
    //                 $user->wallet->deposit($request->balance); 
    //                 return response()->json(['message' => 'Success! $'.$request->balance.' added to your wallet'] , 404);
    //             }else{
    //                 $message = 'Balance should be min $3 or max $100.';
    //                 \Log::channel('wallet')->info($message);
    //                 return response()->json(['message' => $message] , 404);   
    //             }
                
    //         }else{
    //             $message = 'Record not found.';
    //             \Log::channel('wallet')->info($message);
    //             return response()->json(['message' => $message] , 404);
    //         }
    //     }catch(Exception $e){
    //         $message = 'Something went wrong!';
    //         \Log::channel('wallet')->info($message);
    //         return response()->json(['error'=> $message] , 500);
    //     }
    // }
    public function deposite(Request $request){
        $validator = Validator::make($request->all(), [
            'owner_id' => 'required | numeric',
            'owner_type' => 'required',
            'balance' => 'required|numeric|between:0,99.99'
        ]);
        if($validator->fails()){
            return response()->json($validator->errors(), 422);
        }
        try{
            $user = User::find($request->owner_id);  
            if($user){
                if($request->balance >= 3 && $request->balance <= 100 ) {                    
                    $user->wallet = $request->balance;
                    $user->save(); 
                    return response()->json(['message' => 'Success! $'.$request->balance.' added to your wallet'] , 404);
                }else{
                    $message = 'Balance should be min $3 or max $100.';
                    \Log::channel('wallet')->info($message);
                    return response()->json(['message' => $message] , 404);   
                }
                
            }else{
                $message = 'Record not found.';
                \Log::channel('wallet')->info($message);
                return response()->json(['message' => $message] , 404);
            }
        }catch(Exception $e){
            $message = 'Something went wrong!';
            \Log::channel('wallet')->info($message);
            return response()->json(['error'=> $message] , 500);
        }
    }
    /**
     * Buy Cookies money
     * @request
     * @return response 
    */
    public function buyCookies(Request $request){
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',           
            'quanity' => 'required'
        ]);
        if($validator->fails()){
            return response()->json($validator->errors(), 422);
        }        
        try{
            $user = User::find($request->user_id);  
            if($user){
                //check min balnce to buy cookie
                $balance = $user->wallet;
                if($balance < 1 && $request->quanity > $balance){
                    $message = 'Not enough balance to buy a cookie';
                    \Log::channel('wallet')->info($message);
                    return response()->json(['message' => $message] , 422);   
                }else{
                    $user->wallet = $user->wallet - $request->quanity;
                    $balance = $user->wallet;
                    $message =  'Succefully! Buy '. $request->quanity .' Cookies. Reaming balance is '. $balance; 
                    return response()->json(['message' => $message] , 200);    
                }             
            }else{
                $message = 'Record not found.';
                \Log::channel('wallet')->info($message);
                return response()->json(['message' => $message] , 404);
            }
        }catch(Exception $e){
            \Log::channel('wallet')->info('Something went wrong!');
            return response()->json(['Message'=> $e->getMessage()] , 500);
        }
    }


    

}
