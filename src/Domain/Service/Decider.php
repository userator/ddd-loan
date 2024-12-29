<?php

namespace App\Domain\Service;

interface Decider
{
    public function decide(): bool;
}