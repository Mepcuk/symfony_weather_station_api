<?php

namespace App\MessageHandler;

use App\Controller\FilenameParserController;
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

            $filenameParser     = new FilenameParserController();
            $arrayCountyCity    = $filenameParser->getWeatherStationCityAndCountry($csvFile);

            if ( is_array($arrayCountyCity) && key_exists('country', $arrayCountyCity) && key_exists('city', $arrayCountyCity) ) {

                $weatherStationCountry      = $arrayCountyCity['country'];
                $weatherStationCity         = $arrayCountyCity['city'];

                if ( ($handle = fopen($readDirectory. '/' . $csvFile, "r")) !== FALSE ) {
                    while ( ($data = fgetcsv($handle, 1000, ",")) !== FALSE ) {

                        if ( count($data) == 7 ) {

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

                            $this->entityManager->persist($weatherRecord);
                            $this->entityManager->flush();
                        }
                    }
                    fclose($handle);
                }
            }
        }
    }
}