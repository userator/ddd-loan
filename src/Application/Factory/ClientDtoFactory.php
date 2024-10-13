<?php

namespace App\Application\Factory;

use App\Application\Dto\ClientDto;
use App\Domain\Entity\Client;

class ClientDtoFactory
{
    public static function createFromEntity(Client $entity): ClientDto
    {
        return new ClientDto(
            $entity->getId()->getValue(),
            $entity->getLastName(),
            $entity->getFirstName(),
            $entity->getBirthday()->format(ClientDto::BIRTHDAY_FORMAT),
            $entity->getAddress()->getCity(),
            $entity->getAddress()->getState(),
            $entity->getAddress()->getZip(),
            $entity->getSsn()->getValue(),
            $entity->getFico()->getValue(),
            $entity->getEmail()->getValue(),
            $entity->getPhone()->getValue(),
            $entity->getMonthIncome(),
        );
    }

    /**
     * @param Client[] $entities
     * @return ClientDto[]
     */
    public static function createFromEntities(array $entities): array
    {
        return array_map(
            static fn(Client $entity) => self::createFromEntity($entity),
            $entities,
        );
    }
}