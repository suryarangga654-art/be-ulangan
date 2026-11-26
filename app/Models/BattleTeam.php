<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BattleTeam extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
    ];

    public function pokemons(): HasMany
    {
        return $this->hasMany(MyPokemon::class, 'battle_team_id')->orderBy('dex_number');
    }
}