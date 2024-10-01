<?php

namespace App\Infrastructure\Service;

use App\Domain\Service\Randomizer;

class RealRandomizer implements Randomizer
{

    public function randomize(): bool
    {
        return (bool)random_int(0, 1);
    }
}