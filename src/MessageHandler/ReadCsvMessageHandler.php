<?php

namespace App\MessageHandler;

use App\Controller\FilenameParserController;
use App\Controller\Serializer\WeatherDataNormalizer;
use App\Entity\WeatherHourlyRecords;
use App\Message\ReadCsvMessage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor;
use Symfony\Component\Serializer\Encoder\CsvEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncode;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;

class ReadCsvMessageHandler implements MessageHandlerInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var SerializerInterface
     */
    private $serializer;
    /**
     * @var ContainerInterface
     */
    private $container;
    /**
     * @var ObjectNormalizer
     */
    private $objectNormalizer;

    public function __construct(EntityManagerInterface $entityManager, SerializerInterface $serializer, ContainerInterface $container, ObjectNormalizer $objectNormalizer)
    {
        $this->entityManager    = $entityManager;
        $this->serializer       = $serializer;
        $this->container        = $container;
        $this->objectNormalizer = $objectNormalizer;
    }

    public function __invoke(ReadCsvMessage $readCsvMessage)
    {

        $readDirectory  = $readCsvMessage->getScanningDirectory();
        $csvFiles       = preg_grep("~\.csv$~", scandir($readDirectory));

        foreach ( $csvFiles as $csvFile ) {

            $filenameParser     = new FilenameParserController();
            $arrayCountryCity   = $filenameParser->getWeatherStationCityAndCountry($csvFile);
            $usWeatherFormat    = $filenameParser->getUsWeatherFormat($csvFile);

            if ( is_array($arrayCountryCity) && key_exists('country', $arrayCountryCity) && key_exists('city', $arrayCountryCity) ) {

                $encoders                   = [new CsvEncoder(), new JsonEncode()];
                $normalizers                = [new WeatherDataNormalizer($this->objectNormalizer)];
                $serializer                 = new Serializer($normalizers, $encoders);

                $contentCsv                 = file_get_contents($readDirectory. '/' . $csvFile);
                /** @var WeatherHourlyRecords $weatherRecords */
                $weatherRecords             = $serializer->deserialize($contentCsv, WeatherHourlyRecords::class . '[]', 'csv', ['csv_delimiter' => ',', 'csv_headers' => ['measureAt', 'temperature', 'humidity', 'rain', 'wind', 'light', 'batteryLevel'], 'country' => $arrayCountryCity['country'], 'city' => $arrayCountryCity['city'], 'weatherFormat' => $usWeatherFormat ]);

                foreach ( $weatherRecords as $weatherRecord) {
                    $this->entityManager->persist($weatherRecord);
                }
                $this->entityManager->flush();
            }
        }
    }
}