<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ArticleController;
use App\Http\Controllers\Api\BattleTeamController;
use App\Http\Controllers\Api\MyPokemonController;
use App\Http\Controllers\Api\PokeApiController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Endpoint data Pokémon untuk List Page & Detail Page
Route::get('/pokeapi/pokemons', [PokeApiController::class, 'index']);
Route::get('/pokeapi/pokemons/{dexNumber}', [PokeApiController::class, 'show']);

// Endpoint tim induk (battle_teams)
Route::get('/battle-teams', [BattleTeamController::class, 'index']);
Route::get('/battle-teams/{battleTeam}', [BattleTeamController::class, 'show']);

// Endpoint koleksi/tim anak (my_pokemons)
Route::apiResource('my-pokemons', MyPokemonController::class);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::post('/logout', [AuthController::class, 'logout']);

    // Route::apiResource('articles', ArticleController::class);
    // Route::put('/articles/{id}', [ArticleController::class, 'update']);
    // Route::delete('/articles/{id}', [ArticleController::class, 'destroy']);
});

Route::get('/media/{path}', function (string $path) {
    abort_unless(str_starts_with($path, 'articles/'), 404);
    abort_unless(Storage::disk('public')->exists($path), 404);

    return Storage::disk('public')->response($path);
})->where('path', '.*');