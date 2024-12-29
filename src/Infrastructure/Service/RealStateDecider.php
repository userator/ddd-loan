<?php

namespace App\Infrastructure\Service;

use App\Domain\Service\Decider;
use App\Infrastructure\Exception\InfrastructureException;
use Throwable;

class RealStateDecider implements Decider
{

    /**
     * @throws InfrastructureException
     */
    public function decide(): bool
    {
        try {
            return (bool)random_int(0, 1);
        } catch (Throwable $exception) {
            throw new InfrastructureException($exception->getMessage(), $exception);
        }
    }
}