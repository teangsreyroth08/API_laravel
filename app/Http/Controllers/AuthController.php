<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Mail;

use App\Mail\OTPMail;
use App\Models\Role;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:6',
            ]);

            $user = User::create([
                'name'      => $request->name,
                'email'     => $request->email,
                'password'  => Hash::make($request->password),
                'role_id'   => $request->role_id
            ]);



            return response()->json(
            [
                'status'  => 'success',
                'data'    => $user,
                'message' => 'User creagted successfully'
            ], 201);
        } catch (QueryException $e) {
            return response()->json(
            [
                'status'  => 'fail',
                'message' => 'Database error: ' . $e->getMessage()
            ], 500);
        } catch (\Exception $e) {
            return response()->json(
            [
                'status'  => 'fail',
                'message' => 'Something went wrong: ' . $e->getMessage()
            ], 500);
        }
    }

    public function login(Request $request)
    {
        try {
            $credentials = $request->only('email', 'password');

            if (!$token = Auth::attempt($credentials)) {
                return response()->json(
                [
                    'status'  => 'fail',
                    'message' => 'Invalid Credential'
                ], 401);
            }
            return $this->respondWithToken($token);
        } catch (\Exception $e) {
            return response()->json(
            [
                'status'  => 'fail',
                'message' => 'Something went wrong: ' . $e->getMessage()
            ], 500);
        }
    }

    public function user()
    {
        try {
            return response()->json(Auth::user());
        } catch (\Exception $e) {
            return response()->json(
            [
                'status'  => 'fail',
                'message' => 'Something went wrong: ' . $e->getMessage()
            ], 500);
        }
    }

    public function logout()
    {
        try {
            Auth::logout();
            return response()->json(
            [
                'status' => 'success',
                'message' => 'Successfully logged out'
            ], 200);
        } catch (\Exception $e) {
            return response()->json(
            [
                'status'  => 'fail',
                'message' => 'Something went wrong: ' . $e->getMessage()
            ], 500);
        }
    }

    // Helper function to respond with a token
    protected function respondWithToken($token)
    {
        try {
            return response()->json([
                'access_token' => $token,
                'token_type' => 'bearer',
                'expires_in' => Auth::factory()->getTTL() * 60,
                'role' => Auth::user()->role->name
            ]);
        } catch (\Exception $e) {
            return response()->json(
            [
                'status'  => 'fail',
                'message' => 'Something went wrong: ' . $e->getMessage()
            ], 500);
        }
    }

    public function verifyOTP(Request $req){

        $user = User::select('id','role_id', 'email', 'name', 'otp' ,'otp_sent_at','otp_verified_at')
        ->where('email',$req->email)
        ->first();

        $checkOTPExpire = Carbon::now();
        $opt_send_at = Carbon::parse($user->otp_sent_at);

        if($opt_send_at->diffInSeconds($checkOTPExpire) >= 60){ // if opt sent over 60 seconds expires
            return response()->json([
                'status'  => 'fail',
                'message' => 'OTP exprired'
            ], Response::HTTP_BAD_REQUEST);
        }

        if($user->otp != $req->otp){   // Check if OTP is compatible

            return response()->json([
                'status'  => 'fail',
                'message' => 'OTP invalid'
            ], Response::HTTP_BAD_REQUEST);
        }
        $user->otp_verified_at = Date('Y-m-d H:i:s');
        $user->is_active = 1 ;
        $user->save();


        return response()->json([
            'data'    => $user,
            'message' => 'OTP has been verified successfully'
        ], Response::HTTP_OK);

    }

    // send new otp code when otp expired
    function sendOTP(Request $req){

        $user = User::select('id','role_id', 'email', 'name', 'otp' ,'otp_sent_at','otp_verified_at')
        ->where('email',$req->email)
        ->first();

        if(!$user){

            return response()->json([
                'status'  => 'fail',
                'message' => 'User not found'
            ], Response::HTTP_BAD_REQUEST);
        }
        $this->_sentOTP($user);

        return response()->json([
            'data'    => $user,
            'message' => 'OTP has been sent to your email, please check your email and verify'
        ], Response::HTTP_OK);

    }

    function newPassword(Request $req){

        // ================================================>> Check user
        $user = User::select('id','role_id', 'email', 'name', 'otp' ,'otp_sent_at','otp_verified_at')
        ->where('email',$req->email)
        ->first();


        $checkOTPExpire = Carbon::now();
        $opt_send_at = Carbon::parse($user->otp_sent_at);

        if($opt_send_at->diffInSeconds($checkOTPExpire) >= 60){ // if opt sent over 60 seconds expires
            return response()->json([
                'status'  => 'fail',
                'message' => 'OTP Expired'
            ], Response::HTTP_BAD_REQUEST);
        }

        if($user->otp != $req->otp){   // Check if OTP is compatible

            return response()->json([
                'status'  => 'fail',
                'message' => 'Invalid OTP Code'
            ], Response::HTTP_BAD_REQUEST);
        }

        $user->password = Hash::make($req->new_password);
        $user->save();


        return response()->json([
            'data'    => $user,
            'message' => 'Password changed successfully'
        ], Response::HTTP_OK);
    }

    public function roles(){
        $roles = Role::all();

        return response()->json([
            'data'    => $roles,
            'message' => 'all roles'
        ], Response::HTTP_OK);
    }

    public function profile(){
        try {

            $user = User::select('id', 'name', 'email', 'phone_number', 'address', 'is_active', 'role_id')
            ->with(['role:id,name'])
            ->findOrfail(Auth::user()->id);


            if(!$user){
                return response()->json([
                    'status'  => 'fail',
                    'message' => 'User not found'
                ], Response::HTTP_BAD_REQUEST);
            }
            return response()->json(
                [
                    'status'  => 'success',
                    'data'    => $user,
                    'message' => ' User found successfully'
                ]
            , 201);

        } catch (QueryException $e) {
            return response()->json(
            [
                'status'  => 'fail',
                'message' => 'Database error: ' . $e->getMessage()
            ], 500);
        } catch (\Exception $e) {
            return response()->json(
            [
                'status'  => 'fail',
                'message' => 'Something went wrong: ' . $e->getMessage()
            ], 500);
        }
    }

    public function update_profile(Request $request)
    {
        try {
            $request->validate([
                'name'      => 'required|string|max:255',
                'email'     => 'required|string|email|max:255',
                'is_active' => 'required'
            ]);

            $user = User::select('id', 'name', 'email', 'phone_number', 'address', 'is_active', 'role_id', 'password')
            ->findOrfail(Auth::user()->id);


            if(!$user){
                return response()->json([
                    'status'  => 'fail',
                    'message' => 'User not found'
                ], 500);
            }

            $user->name          = $request->name;
            $user->email         = $request->email;
            $user->phone_number  = $request->phone_number??null;
            $user->address       = $request->address??null;
            $user->is_active     = $request->is_active;

            if ($request->current_password && $request->new_password){
                $check_pwd = Hash::check($request->current_password, $user->password);

                if(!$check_pwd){
                    return response()->json(
                    [
                        'status'  => 'fail',
                        'message' => 'Incorrect Current Password!'
                    ], 500);
                }
                $user->password  = Hash::make($request->new_password);
            }

            $user->save();

            return response()->json(
                [
                    'status'  => 'success',
                    'data'    => $user,
                    'message' => 'Profile updated successfully'
                ]
            , 201);

        } catch (QueryException $e) {
            return response()->json(
            [
                'status'  => 'fail',
                'message' => 'Database error: ' . $e->getMessage()
            ], 500);
        } catch (\Exception $e) {
            return response()->json(
            [
                'status'  => 'fail',
                'message' => 'Something went wrong: ' . $e->getMessage()
            ], 500);
        }
    }

    private function _sentOTP($user = null){

        $user->otp = Rand(100000, 999999);
        $user->otp_sent_at = Date('Y-m-d H:i:s');
        $user->save();

        //send opt to email of user
        $this->_sendEmail($user);

    }

    private function _sendEmail($user = null){

        $data = [
            'toEmail'   => $user->email,
            'fromName'  => 'Lifeless Clinic',
            'fromEmail' => 'tongmeng016@gmail.com',
            'otp'       => $user->otp,
            'subject'   => 'New Password Verification',
            'body'      => 'Your OTP code below ',
            'footer'    => 'Thanks for your participating'
        ];
        // sent opt code to user gmail
        Mail::to($user->email)->send(new OTPMail($data));
    }

}