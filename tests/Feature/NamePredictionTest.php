<?php

namespace Tests\Feature;

use App\Models\Country;
use App\Models\NamePrediction;
use Database\Seeders\CountrySeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class NamePredictionTest extends TestCase
{
    use RefreshDatabase;

    protected bool $seed = true;

    protected string $seeder = CountrySeeder::class;

    public function test_index_page_renders(): void
    {
        $this->get(route('predictions.index'))
            ->assertOk()
            ->assertSee('Belum ada nama yang diprediksi.');
    }

    public function test_it_stores_predictions_linked_to_country_codes(): void
    {
        $this->fakeNationalize(['CZ' => 0.141113, 'ID' => 0.092417]);

        $response = $this->post(route('predictions.store'), ['name' => 'Jaja']);

        $prediction = NamePrediction::firstOrFail();
        $response->assertRedirect(route('predictions.show', $prediction));

        $this->assertSame('jaja', $prediction->name);
        $this->assertSame(11182, $prediction->sample_count);
        $this->assertSame(['CZ', 'ID'], $prediction->countries->pluck('code')->all());
        $this->assertDatabaseHas('country_name_prediction', [
            'name_prediction_id' => $prediction->id,
            'country_code' => 'ID',
            'probability' => 0.092417,
        ]);

        $this->get(route('predictions.show', $prediction))
            ->assertOk()
            ->assertSeeInOrder(['Republik Ceko', '14.1%', 'Indonesia', '9.2%']);
    }

    public function test_a_name_already_predicted_is_served_from_the_database(): void
    {
        $this->fakeNationalize(['ID' => 0.5]);

        $this->post(route('predictions.store'), ['name' => 'budi']);
        $this->post(route('predictions.store'), ['name' => ' Budi ']);

        Http::assertSentCount(1);
        $this->assertSame(1, NamePrediction::count());
    }

    public function test_unknown_country_codes_are_registered(): void
    {
        $this->fakeNationalize(['XK' => 0.3]);

        $this->post(route('predictions.store'), ['name' => 'arben']);

        $this->assertTrue(Country::whereKey('XK')->exists());
    }

    public function test_it_shows_a_message_when_rate_limited(): void
    {
        Http::fake([
            'api.nationalize.io/*' => Http::response(['error' => 'Request limit reached'], 429),
        ]);

        $this->from(route('predictions.index'))
            ->post(route('predictions.store'), ['name' => 'jaja'])
            ->assertRedirect(route('predictions.index'))
            ->assertSessionHasErrors(['name' => 'Batas request harian nationalize.io sudah habis. Coba lagi besok.']);

        $this->assertSame(0, NamePrediction::count());
    }

    public function test_name_is_validated(): void
    {
        Http::fake();

        $this->post(route('predictions.store'), ['name' => 'jaja123'])
            ->assertSessionHasErrors('name');

        Http::assertNothingSent();
    }

    public function test_a_prediction_can_be_deleted(): void
    {
        $this->fakeNationalize(['ID' => 0.5]);
        $this->post(route('predictions.store'), ['name' => 'budi']);
        $prediction = NamePrediction::firstOrFail();

        $this->delete(route('predictions.destroy', $prediction))
            ->assertRedirect(route('predictions.index'));

        $this->assertModelMissing($prediction);
        $this->assertDatabaseCount('country_name_prediction', 0);
    }

    /**
     * @param  array<string, float>  $probabilities
     */
    private function fakeNationalize(array $probabilities): void
    {
        Http::fake([
            'api.nationalize.io/*' => Http::response([
                'count' => 11182,
                'name' => 'jaja',
                'country' => collect($probabilities)
                    ->map(fn (float $probability, string $code) => ['country_id' => $code, 'probability' => $probability])
                    ->values()
                    ->all(),
            ]),
        ]);
    }
}
