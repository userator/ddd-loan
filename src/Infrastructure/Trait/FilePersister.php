<?php

namespace App\Infrastructure\Trait;

/**
 * @property string $path
 */
trait FilePersister
{
    /**
     * @return array<object>
     */
    public function read(): array
    {
        touch($this->path);
        return (array)(unserialize((string)file_get_contents($this->path)) ?: []);
    }

    /**
     * @param array<object> $value
     */
    public function write(array $value): void
    {
        touch($this->path);
        file_put_contents($this->path, serialize($value));
    }
}