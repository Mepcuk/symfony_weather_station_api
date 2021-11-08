<?php

namespace App\DataProvider;

use ApiPlatform\Core\Exception\ResourceClassNotSupportedException;
use App\Entity\WeatherHourlyRecords;
use App\Repository\WeatherHourlyRecordsRepository;

class AverageWeatherProvider implements \ApiPlatform\Core\DataProvider\CollectionDataProviderInterface, \ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface, \ApiPlatform\Core\DataProvider\ItemDataProviderInterface
{

    /**
     * @var WeatherHourlyRecordsRepository
     */
    private $weatherHourlyRecordsRepository;

    public function __construct(WeatherHourlyRecordsRepository $weatherHourlyRecordsRepository)
    {
        $this->weatherHourlyRecordsRepository = $weatherHourlyRecordsRepository;
    }

    public function getCollection(string $resourceClass, string $operationName = null)
    {
        /*
         * TODO maybe will be need in future get collection - in repository does not have method - just hardcoded
         */
        $averageTemperature = $this->weatherHourlyRecordsRepository->averageTemperature('2021-11-08');

        return [$averageTemperature];
    }

    public function getItem(string $resourceClass, $id, string $operationName = null, array $context = [])
    {
        return $this->weatherHourlyRecordsRepository->averageTemperature($id);
    }

    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return $resourceClass === WeatherHourlyRecords::class;
    }
}