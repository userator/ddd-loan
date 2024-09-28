<?php

namespace App\Infrastructure\EventDispatcher;

use App\Application\Service\EventDispatcher;
use Psr\EventDispatcher\EventDispatcherInterface;

class PsrEventDispatcher implements EventDispatcher, EventDispatcherInterface
{
    public function __construct(
        private EventDispatcherInterface $dispatcher,
    ) {
    }

    public function dispatch(object $event): object
    {
        return $this->dispatcher->dispatch($event);
    }
}