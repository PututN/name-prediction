<?php

namespace App\Services\Nationalize;

final readonly class NationalityResult
{
    /**
     * @param  array<string, float>  $probabilities  Country code => probability, highest first.
     */
    public function __construct(
        public string $name,
        public int $sampleCount,
        public array $probabilities,
    ) {}

    public static function fromResponse(array $payload): self
    {
        $probabilities = collect($payload['country'] ?? [])
            ->mapWithKeys(fn (array $country) => [strtoupper($country['country_id']) => (float) $country['probability']])
            ->sortDesc()
            ->all();

        return new self(
            name: (string) $payload['name'],
            sampleCount: (int) ($payload['count'] ?? 0),
            probabilities: $probabilities,
        );
    }
}
