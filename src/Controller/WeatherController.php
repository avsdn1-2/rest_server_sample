<?php


namespace App\Controller;


use App\DTO\WeatherDto;
use App\Entity\Weather;
use App\Repository\WeatherRepository;
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
        /** @var WeatherRepository $repository */
        $repository = $this->getDoctrine()->getRepository(Weather::class);
        $report = $repository->findOneBy(
            [
                'cityName' => $name
            ]
        );
        if (!$report) {
            return $this->json([
                'error' => 'Weather report not found'
            ], Response::HTTP_NOT_FOUND);
        }
        return $this->json($report);
    }

    /**
     * @Route("/{id}", methods={"GET"}, name="get_weather_by_id")
     */
    public function getWeatherById(int $id)
    {
        /** @var WeatherRepository $repository */
        $repository = $this->getDoctrine()->getRepository(Weather::class);
        $report = $repository->find($id);
        if (!$report) {
            return $this->json([
                'error' => 'Weather report not found'
            ], Response::HTTP_NOT_FOUND);
        }
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
        $weather = new Weather();
        $weather->setCityName($data['city'])
            ->setHumidity($data['humidity'])
            ->setTemperature($data['temperature'])
            ->setWindSpeed($data['wind_speed']);
        $em = $this->getDoctrine()->getManager();
        $em->persist($weather);
        $em->flush();

        return new Response('', Response::HTTP_CREATED, [
            'Location' => $this->generateUrl('get_weather_by_id', ['id' => $weather->getId()])
        ]);
    }

    /**
     * @Route("/city/{cityName}", methods={"PUT", "PATCH"})
     * @param Request $request
     * @param string $cityName
     * @return JsonResponse
     */
    public function update(Request $request, WeatherRepository $repository, string $cityName): JsonResponse
    {
        $content = json_decode($request->getContent(), true);
        $report = $repository->findByCityName($content['city']);

        if (!$report) {
            return $this->json([
                'error' => 'Weather report not found'
            ], Response::HTTP_NOT_FOUND);
        }
        $repository->updateWeather($content['temperature'],$content['humidity'], $content['humidity'], $cityName);
        return $this->json($report);
    }
}