<?php

namespace App\Infrastructure\Repository;

use App\Domain\Entity\Client;
use App\Domain\Exception\DomainException;
use App\Domain\Factory\ClientFactory;
use App\Domain\Repository\ClientRepository;
use App\Domain\ValueObject\Id;
use Doctrine\DBAL\Connection;
use Throwable;

class DbalClientRepository implements ClientRepository
{
    public function __construct(
        private Connection $connection,
    ) {
    }

    /**
     * @throws DomainException
     */
    public function findById(Id $id): ?Client
    {
        try {
            $line = $this->connection->executeQuery(
                <<<SQL
                SELECT
                    id,
                    firstname "firstName",
                    lastname "lastName",
                    birthday,
                    city,
                    state,
                    zip,
                    ssn,
                    fico,
                    email,
                    phone,
                    monthincome "monthIncome"
                FROM client
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

        return ClientFactory::createFromArray($line);
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
                    firstname "firstName",
                    lastname "lastName",
                    birthday,
                    city,
                    state,
                    zip,
                    ssn,
                    fico,
                    email,
                    phone,
                    monthincome "monthIncome"
                FROM client
                SQL,
            )->fetchAllAssociative();
        } catch (Throwable $exception) {
            throw new DomainException($exception->getMessage(), $exception);
        }

        if ([] === $lines) {
            return [];
        }

        return ClientFactory::createFromArrays($lines);
    }

    /**
     * @throws DomainException
     */
    public function save(Client $entity): void
    {
        try {
            $this->connection->executeStatement(
                <<<SQL
            INSERT INTO client (
                id,
                firstname,
                lastname,
                birthday,
                city,
                state,
                zip,
                ssn,
                fico,
                email,
                phone,
                monthincome
            ) VALUES (
                :id,
                :firstname,
                :lastname,
                :birthday,
                :city,
                :state,
                :zip,
                :ssn,
                :fico,
                :email,
                :phone,
                :monthincome
            ) ON CONFLICT (id) DO UPDATE SET
                firstname = :firstname,
                lastname = :lastname,
                birthday = :birthday,
                city = :city,
                state = :state,
                zip = :zip,
                ssn = :ssn,
                fico = :fico,
                email = :email,
                phone = :phone,
                monthincome = :monthincome
            SQL,
                [
                    'id' => $entity->getId()->getValue(),
                    'firstname' => $entity->getFirstName(),
                    'lastname' => $entity->getLastName(),
                    'birthday' => $entity->getBirthday()->format(
                        $this->connection->getDatabasePlatform()->getDateTimeFormatString()
                    ),
                    'city' => $entity->getAddress()->getCity(),
                    'state' => $entity->getAddress()->getState(),
                    'zip' => $entity->getAddress()->getZip(),
                    'ssn' => $entity->getSsn()->getValue(),
                    'fico' => $entity->getFico()->getValue(),
                    'email' => $entity->getEmail()->getValue(),
                    'phone' => $entity->getPhone()->getValue(),
                    'monthincome' => $entity->getMonthIncome(),
                ],
            );
        } catch (Throwable $exception) {
            throw new DomainException($exception->getMessage(), $exception);
        }
    }
}
