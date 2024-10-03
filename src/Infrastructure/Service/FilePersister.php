<?php

namespace App\Infrastructure\Service;

use App\Application\Exception\InfrastructureException;

class FilePersister
{

    public function __construct(
        private string $path,
    ) {
    }

    /**
     * @return array<object>
     * @throws InfrastructureException
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
     * @throws InfrastructureException
     */
    public function write(array $value): void
    {
        if (false === file_put_contents($this->path, serialize($value))) {
            throw new InfrastructureException('Fail content write');
        }
    }
}