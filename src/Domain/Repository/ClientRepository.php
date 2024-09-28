<?php

namespace App\Domain\Repository;

use App\Domain\Entity\Client;
use App\Domain\ValueObject\Id;

interface ClientRepository
{
    public function findById(Id $id): ?Client;

    /**
     * @return Client[]
     */
    public function findAll(): array;

    public function save(Client $entity): void;
}