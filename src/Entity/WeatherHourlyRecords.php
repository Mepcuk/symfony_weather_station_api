<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\WeatherHourlyRecordsRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use  ApiPlatform\Core\Action\NotFoundAction;

/**
 * @ApiResource(
 *      normalizationContext={
 *          "groups"={
 *              "weather:read"
 *          }
 *     },
 *     itemOperations={
 *         "get"={
 *             "method"="GET",
 *             "controller"=NotFoundAction::class,
 *             "read"=false,
 *             "output"=false,
 *         },
 *     },
 *     collectionOperations={
 *                      "get"
 *     },
 * )
 * @ApiFilter(SearchFilter::class, properties={"measure_at", "country", "city"})
 * @ORM\Entity(repositoryClass=WeatherHourlyRecordsRepository::class)
 */
class WeatherHourlyRecords
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Groups ({"weather:read"})
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $country;

    /**
     * @Groups ({"weather:read"})
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $city;

    /**
     * @Groups ({"weather:read"})
     * @ORM\Column(type="datetime_immutable")
     */
    private $measureAt;

    /**
     * @Groups ({"weather:read"})
     * @ORM\Column(type="float")
     */
    private $temperature;

    /**
     * @Groups ({"weather:read"})
     * @ORM\Column(type="float")
     */
    private $humidity;

    /**
     * @ORM\Column(type="float")
     */
    private $rain;

    /**
     * @Groups ({"weather:read"})
     * @ORM\Column(type="float")
     */
    private $wind;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $light;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $batteryLevel;
    /**
     * @var Container
     */


    public function getId(): ?int
    {
        return $this->id;
    }

    public function setCountry(?string $country): self
    {
        $this->country = $country;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getMeasureAt(): ?\DateTimeImmutable
    {
        return $this->measureAt;
    }

    public function setMeasureAt(\DateTimeImmutable $measureAt): self
    {
        $this->measureAt = $measureAt;

        return $this;
    }

    public function getTemperature(): ?float
    {
        return $this->temperature;
    }

    public function setTemperature(float $temperature): self
    {
        $this->temperature = $temperature;

        return $this;
    }

    public function setHumidity(float $humidity): self
    {
        $this->humidity = $humidity;

        return $this;
    }


    public function setRain(float $rain): self
    {
        $this->rain = $rain;

        return $this;
    }


    public function setWind(float $wind): self
    {
        $this->wind = $wind;

        return $this;
    }

    public function getLight(): ?float
    {
        return $this->light;
    }

    public function setLight(?float $light): self
    {
        $this->light = $light;

        return $this;
    }

    public function setBatteryLevel(string $batteryLevel): self
    {
        $this->batteryLevel = $batteryLevel;

        return $this;
    }
}
