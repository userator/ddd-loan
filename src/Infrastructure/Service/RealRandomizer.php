<?php

namespace App\Infrastructure\Service;

use App\Application\Exception\InfrastructureException;
use App\Domain\Service\Randomizer;
use Throwable;

class RealRandomizer implements Randomizer
{

    /**
     * @throws InfrastructureException
     */
    public function randomize(): bool
    {
        try {
            return (bool)random_int(0, 1);
        } catch (Throwable $exception) {
            throw new InfrastructureException($exception->getMessage(), $exception);
        }
    }
}