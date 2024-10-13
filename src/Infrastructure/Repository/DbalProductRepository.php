<?php

namespace App\Infrastructure\Repository;

use App\Domain\Entity\Product;
use App\Domain\Exception\DomainException;
use App\Domain\Factory\ProductFactory;
use App\Domain\Repository\ProductRepository;
use App\Domain\ValueObject\Id;
use Doctrine\DBAL\Connection;
use Throwable;

class DbalProductRepository implements ProductRepository
{
    public function __construct(
        private Connection $connection,
    ) {
    }

    /**
     * @throws DomainException
     */
    public function findById(Id $id): ?Product
    {
        try {
            $line = $this->connection->executeQuery(
                <<<SQL
                SELECT
                    id,
                    name,
                    term,
                    interestrate "interestRate",
                    amount
                FROM product
                WHERE id = :id
                SQL,
                ['id' => $id->getValue()],
            )->fetchAssociative();
        } catch (Throwable $exception) {
            throw new DomainException($exception->getMessage(), $exception);
        }

        if (false === $line) {
            return null;
        }

        return ProductFactory::createFromArray($line);
    }

    /**
     * @inheritDoc
     * @throws DomainException
     */
    public function findAll(): array
    {
        try {
            $lines = $this->connection->executeQuery(
                <<<SQL
                SELECT
                    id,
                    name,
                    term,
                    interestrate "interestRate",
                    amount
                FROM product
                SQL,
            )->fetchAllAssociative();
        } catch (Throwable $exception) {
            throw new DomainException($exception->getMessage(), $exception);
        }

        if ([] === $lines) {
            return [];
        }

        return ProductFactory::createFromArrays($lines);
    }

    /**
     * @throws DomainException
     */
    public function save(Product $entity): void
    {
        try {
            $this->connection->executeStatement(
                <<<SQL
                INSERT INTO product (
                    id,
                    name,
                    term,
                    interestrate,
                    amount
                ) VALUES (
                    :id,
                    :name,
                    :term,
                    :interestrate,
                    :amount
                ) ON CONFLICT (id) DO UPDATE SET
                    name = :name,
                    term = :term,
                    interestrate = :interestrate,
                    amount = :amount
                SQL,
                [
                    'id' => $entity->getId()->getValue(),
                    'name' => $entity->getName(),
                    'term' => $entity->getTerm(),
                    'interestrate' => $entity->getInterestRate(),
                    'amount' => $entity->getAmount(),
                ],
            );
        } catch (Throwable $exception) {
            throw new DomainException($exception->getMessage(), $exception);
        }
    }
}
