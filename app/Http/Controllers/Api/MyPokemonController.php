<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MyPokemon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MyPokemonController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = MyPokemon::with('battleTeam')->orderBy('dex_number');

        if ($request->filled('battle_team_id')) {
            $query->where('battle_team_id', $request->integer('battle_team_id'));
        }

        return response()->json([
            'data' => $query->get(),
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'battle_team_id' => ['required', 'exists:battle_teams,id'],
            'pokemon_name' => ['required', 'string', 'max:100'],
            'dex_number' => ['required', 'integer', 'min:1'],
            'team_role' => ['nullable', 'string', 'max:100'],
        ]);

        $dexNumber = (int) $validated['dex_number'];
        $validated['dex_number'] = $dexNumber;
        $validated['sprite_url'] = "https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/{$dexNumber}.png";
        $validated['detail_url'] = "https://pokeapi.co/api/v2/pokemon/{$dexNumber}/";

        $pokemon = MyPokemon::create($validated);
        $pokemon->load('battleTeam');

        return response()->json([
            'message' => 'Pokémon berhasil ditambahkan ke tim.',
            'data' => $pokemon,
        ], 201);
    }

    public function show(MyPokemon $myPokemon): JsonResponse
    {
        $myPokemon->load('battleTeam');

        return response()->json([
            'data' => $myPokemon,
        ]);
    }

    public function update(Request $request, MyPokemon $myPokemon): JsonResponse
    {
        $validated = $request->validate([
            'battle_team_id' => ['sometimes', 'exists:battle_teams,id'],
            'pokemon_name' => ['sometimes', 'string', 'max:100'],
            'dex_number' => ['sometimes', 'integer', 'min:1'],
            'team_role' => ['nullable', 'string', 'max:100'],
        ]);

        if (isset($validated['dex_number'])) {
            $dexNumber = (int) $validated['dex_number'];
            $validated['dex_number'] = $dexNumber;
            $validated['sprite_url'] = "https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/{$dexNumber}.png";
            $validated['detail_url'] = "https://pokeapi.co/api/v2/pokemon/{$dexNumber}/";
        }

        $myPokemon->update($validated);
        $myPokemon->load('battleTeam');

        return response()->json([
            'message' => 'Data Pokémon berhasil diperbarui.',
            'data' => $myPokemon,
        ]);
    }

    public function destroy(MyPokemon $myPokemon): JsonResponse
    {
        $myPokemon->delete();

        return response()->json([
            'message' => 'Pokémon berhasil dihapus dari tim.',
        ]);
    }
}