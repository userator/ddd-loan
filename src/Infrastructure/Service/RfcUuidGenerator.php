<?php

namespace App\Infrastructure\Service;

use App\Domain\Service\UuidGenerator;
use Symfony\Component\Uid\Uuid;

class RfcUuidGenerator implements UuidGenerator
{

    public function generate(): string
    {
        return Uuid::v4()->toRfc4122();
    }
}