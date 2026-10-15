<?php

namespace App\Services;

use App\Models\Country;
use App\Models\NamePrediction;
use App\Services\Nationalize\NationalityResult;
use App\Services\Nationalize\NationalizeClient;
use App\Services\Nationalize\NationalizeException;
use Illuminate\Support\Facades\DB;

class NamePredictionService
{
    public function __construct(
        private readonly NationalizeClient $client,
    ) {}

    /**
     * Return the stored prediction for a name, calling nationalize.io only for names not seen before.
     *
     * @throws NationalizeException
     */
    public function predict(string $name): NamePrediction
    {
        $name = mb_strtolower(trim($name));

        $prediction = NamePrediction::firstWhere('name', $name)
            ?? $this->store($name, $this->client->predict($name));

        return $prediction->load('countries');
    }

    private function store(string $name, NationalityResult $result): NamePrediction
    {
        return DB::transaction(function () use ($name, $result) {
            $this->ensureCountriesExist(array_keys($result->probabilities));

            $prediction = NamePrediction::create([
                'name' => $name,
                'sample_count' => $result->sampleCount,
            ]);

            $prediction->countries()->attach(
                collect($result->probabilities)->map(fn (float $probability) => ['probability' => $probability])
            );

            return $prediction;
        });
    }

    /**
     * The API may return codes missing from the seeded master list; register them so the foreign key holds.
     *
     * @param  list<string>  $codes
     */
    private function ensureCountriesExist(array $codes): void
    {
        $missing = array_diff($codes, Country::whereIn('code', $codes)->pluck('code')->all());

        foreach ($missing as $code) {
            Country::create(['code' => $code, 'name' => $code]);
        }
    }
}
