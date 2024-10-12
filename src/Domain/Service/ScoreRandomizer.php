<?php

namespace App\Domain\Service;

interface ScoreRandomizer
{
    public function randomize(): bool;
}