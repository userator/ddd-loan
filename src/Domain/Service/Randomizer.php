<?php

namespace App\Domain\Service;

interface Randomizer
{
    public function randomize(): bool;
}