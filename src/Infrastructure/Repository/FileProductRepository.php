<?php

namespace App\Infrastructure\Repository;

use App\Domain\Entity\Product;
use App\Domain\Repository\ProductRepository;
use App\Domain\ValueObject\Id;
use App\Infrastructure\Trait\FilePersister;

class FileProductRepository implements ProductRepository
{
    use FilePersister;

    public function __construct(
        private string $path,
    ) {
    }

    public function findById(Id $id): ?Product
    {
        return current(
            array_filter(
                $this->read(),
                static fn(Product $item) => $id->getValue() === $item->getId()->getValue(),
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
                $lines[$line->getId()->getValue()] = $line;
                return $lines;
            },
            [],
        );

        $this->write(array_values($data));
    }
}
