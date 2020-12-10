<?php


namespace App\Controller;


use App\DTO\WeatherDto;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * @Route("/weather")
 */
class WeatherController extends AbstractController
{
    private const CITIES = [
        'dnipro',
        'kiev',
        'lvov',
        'sydney'
    ];

    /**
     * @Route("/city/{name}", methods={"GET"}, name="get_weather_by_city")
     */
    public function getWeatherByCity(string $name)
    {
        if (!in_array($name, self::CITIES)) {
            $error = [
                'message' => sprintf('City <%s> not found', $name),
                'advice' => 'Try: ' . implode(',', self::CITIES)
            ];
            return $this->json($error, Response::HTTP_NOT_FOUND);
        }

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

    /**
     * @Route("", methods={"POST"})
     * @return Response
     */
    public function create(Request $request): Response
    {
        $rawData = $request->getContent();
        $data = json_decode($rawData, true);
        return new Response('', Response::HTTP_CREATED, [
            'Location' => $this->generateUrl('get_weather_by_city', ['name' => $data['city']])
        ]);
    }

    /**
     * @Route("/city/{cityName}", methods={"PUT", "PATCH"})
     * @param Request $request
     * @param string $cityName
     * @return JsonResponse
     */
    public function update(Request $request, string $cityName): JsonResponse
    {
        $content = json_decode($request->getContent(), true);
        $content['method'] = $request->getMethod();
        //updating
        return $this->json($content);
    }
}