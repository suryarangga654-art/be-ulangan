<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PokeApiController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $limit = (int) $request->query('limit', 50);
        $offset = (int) $request->query('offset', 0);

        $rawData = null;

        try {
            $response = Http::timeout(10)->get('https://pokeapi.co/api/v2/pokemon', [
                'limit' => $limit,
                'offset' => $offset,
            ]);

            if ($response->successful()) {
                $rawData = $response->json();
            }
        } catch (\Throwable $e) {
            $rawData = null;
        }

        // Fallback data dummy jika koneksi eksternal gagal
        if (! $rawData) {
            $rawData = $this->fallbackDummyData($limit, $offset);
        }

        $items = collect($rawData['results'] ?? [])->map(function (array $item) {
            $detailUrl = $item['url'] ?? '';
            $dexNumber = $this->extractIdFromUrl($detailUrl);

            return [
                // Wajib di List Page
                'dex_number' => $dexNumber,
                'name' => $item['name'] ?? '',
                // Tambahan wajib di Detail Page
                'sprite_url' => $dexNumber
                    ? "https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/{$dexNumber}.png"
                    : null,
                'detail_url' => $detailUrl,
            ];
        })->values();

        return response()->json([
            'count' => $rawData['count'] ?? $items->count(),
            'next' => $rawData['next'] ?? null,
            'previous' => $rawData['previous'] ?? null,
            'data' => $items,
        ]);
    }

    public function show(int $dexNumber): JsonResponse
    {
        $detailUrl = "https://pokeapi.co/api/v2/pokemon/{$dexNumber}/";
        $spriteUrl = "https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/{$dexNumber}.png";

        try {
            $response = Http::timeout(10)->get($detailUrl);

            if ($response->successful()) {
                $json = $response->json();

                return response()->json([
                    'dex_number' => $dexNumber,
                    'name' => $json['name'] ?? null,
                    'sprite_url' => $json['sprites']['front_default'] ?? $spriteUrl,
                    'detail_url' => $detailUrl,
                    'types' => collect($json['types'] ?? [])->pluck('type.name')->values(),
                    'stats' => collect($json['stats'] ?? [])->map(fn ($s) => [
                        'name' => $s['stat']['name'] ?? '',
                        'base_stat' => $s['base_stat'] ?? 0,
                    ])->values(),
                    'height' => $json['height'] ?? null,
                    'weight' => $json['weight'] ?? null,
                ]);
            }
        } catch (\Throwable $e) {
            // Lanjut ke respons dasar jika gagal fetch
        }

        return response()->json([
            'dex_number' => $dexNumber,
            'sprite_url' => $spriteUrl,
            'detail_url' => $detailUrl,
        ]);
    }

    private function extractIdFromUrl(string $url): ?int
    {
        $trimmed = trim($url, '/');
        $segments = explode('/', $trimmed);
        $last = end($segments);

        return is_numeric($last) ? (int) $last : null;
    }

    private function fallbackDummyData(int $limit, int $offset): array
    {
        $dummy = [
            ["name" => "bulbasaur", "url" => "https://pokeapi.co/api/v2/pokemon/1/"],
            ["name" => "ivysaur", "url" => "https://pokeapi.co/api/v2/pokemon/2/"],
            ["name" => "venusaur", "url" => "https://pokeapi.co/api/v2/pokemon/3/"],
            ["name" => "charmander", "url" => "https://pokeapi.co/api/v2/pokemon/4/"],
            ["name" => "charmeleon", "url" => "https://pokeapi.co/api/v2/pokemon/5/"],
            ["name" => "charizard", "url" => "https://pokeapi.co/api/v2/pokemon/6/"],
            ["name" => "squirtle", "url" => "https://pokeapi.co/api/v2/pokemon/7/"],
            ["name" => "wartortle", "url" => "https://pokeapi.co/api/v2/pokemon/8/"],
            ["name" => "blastoise", "url" => "https://pokeapi.co/api/v2/pokemon/9/"],
            ["name" => "caterpie", "url" => "https://pokeapi.co/api/v2/pokemon/10/"],
            ["name" => "metapod", "url" => "https://pokeapi.co/api/v2/pokemon/11/"],
            ["name" => "butterfree", "url" => "https://pokeapi.co/api/v2/pokemon/12/"],
            ["name" => "weedle", "url" => "https://pokeapi.co/api/v2/pokemon/13/"],
            ["name" => "kakuna", "url" => "https://pokeapi.co/api/v2/pokemon/14/"],
            ["name" => "beedrill", "url" => "https://pokeapi.co/api/v2/pokemon/15/"],
            ["name" => "pidgey", "url" => "https://pokeapi.co/api/v2/pokemon/16/"],
            ["name" => "pidgeotto", "url" => "https://pokeapi.co/api/v2/pokemon/17/"],
            ["name" => "pidgeot", "url" => "https://pokeapi.co/api/v2/pokemon/18/"],
            ["name" => "rattata", "url" => "https://pokeapi.co/api/v2/pokemon/19/"],
            ["name" => "raticate", "url" => "https://pokeapi.co/api/v2/pokemon/20/"],
            ["name" => "spearow", "url" => "https://pokeapi.co/api/v2/pokemon/21/"],
            ["name" => "fearow", "url" => "https://pokeapi.co/api/v2/pokemon/22/"],
            ["name" => "ekans", "url" => "https://pokeapi.co/api/v2/pokemon/23/"],
            ["name" => "arbok", "url" => "https://pokeapi.co/api/v2/pokemon/24/"],
            ["name" => "pikachu", "url" => "https://pokeapi.co/api/v2/pokemon/25/"],
            ["name" => "raichu", "url" => "https://pokeapi.co/api/v2/pokemon/26/"],
            ["name" => "sandshrew", "url" => "https://pokeapi.co/api/v2/pokemon/27/"],
            ["name" => "sandslash", "url" => "https://pokeapi.co/api/v2/pokemon/28/"],
            ["name" => "nidoran-f", "url" => "https://pokeapi.co/api/v2/pokemon/29/"],
            ["name" => "nidorina", "url" => "https://pokeapi.co/api/v2/pokemon/30/"],
            ["name" => "nidoqueen", "url" => "https://pokeapi.co/api/v2/pokemon/31/"],
            ["name" => "nidoran-m", "url" => "https://pokeapi.co/api/v2/pokemon/32/"],
            ["name" => "nidorino", "url" => "https://pokeapi.co/api/v2/pokemon/33/"],
            ["name" => "nidoking", "url" => "https://pokeapi.co/api/v2/pokemon/34/"],
            ["name" => "clefairy", "url" => "https://pokeapi.co/api/v2/pokemon/35/"],
            ["name" => "clefable", "url" => "https://pokeapi.co/api/v2/pokemon/36/"],
            ["name" => "vulpix", "url" => "https://pokeapi.co/api/v2/pokemon/37/"],
            ["name" => "ninetales", "url" => "https://pokeapi.co/api/v2/pokemon/38/"],
            ["name" => "jigglypuff", "url" => "https://pokeapi.co/api/v2/pokemon/39/"],
            ["name" => "wigglytuff", "url" => "https://pokeapi.co/api/v2/pokemon/40/"],
            ["name" => "zubat", "url" => "https://pokeapi.co/api/v2/pokemon/41/"],
            ["name" => "golbat", "url" => "https://pokeapi.co/api/v2/pokemon/42/"],
            ["name" => "oddish", "url" => "https://pokeapi.co/api/v2/pokemon/43/"],
            ["name" => "gloom", "url" => "https://pokeapi.co/api/v2/pokemon/44/"],
            ["name" => "vileplume", "url" => "https://pokeapi.co/api/v2/pokemon/45/"],
            ["name" => "paras", "url" => "https://pokeapi.co/api/v2/pokemon/46/"],
            ["name" => "parasect", "url" => "https://pokeapi.co/api/v2/pokemon/47/"],
            ["name" => "venonat", "url" => "https://pokeapi.co/api/v2/pokemon/48/"],
            ["name" => "venomoth", "url" => "https://pokeapi.co/api/v2/pokemon/49/"],
            ["name" => "diglett", "url" => "https://pokeapi.co/api/v2/pokemon/50/"],
        ];

        return [
            'count' => 1351,
            'next' => null,
            'previous' => null,
            'results' => array_slice($dummy, $offset, $limit),
        ];
    }
}