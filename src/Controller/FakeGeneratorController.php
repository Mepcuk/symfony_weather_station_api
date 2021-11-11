<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class FakeGeneratorController extends AbstractController
{
    /**
     * Generate fake json in ./data with weather information data
     *
     * @Route("/generate_fake_json")
     */

    public function generateFakeWeatherJson(int $unixStartDatetime = 1636236000): JsonResponse
    {
        $weather    = [];

        for ( $hour = 0; $hour < 24; $hour++) {

            $hourlyData = [
                "Time"          => $unixStartDatetime + ($hour * 3600),
                "Temperature"   => rand(0, 100),
                "Humidity"      => rand(0, 100),
                "Rain"          => rand(0, 20),
                "Wind"          => rand(0, 15),
                "Battery"       => rand(1, 100),
            ];
            $weather[] = $hourlyData;

        }

        $json       = json_encode($weather);
        $filename   = sprintf('../data/us_boston %s.json', gmdate("Y-m-d", $unixStartDatetime));
        $file       = file_put_contents($filename, $json);

        return new JsonResponse($json);
    }

    /**
     * Generate fake csv in ./data with weather information data
     *
     * @Route("/generate_fake_csv")
     */
    public function generateFakeWeatherCsv(string $startDate = '07-11-2021'): JsonResponse
    {
        $batteryLevel   = ['low', 'medium', 'high', 'full'];
        $dailyWeather   = [[
            "Time"          => "measureAt" ,
            "Temperature"   => "temperature",
            "Humidity"      => "humidity",
            "Rain"          => "rain",
            "Wind"          => "wind",
            "Lux"           => "light",
            "Battery"       => "batteryLevel",
        ]];

        for ( $hour = 0; $hour < 24; $hour++) {

            $hour24 = ( $hour < 10 ) ? '0' . $hour : $hour;

            $hourlyData = [
                "Time"          => sprintf('%s %s:00:00', $startDate, $hour24) ,
                "Temperature"   => rand(0, 100),
                "Humidity"      => rand(0, 100),
                "Rain"          => rand(0, 20),
                "Wind"          => rand(0, 15),
                "Lux"           => rand(0, 1000),
                "Battery"       => $batteryLevel[rand(0, 3)],
            ];
            $dailyWeather[] = $hourlyData;
        }

        $csvFile = fopen(sprintf('../data/lv_riga %s.csv', $startDate), 'w');

        foreach ( $dailyWeather as $hourlyData ) {
            fputcsv($csvFile, $hourlyData);
        }

        fclose($csvFile);

        $json = json_encode($dailyWeather);
        return new JsonResponse($json);
    }
}