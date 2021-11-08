<?php

namespace App\DataProvider;

use ApiPlatform\Core\DataProvider\CollectionDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use App\Entity\LastUpdate;
use App\Repository\WeatherHourlyRecordsRepository;

class LastUpdateProvider implements CollectionDataProviderInterface, RestrictedDataProviderInterface
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
        // TODO: Implement getCollection() method.
    }

    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return $resourceClass === LastUpdate::class;
    }
}