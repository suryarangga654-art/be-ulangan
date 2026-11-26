<?php

namespace Database\Seeders;

use App\Models\BattleTeam;
use Illuminate\Database\Seeder;

class BattleTeamSeeder extends Seeder
{
    public function run(): void
    {
        $teams = [
            [
                'name' => 'Tim Utama',
                'description' => 'Skuad inti untuk battle utama',
            ],
            [
                'name' => 'Tim Latihan',
                'description' => 'Pokémon yang sedang dilatih dan dinaikkan levelnya',
            ],
            [
                'name' => 'Cadangan',
                'description' => 'Penyimpanan rotasi dan opsi taktis di box',
            ],
        ];

        foreach ($teams as $team) {
            BattleTeam::firstOrCreate(
                ['name' => $team['name']],
                ['description' => $team['description']]
            );
        }
    }
}