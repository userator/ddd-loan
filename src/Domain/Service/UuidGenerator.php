<?php

namespace App\Domain\Service;

interface UuidGenerator
{
    public function generate(): string;
}