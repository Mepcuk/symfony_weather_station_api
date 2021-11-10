<?php

namespace App\MessageHandler;

use App\Controller\FilenameParserController;
use App\Controller\MeasureUnitConverterController;
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

    /**
     * @throws \Exception
     */
    public function __invoke(ReadJsonMessage $readJsonMessage)
    {
        /** default value '../data' */
        $readDirectory  = $readJsonMessage->getScanningDirectory();

        $jsonFiles      = preg_grep("~\.json$~", scandir($readDirectory));

        foreach ( $jsonFiles as $jsonFile ) {

            $dailyWeatherData           = json_decode(file_get_contents($readDirectory . '/' . $jsonFile), true);

            $filenameParser     = new FilenameParserController();
            $arrayCountyCity    = $filenameParser->getWeatherStationCityAndCountry($jsonFile);
            $usWeatherFormat    = $filenameParser->getUsWeatherFormat($jsonFile);

            if ( is_array($arrayCountyCity) && key_exists('country', $arrayCountyCity) && key_exists('city', $arrayCountyCity) ) {

                $weatherStationCountry  = $arrayCountyCity['country'];
                $weatherStationCity     = $arrayCountyCity['city'];

                if ($dailyWeatherData && is_array($dailyWeatherData) && count($dailyWeatherData) == 24) {
                    foreach ($dailyWeatherData as $hourlyWeatherData) {

                        $measureUnitConverter   = new MeasureUnitConverterController();
                        $weatherAt              = ( $usWeatherFormat ) ? date("d:m:Y H:i:s", date($hourlyWeatherData['Time'])) : $hourlyWeatherData['Time'];
                        $hourlyTemperatureC     = ( $usWeatherFormat ) ? $measureUnitConverter->convertTemperatureFromFahrenheitToCelsius( $hourlyWeatherData['Temperature'] ) : $hourlyWeatherData['Temperature'];
                        $hourlyRainMm           = ( $usWeatherFormat ) ? $measureUnitConverter->convertInchToMm($hourlyWeatherData['Rain']) : $hourlyWeatherData['Rain'];
                        $hourlyWindMeters       = ( $usWeatherFormat ) ? $measureUnitConverter->convertMilesToKm($hourlyWeatherData['Wind']) : $hourlyWeatherData['Wind'];

                        $weatherRecord = new WeatherHourlyRecords();
                        $weatherRecord->setMeasureAt(\DateTimeImmutable::createFromFormat("d:m:Y H:i:s", $weatherAt));
                        $weatherRecord->setCountry($weatherStationCountry);
                        $weatherRecord->setCity($weatherStationCity);
                        $weatherRecord->setTemperature($hourlyTemperatureC);
                        $weatherRecord->setHumidity($hourlyWeatherData['Humidity']);
                        $weatherRecord->setRain($hourlyRainMm);
                        $weatherRecord->setWind($hourlyWindMeters);
                        $weatherRecord->setBatteryLevel($hourlyWeatherData['Battery']);

                        if ( !$usWeatherFormat ) {
                            $weatherRecord->setLight($hourlyWeatherData['Light']);
                        }

                        $this->entityManager->persist($weatherRecord);
                        $this->entityManager->flush();

                    }
                } else {
                    throw new \Exception("Json File not Valid");
                }
            }
        }
    }
}