<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
class UserController extends Controller
{
     /**
     * Display a listing of the resource.
     */
    public function index()
    {
         $users = User::whereIn('role', ['user', 'guest'])->with('games')->get();

         $userData = [];
         
         foreach($users as $user) {
            $userData[] = [
            'id' => $user->id,
            'nickname' => $user->nickname,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
            'successPercentage' => $user->getSuccessPercentage(),
            ];
         }

         return response()->json([
            'data' => $userData,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        $user = User::find($user);

        return response()->json($user)->with();
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        //
    }
}
