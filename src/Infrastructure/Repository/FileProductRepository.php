<?php

namespace App\Infrastructure\Repository;

use App\Domain\Entity\Product;
use App\Domain\Repository\ProductRepository;
use App\Infrastructure\Trait\FilePersister;

class FileProductRepository implements ProductRepository
{
    use FilePersister;

    public function __construct(
        private string $path,
    ) {
    }

    public function findById(string $id): ?Product
    {
        return current(
            array_filter(
                $this->read(),
                fn(Product $item) => $id === $item->getId(),
            )
        ) ?: null;
    }

    /**
     * @inheritDoc
     */
    public function findAll(): array
    {
        return $this->read();
    }

    public function save(Product $entity): void
    {
        $data = $this->read();

        $data[] = $entity;

        $data = array_reduce(
            $data,
            function (array $lines, Product $line) {
                $lines[$line->getId()] = $line;
                return $lines;
            },
            [],
        );

        $this->write(array_values($data));
    }
}