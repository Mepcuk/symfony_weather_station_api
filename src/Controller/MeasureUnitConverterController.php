<?php

namespace App\Controller;

class MeasureUnitConverterController
{
    /**
     * Temperature convertation from Farengheit to Celsium
     *
     * @param float $temperatureInFahrenheit
     * @return float
     */
    public function convertTemperatureFromFahrenheitToCelsius(float $temperatureInFahrenheit): float
    {
        return round( (( $temperatureInFahrenheit - 32 ) * 5/9 ), 2);
    }


    /**
     * Convertation Inch to mm
     *
     * @param float $inchValue
     * @return float
     */
    public function convertInchToMm(float $inchValue): float
    {
        return round( $inchValue * 25.4, 1);
    }


    /**
     * Convertation Miles to Km
     *
     * @param float $mileValue
     * @return float
     */
    public function convertMilesToKm(float $mileValue): float
    {
        return round( $mileValue * 1.609, 5);
    }
}