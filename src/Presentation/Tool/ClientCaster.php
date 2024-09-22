<?php

namespace App\Presentation\Tool;

use App\Domain\Entity\Client;

class ClientCaster
{
    /**
     * @param Client[] $entities
     * @return array<array{
     *      id:string,
     *      lastName:string,
     *      name:string,
     *      age:int,
     *      address:string,
     *      ssn:string,
     *      fico:int,
     *      phone:string,
     *      email:string,
     *      monthIncome:int,
     *  }>
     */
    public static function batchCastToArray(array $entities): array
    {
        return array_map(
            fn(Client $entity) => [
                'id' => $entity->getId(),
                'lastName' => $entity->getLastName(),
                'name' => $entity->getName(),
                'age' => $entity->getAge(),
                'address' => $entity->getAddress()->makeValue(),
                'ssn' => $entity->getSsn()->getValue(),
                'fico' => $entity->getFico()->getValue(),
                'phone' => $entity->getPhone()->getValue(),
                'email' => $entity->getEmail()->getValue(),
                'monthIncome' => $entity->getMonthIncome(),
            ],
            $entities,
        );
    }
}