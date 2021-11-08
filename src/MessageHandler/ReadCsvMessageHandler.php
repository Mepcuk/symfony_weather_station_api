<?php

namespace App\MessageHandler;

use App\Entity\WeatherHourlyRecords;
use App\Message\ReadCsvMessage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class ReadCsvMessageHandler implements MessageHandlerInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function __invoke(ReadCsvMessage $readCsvMessage)
    {

        $readDirectory  = $readCsvMessage->getScanningDirectory();
        $csvFiles       = preg_grep("~\.csv$~", scandir($readDirectory));

        foreach ( $csvFiles as $csvFile ) {

            $weatherStationCountry      = $this->getWeatherStationCountry($csvFile);
            $weatherStationCity         = $this->getWeatherStationCity($csvFile);

            if ( ($handle = fopen($readDirectory. '/' . $csvFile, "r")) !== FALSE ) {
                while ( ($data = fgetcsv($handle, 1000, ",")) !== FALSE ) {

                    if ( count($data) == 7 ) {


                        $a = \DateTimeImmutable::createFromFormat("d-m-Y H:i:s", $data[0]);
                        $weatherRecord = new WeatherHourlyRecords();
                        $weatherRecord->setMeasureAt(\DateTimeImmutable::createFromFormat("d-m-Y H:i:s", $data[0]));
                        $weatherRecord->setCountry($weatherStationCountry);
                        $weatherRecord->setCity($weatherStationCity);
                        $weatherRecord->setTemperature($data[1]);
                        $weatherRecord->setHumidity($data[2]);
                        $weatherRecord->setRain( $data[3]);
                        $weatherRecord->setWind( $data[4]);
                        $weatherRecord->setLight($data[5]);
                        $weatherRecord->setBatteryLevel( $data[6]);

//                    if ( !$usWeatherFormat ) {
//                        $weatherRecord->setLight($hourlyWeatherData['Light']);
//                    }

                        $this->entityManager->persist($weatherRecord);
                        $this->entityManager->flush();
                    }
                }
                fclose($handle);
            }
        }
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
}