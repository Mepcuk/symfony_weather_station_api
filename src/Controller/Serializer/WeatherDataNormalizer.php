<?php

namespace App\Controller\Serializer;

use ApiPlatform\Core\Api\UrlGeneratorInterface;
use App\Entity\WeatherHourlyRecords;
use Symfony\Component\Serializer\Exception\BadMethodCallException;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Exception\ExtraAttributesException;
use Symfony\Component\Serializer\Exception\InvalidArgumentException;
use Symfony\Component\Serializer\Exception\LogicException;
use Symfony\Component\Serializer\Exception\RuntimeException;
use Symfony\Component\Serializer\Exception\UnexpectedValueException;
use Symfony\Component\Serializer\Normalizer\ContextAwareDenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class WeatherDataNormalizer implements ContextAwareDenormalizerInterface
{


    /**
     * @var ObjectNormalizer
     */
    private $objectNormalizer;

    public function __construct(ObjectNormalizer $objectNormalizer)
    {

        $this->objectNormalizer = $objectNormalizer;
    }

    public function denormalize($weatherData, string $type, string $format = null, array $context = [])
    {
        $data = [];

        if ( $format === 'csv') {
            if ( key_exists('csv_headers', $context) && key_exists('country', $context) && key_exists('city', $context) ) {
                foreach ( $weatherData as $weatherRecord ) {
                    /** @var WeatherHourlyRecords $weatherRecord */
                    $weatherHourlyRecord = new WeatherHourlyRecords();
                    foreach ( $context['csv_headers'] as $csvHeader ) {
                        $setter = 'set' . $csvHeader;
                        if ( $csvHeader === 'measureAt' ) {
                            $weatherHourlyRecord->$setter(\DateTimeImmutable::createFromFormat("d-m-Y H:i:s", $weatherRecord[$csvHeader]));
                        } else {
                            $weatherHourlyRecord->$setter($weatherRecord[$csvHeader]);
                        }
                    }

                    $weatherHourlyRecord->setCountry($context['country']);
                    $weatherHourlyRecord->setCity($context['country']);
                    $data[] = $weatherHourlyRecord;
                }
            }
        }

        return $data;
    }

    public function supportsDenormalization($data, string $type, string $format = null, array $context = [])
    {
        return $data;
    }

}