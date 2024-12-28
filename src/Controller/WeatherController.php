<?php

namespace App\Controller;

use App\Service\WeatherApiService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\City;
use App\Entity\Weather;

class WeatherController extends AbstractController
{
    public function __construct(
        private WeatherApiService $weatherService,
        private EntityManagerInterface $entityManager
    ) {}

    #[Route('/api/weather/city/{cityName}', methods: ['GET'])]
    public function getCurrentWeather(string $cityName): JsonResponse
    {
        try {
            $weatherData = $this->weatherService->getWeatherForCity($cityName);

            $temperatureCelsius = $weatherData['main']['temp'] - 273.15;

            $city = $this->entityManager->getRepository(City::class)
                ->findOneBy(['name' => $cityName]);

            if (!$city) {
                $city = new City();
                $city->setName($cityName);
                $this->entityManager->persist($city);
            }

            $weather = new Weather();
            $weather->setCity($city);
            $weather->setTemperature($temperatureCelsius);
            $weather->setDescription($weatherData['weather'][0]['description']);

            $this->entityManager->persist($weather);
            $this->entityManager->flush();

            return new JsonResponse([
                'city' => $cityName,
                'temperature' => round($temperatureCelsius, 2),
                'description' => $weatherData['weather'][0]['description'],
                'humidity' => $weatherData['main']['humidity'],
                'wind_speed' => $weatherData['wind']['speed']
            ]);

        } catch (\Exception $e) {
            return new JsonResponse([
                'error' => 'Не удалось получить данные о погоде',
                'message' => $e->getMessage()
            ], 500);
        }
    }
} 