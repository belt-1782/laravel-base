<?php

namespace App\Services;

use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Exceptions\LaravelBaseApiException;

class UserService
{
    /**
     * Get list user.
     *
     * @return object
     */
    public function getListUser($request)
    {
        if($request->has('search')){
            return User::complexSearch(array(
                'body' => array(
                    'query' => array(
                        'multi_match' => array(
                            "query" => $request->input('search'),
                            "operator" => "and",
                            "type" => "cross_fields",
                            "fields" => [
                                "name",
                                "email"
                            ]
                        )
                    )
                )
            ));
        } else {
            return User::all();
        }
    }

    /**
     * Create a user.
     *
     * @return object
     */
    public function createUser($request)
    {
        $data = $request->only([
            'name',
            'email',
            'password',
        ]);
        $data['password'] = bcrypt($data['password']);

        return User::create($data)->assignRole('member');
    }

    /**
     * Update the user.
     *
     * @return bool
     */
    public function updateUser($user, $request)
    {
        $data = $request->only([
            'name',
            'email',
            'password',
        ]);
        $data['password'] = bcrypt($data['password']);

        return $user->update($data);
    }

    /**
     * Delete the user.
     *
     * @return object
     */
    public function deleteUser($user)
    {
        return $user->delete($user->id);
    }
}
