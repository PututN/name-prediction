<?php

namespace Database\Seeders;

use App\Models\Country;
use Illuminate\Database\Seeder;

/**
 * ISO 3166-1 alpha-2 country codes, taken from https://www.ssl.com/id/kode-negara-a/.
 */
class CountrySeeder extends Seeder
{
    public function run(): void
    {
        $countries = json_decode(
            file_get_contents(database_path('data/countries.json')),
            true,
            flags: JSON_THROW_ON_ERROR
        );

        $now = now();

        Country::upsert(
            array_map(fn (array $country) => [...$country, 'created_at' => $now, 'updated_at' => $now], $countries),
            uniqueBy: ['code'],
            update: ['name', 'updated_at']
        );
    }
}
