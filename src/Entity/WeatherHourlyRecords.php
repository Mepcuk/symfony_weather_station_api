<?php

namespace App\Entity;

use App\Repository\WeatherHourlyRecordsRepository;
use Doctrine\ORM\Mapping as ORM;

/**
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
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $country;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $city;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $measure_at;

    /**
     * @ORM\Column(type="float")
     */
    private $temperature;

    /**
     * @ORM\Column(type="float")
     */
    private $humidity;

    /**
     * @ORM\Column(type="float")
     */
    private $rain;

    /**
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

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCountry(): ?string
    {
        return $this->country;
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
        return $this->measure_at;
    }

    public function setMeasureAt(\DateTimeImmutable $measure_at): self
    {
        $this->measure_at = $measure_at;

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

    public function getHumidity(): ?float
    {
        return $this->humidity;
    }

    public function setHumidity(float $humidity): self
    {
        $this->humidity = $humidity;

        return $this;
    }

    public function getRain(): ?float
    {
        return $this->rain;
    }

    public function setRain(float $rain): self
    {
        $this->rain = $rain;

        return $this;
    }

    public function getWind(): ?float
    {
        return $this->wind;
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

    public function getBatteryLevel(): ?string
    {
        return $this->batteryLevel;
    }

    public function setBatteryLevel(string $batteryLevel): self
    {
        $this->batteryLevel = $batteryLevel;

        return $this;
    }
}
