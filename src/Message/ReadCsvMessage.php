<?php

namespace App\Message;

class ReadCsvMessage
{
    /**
     * @var string
     */
    private $scanningDirectory;

    public function __construct(string $scanningDirectory)
    {
        $this->scanningDirectory = $scanningDirectory;
    }

    /**
     * @return string
     */
    public function getScanningDirectory(): string
    {
        return $this->scanningDirectory;
    }
}