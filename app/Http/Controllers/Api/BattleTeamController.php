<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BattleTeam;
use Illuminate\Http\JsonResponse;

class BattleTeamController extends Controller
{
    public function index(): JsonResponse
    {
        $teams = BattleTeam::with('pokemons')->get();

        return response()->json([
            'data' => $teams,
        ]);
    }

    public function show(BattleTeam $battleTeam): JsonResponse
    {
        $battleTeam->load('pokemons');

        return response()->json([
            'data' => $battleTeam,
        ]);
    }
}