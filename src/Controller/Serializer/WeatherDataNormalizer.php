<?php

namespace App\Controller\Serializer;

use App\Entity\WeatherHourlyRecords;
use Symfony\Component\Serializer\Normalizer\ContextAwareDenormalizerInterface;

class WeatherDataNormalizer implements ContextAwareDenormalizerInterface
{


    public function denormalize($data, string $type, string $format = null, array $context = []): array
    {
        $weatherData = [];

        if ( $format === 'csv') {
            if ( key_exists('csv_headers', $context) && key_exists('country', $context) && key_exists('city', $context) ) {
                foreach ( $data as $weatherRecord ) {
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
                    $weatherData[] = $weatherHourlyRecord;
                }
            }
        }

        return $weatherData;
    }

    public function supportsDenormalization($data, string $type, string $format = null, array $context = [])
    {
        return $data;
    }

}