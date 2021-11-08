<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Action\NotFoundAction;

/**
 * @ApiResource(
 *     normalizationContext={"groups"={"average-weather:read"}},
 *     itemOperations={
 *          "get"={"path"="/average-weather/{reportDate}"}
 *     },
 *     collectionOperations={
 *                      "get"={
*                           "method"="GET",
 *                          "controller"=NotFoundAction::class,
 *                          "read"=false,
 *                          "write"=false,
 *                      },
 *     },
 * )
 */

class AverageWeather
{
    /**
     * @Groups ({"average-weather:read"})
     */
    public $averageTemperature;

    /**
     * @Groups ({"average-weather:read"})
     */
    public $averageHumidity;

    /**
     * @Groups ({"average-weather:read"})
     */
    public $averageWind;

    /**
     * @Groups ({"average-weather:read"})
     */
    public $reportDate;

    /**
     * @Groups ({"average-weather:read"})
     */
    public $city;

    public function __construct(float $averageTemperature, float $averageHumidity, float $averageWind, \DateTime $reportDate)
    {
        $this->averageTemperature   = $averageTemperature;
        $this->averageHumidity      = $averageHumidity;
        $this->averageWind          = $averageWind;
        $this->reportDate           = $reportDate;
    }


    /**
     * @ApiProperty(identifier=true)
     */
    public function getReportDate()
    {
        return $this->reportDate->format('Y-m-d');
    }

}