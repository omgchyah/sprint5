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
            'playerId' => $user->id,
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
    public function show($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        $games = $user->games;
        $gamesArray = [];

        foreach($games as $game) {
            $gamesArray[] = [
                'gameId' => $game->id,
                'dice1' => $game->dice1,
                'dice2' => $game->dice2,
                'result' => $game->result,
            ];
        }

        return response()->json([
            'playerId' => $user->id,
            'nickname' => $user->nickname,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
            'successPercentage' => $user->getSuccessPercentage(),
            'games' => $gamesArray,
        ]);
    }

    public function averageSuccessRanking()
    {
        $averageSuccessPercentage = User::getSuccessAveragePercentage();

        return response()->json([
            'averageSuccessPercentage' => $averageSuccessPercentage,
        ]);
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
