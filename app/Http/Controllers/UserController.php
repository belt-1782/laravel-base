<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Get the authenticated User
     *
     * @return JsonResponse
     */
    public function show($id)
    {
        $user = User::findOrFail($id);
        $member = Auth::user();

        $this->authorize('show', $user, $member);
        return response()->json(Auth::user());
    }
}
