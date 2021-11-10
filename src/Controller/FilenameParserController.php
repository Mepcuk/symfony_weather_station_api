<?php

namespace App\Controller;

class FilenameParserController
{
    /**
     * Get weather station city from filename
     * if date have YYYY-DD-MM format return true
     *
     * @param string $jsonFile
     * @return array|null
     */

    public function getWeatherStationCityAndCountry(string $jsonFile):? array
    {
        $filename = explode(' ', $jsonFile);
        if ( is_array($filename) && count($filename) == 2 ) {
            $location = explode('_', $filename[0]);
            if ( is_array($location) && count($location) == 2 ) {
                return [
                    'country'   => $location[0],
                    'city'      => $location[1],
                    ];
            }
        }

        return null;
    }


    /**
     * Get from filename datetime
     * if date have YYYY-DD-MM format return true
     *
     * @param string $jsonFile
     * @return bool
     */

    public function getUsWeatherFormat(string $jsonFile): bool
    {
        $filename = explode(' ', $jsonFile);
        if ( is_array($filename) && count($filename) == 2 ) {
            $date = explode('-', $filename[1]);
            if ( is_array($date) ) {
                return strlen($date[0]) == 4;
            } else {
                return new \Exception('Filename format is not valid');
            }
        }

        return false;
    }
}