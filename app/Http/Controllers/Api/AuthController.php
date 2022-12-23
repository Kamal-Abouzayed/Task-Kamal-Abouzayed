<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\LoginRequest;
use App\Http\Requests\Api\RegisterRequest;
use App\Http\Requests\Api\VerifyRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    use ApiResponse;

    public function register(RegisterRequest $request)
    {
        $data = $request->all();

        if ($request->password) {
            $data['password'] = bcrypt($request->password);
        }

        $user = User::create($data);

        if ($user) {

            $code = rand(100000, 999999);

            $user->update(['code' => $code]);

            Log::channel('verification')->info("Your verification code is " . $code);

            return $this->apiResponse(
                'Your account has been registered successfully',
                [
                    'user' => new UserResource($user),
                    'token' => $user->createToken('token')->plainTextToken
                ],
                201
            );
        } else {
            return $this->apiResponse('Something went wrong while registering your email, Please try again', [], 422);
        }
    }

    public function login(LoginRequest $request)
    {

        $user = User::firstWhere('phone', $request->phone);

        if ($user && $user->isVerified == 1) {
            if (Hash::check($request->password, $user->password)) {
                return $this->apiResponse(
                    "You're logged in",
                    [
                        'user' => new UserResource($user),
                        'token' => $user->createToken('token')->plainTextToken
                    ],
                    200
                );
            } else {
                return $this->apiResponse('Your phone or password are wrong, Please try again', [], 422);
            }
        } else {
            return $this->apiResponse('Your account is not verified', [], 422);
        }
    }

    public function verifyUser(Request $request)
    {

        $user = User::where([['phone', $request->phone], ['code', $request->code]]);

        if ($user) {

            $user->update(['code' => null, 'isVerified' => 1]);

            return $this->apiResponse('Your account is verified', [], 200);
        } else {
            return $this->apiResponse('No matching result, Please try again', [], 404);
        }
    }
}
