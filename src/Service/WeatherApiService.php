<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class WeatherApiService
{
    public function __construct(
        private HttpClientInterface $client,
        private string $apiKey
    ) {}

    public function getWeatherForCity(string $city): array
    {
        $response = $this->client->request(
            'GET',
            "https://api.openweathermap.org/data/2.5/weather?q={$city}&appid={$this->apiKey}"
        );

        return $response->toArray();
    }
}