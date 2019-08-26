<?php

namespace App\Http\Controllers;

use App\Services\AuthService;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\SignupRequest;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * @var AuthService
     */
    protected $authService;

    /**
     * AuthController constructor.
     * @param AuthService $authService
     */
    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * Create user
     *
     * @param  SignupRequest $request
     * @return [string] message
     * @return [json] user object
     */
    public function signup(SignupRequest $request)
    {
        $user = $this->authService->createUser($request);

        return response()->json([
            'message' => trans('auth.successfully_created_user'),
            'user' => $user
        ], 201);
    }

    /**
     * Login user and create token
     *
     * @param  LoginRequest $request
     * @return [string] access_token
     * @return [string] token_type
     * @return [string] expires_at
     */
    public function login(LoginRequest $request)
    {
        $token = $this->authService->login($request);

        return response()->json($token);
    }

    /**
     * Logout user (Revoke the token)
     *
     * @return [string] message
     */
    public function logout()
    {
         Auth::user()->token()->revoke();

         return response()->json([
            'message' => trans('auth.successfully_logged_out')
         ]);
    }

    /**
     * Get the authenticated User
     *
     * @return [json] user object
     */
    public function user()
    {
        return response()->json(Auth::user());
    }
}
