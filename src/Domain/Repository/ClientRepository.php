<?php

namespace App\Domain\Repository;

use App\Domain\Entity\Client;

interface ClientRepository
{
    public function findById(string $id): ?Client;

    /**
     * @return Client[]
     */
    public function findAll(): array;

    public function save(Client $entity): void;
}