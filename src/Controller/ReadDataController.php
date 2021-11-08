<?php

namespace App\Controller;

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
    public function getWeatherJson(MessageBusInterface $messageBus)
    {
        $getMessage = new ReadJsonMessage('./../data');
        $messageBus->dispatch($getMessage);

        return new Response('Queue added read from json - weather information', 201);
    }
}