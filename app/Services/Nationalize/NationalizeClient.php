<?php

namespace App\Services\Nationalize;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;

class NationalizeClient
{
    public function __construct(
        private readonly string $baseUrl,
        private readonly int $timeout,
    ) {}

    /**
     * @throws NationalizeException
     */
    public function predict(string $name): NationalityResult
    {
        try {
            $response = Http::baseUrl($this->baseUrl)
                ->timeout($this->timeout)
                ->acceptJson()
                ->get('/', ['name' => $name])
                ->throw();
        } catch (RequestException $e) {
            throw $e->response->tooManyRequests()
                ? NationalizeException::rateLimited($e)
                : NationalizeException::unavailable($e);
        } catch (ConnectionException $e) {
            throw NationalizeException::unavailable($e);
        }

        return NationalityResult::fromResponse($response->json());
    }
}
