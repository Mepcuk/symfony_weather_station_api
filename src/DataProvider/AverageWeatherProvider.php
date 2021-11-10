<?php

namespace App\DataProvider;

use ApiPlatform\Core\DataProvider\CollectionDataProviderInterface;
use ApiPlatform\Core\DataProvider\ItemDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use App\Entity\AverageWeather;
use App\Repository\WeatherHourlyRecordsRepository;

class AverageWeatherProvider implements CollectionDataProviderInterface, RestrictedDataProviderInterface, ItemDataProviderInterface
{

    /**
     * @var WeatherHourlyRecordsRepository
     */
    private $weatherHourlyRecordsRepository;

    public function __construct(WeatherHourlyRecordsRepository $weatherHourlyRecordsRepository)
    {
        $this->weatherHourlyRecordsRepository = $weatherHourlyRecordsRepository;
    }

    public function getCollection(string $resourceClass, string $operationName = null): array
    {
        /*
         * TODO maybe will be need in future get collection - in repository does not have method - just hardcoded
         */
        $averageTemperature = $this->weatherHourlyRecordsRepository->averageTemperature('2021-11-08');

        return [$averageTemperature];
    }

    public function getItem(string $resourceClass, $id, string $operationName = null, array $context = []): AverageWeather
    {

        $temperature    = $this->weatherHourlyRecordsRepository->averageTemperature($id);
        $humidity       = $this->weatherHourlyRecordsRepository->averageHumidity($id);
        $wind           = $this->weatherHourlyRecordsRepository->averageWind($id);

        return new AverageWeather(
            $temperature[1],
            $humidity[1],
            $wind[1],
            $id
        );

    }

    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return $resourceClass === AverageWeather::class;
    }
}