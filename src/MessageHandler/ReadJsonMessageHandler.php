<?php

namespace App\MessageHandler;

use App\Entity\WeatherHourlyRecords;
use App\Message\ReadJsonMessage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class ReadJsonMessageHandler implements MessageHandlerInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function __invoke(ReadJsonMessage $readJsonMessage)
    {
        /** default value '../data' */
        $readDirectory  = $readJsonMessage->getScanningDirectory();

        $jsonFiles      = preg_grep("~\.json$~", scandir($readDirectory));

        foreach ( $jsonFiles as $jsonFile ) {

            $dailyWeatherData           = json_decode(file_get_contents($readDirectory . '/' . $jsonFile), true);
            $usWeatherFormat            = $this->getUsWeatherFormat($jsonFile);
            $weatherStationCountry      = $this->getWeatherStationCountry($jsonFile);
            $weatherStationCity         = $this->getWeatherStationCity($jsonFile);

            if ( $dailyWeatherData && is_array($dailyWeatherData) && count($dailyWeatherData) == 24 ) {
                foreach ( $dailyWeatherData as $hourlyWeatherData ) {

                    $weatherAt          = (!$usWeatherFormat) ? date("d:m:Y H:i:s", date($hourlyWeatherData['Time'])) : $hourlyWeatherData['Time'];
                    $hourlyTemperatureC = ($usWeatherFormat) ? $this->convertTemperatureFromFarengheitToCelsium($hourlyWeatherData['Temperature']) : $hourlyWeatherData['Temperature'];
                    $hourlyRainMm       = ($usWeatherFormat) ? $this->convertInchToMm($hourlyWeatherData['Rain']) : $hourlyWeatherData['Rain'];
                    $hourlyWindMeters   = ($usWeatherFormat) ? $this->convertMilesToKm($hourlyWeatherData['Wind']) : $hourlyWeatherData['Wind'];

                    $weatherRecord = new WeatherHourlyRecords();
                    $weatherRecord->setMeasureAt(\DateTimeImmutable::createFromFormat("d:m:Y H:i:s", $weatherAt));
                    $weatherRecord->setCountry($weatherStationCountry);
                    $weatherRecord->setCity($weatherStationCity);
                    $weatherRecord->setTemperature($hourlyTemperatureC);
                    $weatherRecord->setHumidity($hourlyWeatherData['Humidity']);
                    $weatherRecord->setRain($hourlyRainMm);
                    $weatherRecord->setWind($hourlyWindMeters);
                    $weatherRecord->setBatteryLevel($hourlyWeatherData['Battery']);

//                    if ( !$usWeatherFormat ) {
//                        $weatherRecord->setLight($hourlyWeatherData['Light']);
//                    }

                    $this->entityManager->persist($weatherRecord);
                    $this->entityManager->flush();

                }
            } else {
                new \Exception("Json File not Valid");
            }
        }
    }


    /**
     * Get from filename datetime
     * if date have YYYY-DD-MM format return true
     *
     * @param string $jsonFile
     * @return bool
     */

    public function getUsWeatherFormat(string $jsonFile): bool
    {
        $filename = explode(' ', $jsonFile);
        if ( is_array($filename) && count($filename) == 2 ) {
            $date = explode('-', $filename[1]);
            if ( is_array($date) ) {
                return strlen($date[0]) == 4;
            } else {
                return new \Exception('Filename format is not valid');
            }
        }

        return false;
    }

    /**
     * Get weather station city from filename
     * if date have YYYY-DD-MM format return true
     *
     * @param string $jsonFile
     * @return bool
     */

    public function getWeatherStationCity(string $jsonFile):? string
    {
        $filename = explode(' ', $jsonFile);
        if ( is_array($filename) && count($filename) == 2 ) {
            $location = explode('_', $filename[0]);
            if ( is_array($location) && count($location) == 2 ) {
                return $location[1];
            }
        }

        return false;
    }


    /**
     * Get weather station country from filename
     *
     * @param string $jsonFile
     * @return bool
     */

    public function getWeatherStationCountry(string $jsonFile):? string
    {
        $filename = explode(' ', $jsonFile);
        if ( is_array($filename) && count($filename) == 2 ) {
            $location = explode('_', $filename[0]);
            if ( is_array($location) && count($location) == 2 ) {
                return $location[0];
            }
        }

        return false;
    }

    /**
     * Temperature convertation from Farengheit to Celsium
     *
     * @param float $temperatureInFarengheit
     * @return float
     */
    public function convertTemperatureFromFarengheitToCelsium(float $temperatureInFarengheit): float
    {
        return round( (( $temperatureInFarengheit - 32 ) * 5/9 ), 2);
    }


    /**
     * Convertation Inch to mm
     *
     * @param float $inchValue
     * @return float
     */
    public function convertInchToMm(float $inchValue): float
    {
        return round( $inchValue * 25.4, 1);
    }


    /**
     * Convertation Miles to Km
     *
     * @param float $mileValue
     * @return float
     */
    public function convertMilesToKm(float $mileValue)
    {
        return round( $mileValue * 1.609, 5);
    }
}