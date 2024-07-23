<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
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
        $data = $request->validated();

        if(empty($data['name'])){
            $data['name'] = 'Anonymous';
            $data['role'] = 'guest';
        } else {
            $data['role'] = 'user';
        }
        
        $user = User::create($data);

        return new UserResource($user);
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

    public function showLoser()
    {

        $users = User::whereIn('role', ['user', 'guest'])->with('games')->get();

        $minSuccessPercentage = 100;

        foreach($users as $user) {
            $successPercentage = $user->getSuccessPercentage();
            if($successPercentage < $minSuccessPercentage) {
                $minSuccessPercentage = $successPercentage;
                $loser = $user;
            }
        }

        return response()->json([
            'loser' => [
                'playerId' => $loser->id,
                'nickname' => $loser->nickname,
                'name' => $loser->name,
                'email' => $loser->email,
                'role' => $loser->role,
                'successPercentage' => $loser->getSuccessPercentage(),
                ]
            ]);
    }

    public function showWinner()
    {
        $users = User::whereIn('role', ['user', 'guest'])->with('games')->get();

        $maxSuccessPercentage = 0;
        $winner = null;

        foreach($users as $user) {
            $successPercentage = $user->getSuccessPercentage();
            if($successPercentage > $maxSuccessPercentage) {
                $maxSuccessPercentage = $successPercentage;
                $winner = $user;
            }
        }

        return response()->json([
            'winner' => [
                'playerId' => $winner->id,
                'nickname' => $winner->nickname,
                'name' => $winner->name,
                'email' => $winner->email,
                'role' => $winner->role,
                'successPercentage' => $winner->getSuccessPercentage(),
                ]
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
    public function update(UpdateUserRequest $request, $id)
    {
        $user = User::find($id);

        if(!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        $data = $request->validated();

        //hash the password
        if(isset($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        }

        //Change role to user if names is changed from anonymous
        if(isset($data['name']) && $data['name'] !== 'Anonymous') {
            $data['role'] = 'user';
        }

        //update the user
        $user->update($data);

        return response()->json([
            'message' => 'User updated successfully',
            'user' => $user
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        //
    }
}
