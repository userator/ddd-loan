<?php

namespace App\Infrastructure\Trait;

use App\Application\Exception\InfrastructureException;

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
        if (false === file_exists($this->path)) {
            return [];
        }

        $content = file_get_contents($this->path);

        if (false === $content) {
            throw new InfrastructureException('Fail content read');
        }

        if ('' === $content) {
            return [];
        }

        $unserialized = unserialize($content);

        if (false === $unserialized) {
            throw new InfrastructureException('Fail content unserialize');
        }

        if (false === is_array($unserialized)) {
            throw new InfrastructureException('Invalid content');
        }

        return $unserialized;
    }

    /**
     * @param array<object> $value
     */
    public function write(array $value): void
    {
        if (false === file_put_contents($this->path, serialize($value))) {
            throw new InfrastructureException('Fail content write');
        }
    }
}