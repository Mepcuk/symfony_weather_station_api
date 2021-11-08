<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *     normalizationContext={"groups"={"last-update:read"}},
 *     itemOperations={
 *
 *     },
 *     collectionOperations={
 *                      "get"
 *     },
 * )
 */

class LastUpdate
{

    /**
     * @Groups ({"last-update:read"})
     */
    public $lastUpdatedDatetime;


    public function __construct(\DateTime $lastUpdatedDatetime)
    {
        $this->lastUpdatedDatetime = $lastUpdatedDatetime;
    }


}