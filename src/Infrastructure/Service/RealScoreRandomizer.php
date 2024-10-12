<?php

namespace App\Infrastructure\Service;

use App\Domain\Service\ScoreRandomizer;
use App\Infrastructure\Exception\InfrastructureException;
use Throwable;

class RealScoreRandomizer implements ScoreRandomizer
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