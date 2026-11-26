<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MyPokemon extends Model
{
    use HasFactory;

    protected $table = 'my_pokemons';

    protected $fillable = [
        'battle_team_id',
        'pokemon_name',
        'dex_number',
        'team_role',
        'sprite_url',
        'detail_url',
    ];

    protected $casts = [
        'dex_number' => 'integer',
    ];

    public function battleTeam(): BelongsTo
    {
        return $this->belongsTo(BattleTeam::class, 'battle_team_id');
    }
}