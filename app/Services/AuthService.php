<?php

namespace App\Services;

use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class AuthService
{
    /**
     * @param SignupRequest $request
     * @return array
     */
    public function createUser($request)
    {
        $data = $request->only([
            'name',
            'email',
            'password',
        ]);

        $data['password'] = bcrypt($data['password']);

        return User::create($data);
    }

    /**
     * @param LoginRequest $request
     * @return array
     */
    public function login($request)
    {
        $credentials = $request->only(['email', 'password']);
        if (!Auth::attempt($credentials)) {
            return [
                'data' => ['message' => trans('auth.unauthorized')],
                'status' => 401
            ];
        }

        $user = Auth::user();
        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->token;

        if ($request->remember_me) {
            $token->expires_at = Carbon::now()->addWeek(1);
        }
        $token->save();

        return [
            'data' => ['access_token' => $tokenResult->accessToken,
                'token_type' => 'Bearer',
                'expires_at' => Carbon::parse(
                    $tokenResult->token->expires_at
                )->toDateTimeString()
            ],
            'status' => 200
        ];
    }
}
