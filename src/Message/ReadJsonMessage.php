<?php

namespace App\Message;

class ReadJsonMessage
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