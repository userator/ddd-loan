<?php

namespace App\Infrastructure\Service;

use App\Domain\Service\Randomizer;

class RealRandomizer implements Randomizer
{

    public function randomize(): bool
    {
        return (bool)rand(0, 1);
    }
}