<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use App\Services\UserService;
use App\Http\Requests\SignupRequest;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\UpdateUserRequest;

class UserController extends Controller
{
    /**
     * @var UserService
     */
    protected $userService;

    /**
     * UserController constructor.
     * @param UserService $userService
     */
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('index', User::class);
        $users = $this->userService->getListUser($request);

        return response()->json($users);
    }

    /**
     * Display the specified resource.
     *
     * @param  User $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        $currentUser = Auth::user();
        $this->authorize('show', $currentUser, $user);

        return response()->json($user);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  SignupRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(SignupRequest $request)
    {
        $this->authorize('create', User::class);
        $user = $this->userService->createUser($request);
        $user->addToIndex();

        return response()->json([
            'message' => trans('auth.successfully_created_user'),
            'user' => $user
        ], 201);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateUserRequest $request
     * @param  User $user
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $currentUser = Auth::user();
        $this->authorize('update', $currentUser, $user);
        $response = $this->userService->updateUser($user, $request);
        $user->updateIndex();

        return response()->json([
            'success' => $response,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $this->authorize('delete', User::class);
        $response = $this->userService->deleteUser($user);
        $user->removeFromIndex();

        return response()->json([
            'success' => $response,
        ]);
    }
}
