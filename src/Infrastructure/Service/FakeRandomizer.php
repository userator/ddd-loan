<?php

namespace App\Infrastructure\Service;

use App\Domain\Service\Randomizer;

class FakeRandomizer implements Randomizer
{

    public function __construct(
        private bool $value,
    ) {
    }

    public function randomize(): bool
    {
        return $this->value;
    }
}