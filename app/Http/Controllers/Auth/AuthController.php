<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * User Login
     * @return json
     */
    public function login(Request $request)
    {
        // data validaton
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'data' => [
                    'inputs' => $request->input(),
                ],
                'errors' => $validator->errors()->all(),
                'message' => 'Validation Failed',
                'error' => true,

            ], 200);
        }

        $credentials = [
            'email' => $request->email,
            'password' => $request->password,
        ];

        if (!auth()->attempt($credentials)) {
            return response()->json([
                'data' => [
                    'inputs' => $request->input(),
                ],
                'message' => 'Invalid Credentials',
                'error' => true,
            ], 401);
        }

        $user = auth()->user();
        // check account status
        if ($user->status == '0') {
            auth()->logout();
            return response()->json([
                'error' => true,
                'message' => 'Your account is deactivated. Please contact with administrator.',
            ], 401);
        }

        $accessToken = auth()->user()->createToken('authToken')->accessToken;
        return response()->json([
            'data' => [
                'user' => $user,
                'access_token' => $accessToken
            ],
            'message' => 'Login successful',
            'error' => false,
        ]);
    }

    public function logout(Request $request)
    {
        $token = $request->user()->token();
        $token->revoke();
        return response()->json([

            'message' => 'Logout Successful',
            'error' => false,
        ]);
    }
}
