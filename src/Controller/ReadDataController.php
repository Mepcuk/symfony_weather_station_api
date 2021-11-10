<?php

namespace App\Controller;

use App\Message\ReadCsvMessage;
use App\Message\ReadJsonMessage;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

class ReadDataController extends AbstractController
{
    /**
     * Get weather information from all jsons in ./data
     *
     * @Route ("/get_weather_info_json")
     */
    public function getWeatherJson(MessageBusInterface $messageBus): Response
    {
        $getMessage = new ReadJsonMessage('./../data');
        $messageBus->dispatch($getMessage);

        return new Response('Queue added read from json - weather information', 201);
    }

    /**
     * Get weather information from all csv in ./data
     *
     * @Route ("/get_weather_info_csv")
     */
    public function getWeatherCsv(MessageBusInterface $messageBus): Response
    {
        $getMessage = new ReadCsvMessage('./../data');
        $messageBus->dispatch($getMessage);

        return new Response('Queue added read from Csv - weather information', 201);
    }
}