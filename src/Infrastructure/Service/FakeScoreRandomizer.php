<?php

namespace App\Infrastructure\Service;

use App\Domain\Service\ScoreRandomizer;

class FakeScoreRandomizer implements ScoreRandomizer
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