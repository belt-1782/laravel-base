<?php

namespace App\Http\Controllers;

use App\Jobs\RegistedUser;
use App\Services\AuthService;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\SignupRequest;
use Illuminate\Support\Facades\Auth;
use App\Exceptions\LaravelBaseApiException;

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
     * @return JsonResponse
     */
    public function signup(SignupRequest $request)
    {
        $user = $this->authService->createUser($request);
        RegistedUser::dispatch($user);

        return response()->json([
            'message' => trans('auth.successfully_created_user'),
            'user' => $user
        ], 201);
    }

    /**
     * Login user and create token
     *
     * @param LoginRequest $request
     * @return JsonResponse
     * @throws LaravelBaseApiException
     */
    public function login(LoginRequest $request)
    {
        $token = $this->authService->login($request);
        return response()->json($token);
    }

    /**
     * Logout user (Revoke the token)
     *
     * @return JsonResponse
     */
    public function logout()
    {
        Auth::user()->token()->revoke();

        return response()->json([
            'message' => trans('auth.successfully_logged_out')
        ]);
    }
}
