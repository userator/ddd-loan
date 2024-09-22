<?php

namespace App\Application\UseCase;

use App\Application\Exception\ApplicationException;
use App\Domain\Entity\Client;
use App\Domain\Repository\ClientRepository;
use App\Domain\ValueObject\Address;
use App\Domain\ValueObject\Email;
use App\Domain\ValueObject\Fico;
use App\Domain\ValueObject\Phone;
use App\Domain\ValueObject\Ssn;
use Throwable;

class ClientModify
{
    public function __construct(
        private ClientRepository $repository,
    ) {
    }

    /**
     * @throws ApplicationException
     */
    public function findClient(string $clientId): Client
    {
        $client = $this->repository->findById($clientId);

        if (null === $client) {
            throw new ApplicationException(sprintf('Клиент не найден по ID [%s]', $clientId));
        }

        return $client;
    }

    /**
     * @param array{
     *     lastName:string,
     *     name:string,
     *     age:int,
     *     city:string,
     *     state:string,
     *     zip:string,
     *     ssn:string,
     *     fico:int,
     *     phone:string,
     *     email:string,
     *     monthIncome:int,
     *  } $data
     * @throws ApplicationException
     */
    public function modifyClient(string $clientId, array $data): Client
    {
        try {
            $client = new Client(
                $clientId,
                (string)$data['lastName'],
                (string)$data['name'],
                (int)$data['age'],
                new Address(
                    (string)$data['city'],
                    (string)$data['state'],
                    (string)$data['zip'],
                ),
                new Ssn((string)$data['ssn']),
                new Fico((int)$data['fico']),
                new Email((string)$data['email']),
                new Phone((string)$data['phone']),
                (int)$data['monthIncome'],
            );

            $this->repository->save($client);

            return $client;
        } catch (Throwable $exception) {
            throw new ApplicationException($exception->getMessage(), $exception);
        }
    }
}