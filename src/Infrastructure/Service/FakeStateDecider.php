<?php

namespace App\Infrastructure\Service;

use App\Domain\Service\Decider;

class FakeStateDecider implements Decider
{

    public function __construct(
        private bool $value,
    ) {
    }

    public function decide(): bool
    {
        return $this->value;
    }
}