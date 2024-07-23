<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Http\Requests\StoreGameRequest;
use App\Http\Requests\UpdateGameRequest;
use App\Models\User;

class GameController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $games = Game::all();

        return response()->json($games)->games()->getSuccessPercentage();
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
    public function store($id)
    {
        $user = User::find($id);

        if(!$user) {
            response()->json(['error' => 'User not found'], 404);
        }

        $dice1 = fake()->numberBetween(1, 6);
        $dice2 = fake()->numberBetween(1, 6);
        $result = ($dice1 + $dice2 == 7) ? 'W' : 'L';

        $game = $user->games()->create([
            'dice1' => $dice1,
            'dice2' => $dice2,
            'result' => $result,
        ]);

        return response()->json([
            'message' => 'Game created successfully',
            'game' => $game
        ], 201);      
    }

    /**
     * Display the specified resource.
     */
    public function show(Game $game)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Game $game)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateGameRequest $request, Game $game)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $user = User::find($id);

        if(!$user) {
            return response()->json([
                'error' => 'User not found'
            ], 404);
        }

        $user->games()->delete();

        return response()->json([
            'message' => 'Games for this user deleted successfully.'
        ], 204);  
    }
}
