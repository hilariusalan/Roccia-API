<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequestOtpRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Requests\UserVerivyOtpRequest;
use App\Http\Resources\UserResource;
use App\Mail\SendOtpMail;
use App\Models\OtpCode;
use App\Models\User;
use Exception;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserController extends Controller
{
    public function userRequestOtp(UserRequestOtpRequest $request): JsonResponse {
        try {
            $data = $request->validated();
    
            $decayMinutes = 1;
            $maxAttemps = 3;
            $key = 'send-otp: ' . $data->email;
    
            if (RateLimiter::tooManyAttempts($key, $maxAttemps)) {
                $second = RateLimiter::availableIn($key);
    
                throw new HttpResponseException(response()->json([
                    'error' => 'Too many otp request. Please try again after ' . $second . ' second' 
                ])->setStatusCode(429));
            }
    
            RateLimiter::hit($key, $decayMinutes * 60);
    
            $otp = rand(100000, 999999);
            $hashedOtp = Hash::make((string)$otp);
    
            OtpCode::create([
                'email' => $data->email,
                'otp' => $hashedOtp,
                'expires_at' => now()->addMinutes(5)
            ]);
    
            Mail::to($data->email)->send(new SendOtpMail($otp));
    
            return response()->json([
                'message' => 'Otp send successfully.'
            ])->setStatusCode(200);
        } catch (Exception $ex) {
            throw new HttpResponseException(response()->json([
                'error' => 'Something went wrong.',
                'message' => $ex->getMessage()
            ])->setStatusCode(500));
        } 
    }

    public function userVerifyOtp(UserVerivyOtpRequest $request): JsonResponse {
        try {
            $data = $request->validated();
    
            $decayMinutes = 1;
            $maxAttemps = 3;
            $key = 'verify-otp: ' . $data->email;
    
            if (RateLimiter::tooManyAttempts($key, $maxAttemps)) {
                $second = RateLimiter::availableIn($key);
    
                throw new HttpResponseException(response()->json([
                    'error' => 'Too many verification attemps. Please try again after ' . $second . ' second'
                ])->setStatusCode(429));
            }
    
            $otpRecord = OtpCode::where('email', $data->email)
                                ->where('expires_at', '>', now())
                                ->latest()
                                ->first();
    
            if (!$otpRecord || !Hash::check((string)$data->otp, $otpRecord->otp)) {
                RateLimiter::hit($key, $decayMinutes * 60);
    
                throw new HttpResponseException(response()->json([
                    'message' => 'Otp is not valid.'
                ])->setStatusCode(403));
            }
    
            RateLimiter::clear($key);
    
            $user = User::firstOrCreate(
                ['email' => $data->email],
                ['full_name' => '', 'is_admin' => false],
            );
    
            $otpRecord->delete();
    
            $token = JWTAuth::fromUser($user);
    
            return response()->json([
                'message' => 'Login successfully.',
                'data' => [
                    'id' => $user->id,
                    'email' => $user->email,
                    'full_name' => $user->full_name,
                    'token' => $token
                ]
            ]);
        } catch (Exception $ex) {
            throw new HttpResponseException(response()->json([
                'error' => 'Something went wrong.',
                'message' => $ex->getMessage()
            ])->setStatusCode(500));
        } 
    }

    public function getUserData(): JsonResponse {
        $user = Auth::user();

        return response()->json([
            'data' => new UserResource($user)
        ]);
    }

    public function updateUser(UserUpdateRequest $request): JsonResponse {
        try {
            $user = Auth::user();
    
            $decayMinutes = 1;
            $maxAttemps = 3;
            $key = 'update-user: ' . $user->email;
    
            if (RateLimiter::tooManyAttempts($key, $maxAttemps)) {
                $second = RateLimiter::availableIn($key);
    
                throw new HttpResponseException(response()->json([
                    'error' => 'Too many attemps. Please try again after ' . $second . ' second'
                ])->setStatusCode(429));
            }
    
            $userData = User::where('id', $user->id)->first();
            if (!$userData) {
                RateLimiter::hit($key, $decayMinutes * 60);
    
                throw new HttpResponseException(response()->json([
                    'error' => 'User not found.' 
                ])->setStatusCode(404));
            }
    
            RateLimiter::clear($key);
    
            $data = $request->validated();
            $userData->fill($data);
            $userData->save();
    
            return response()->json([
                'message' => 'Full Name updated successfully.',
                'data' => [
                    'id' => $userData->id,
                    'email' => $userData->email,
                    'full_name' => $userData->full_name,
                    'is_admin' => $userData->is_admin,
                    'updated_at' => $userData->updated_at
                ]
            ])->setStatusCode(200);
        } catch (Exception $ex) {
            throw new HttpResponseException(response()->json([
                'error' => 'Something went wrong.',
                'message' => $ex->getMessage()
            ])->setStatusCode(500));
        } 
    }

    public function logoutUser() {
        JWTAuth::invalidate(JWTAuth::getToken());
        
        return response()->json([
            'message' => 'Logout successful.'
        ])->setStatusCode(200);
    }
    
}
