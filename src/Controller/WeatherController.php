<?php


namespace App\Controller;


use App\DTO\WeatherDto;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * @Route("/weather")
 */
class WeatherController extends AbstractController
{
    /**
     * @Route("/city/{name}", methods={"GET"})
     */
    public function getWeatherByCity(string $name)
    {
        $report = [
            'temperature' => 1,
            'wind_speed' => 12,
            'humidity' => 60,
            'conditions' => [
                'road' => 'clear',
                'fall' => 'snow'
            ]
        ];

        return $this->json($report);
    }
}