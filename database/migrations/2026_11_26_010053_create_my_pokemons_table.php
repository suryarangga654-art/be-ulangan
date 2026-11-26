<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('my_pokemons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('battle_team_id')->constrained('battle_teams')->cascadeOnDelete();
            $table->string('pokemon_name');
            $table->unsignedInteger('dex_number');
            $table->string('team_role')->nullable();
            $table->string('sprite_url')->nullable();
            $table->string('details_url')->nullable();
            $table->timestamps();

            $table->unique(['battle_team_id', 'dex_number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('my_pokemons');
    }
};
