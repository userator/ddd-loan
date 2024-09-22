<?php

namespace App\Application\Service;

interface EventDispatcher
{
    public function dispatch(object $event): object;
}