<?php

namespace App\Application\Dto;

use App\Application\Exception\ApplicationException;
use App\Domain\Entity\Client as ClientEntity;

class ClientDto
{
    public const BIRTHDAY_FORMAT = 'd.m.Y';

    public function __construct(
        private string $id,
        private string $lastName,
        private string $name,
        private string $birthday,
        private string $city,
        private string $state,
        private string $zip,
        private string $ssn,
        private int $fico,
        private string $email,
        private string $phone,
        private int $monthIncome,
    ) {
    }

    public static function createFromEntity(ClientEntity $entity): self
    {
        return new self(
            $entity->getId()->getValue(),
            $entity->getLastName(),
            $entity->getName(),
            $entity->getBirthday()->format(self::BIRTHDAY_FORMAT),
            $entity->getAddress()->getCity(),
            $entity->getAddress()->getState(),
            $entity->getAddress()->getZip(),
            $entity->getSsn()->getValue(),
            $entity->getFico()->getValue(),
            $entity->getPhone()->getValue(),
            $entity->getEmail()->getValue(),
            $entity->getMonthIncome(),
        );
    }

    /**
     * @param array{
     *      id?:string,
     *      lastName?:string,
     *      name?:string,
     *      birthday?:string,
     *      city?:string,
     *      state?:string,
     *      zip?:string,
     *      ssn?:string,
     *      fico?:int,
     *      email?:string,
     *      phone?:string,
     *      monthIncome?:int,
     *  } $data
     * @throws ApplicationException
     */
    public static function createFromArray(array $data): self
    {
        if (!isset(
            $data['id'],
            $data['lastName'],
            $data['name'],
            $data['birthday'],
            $data['city'],
            $data['state'],
            $data['zip'],
            $data['ssn'],
            $data['fico'],
            $data['email'],
            $data['phone'],
            $data['monthIncome'],
        )) {
            throw new ApplicationException('Invalid argument');
        }

        return new self(
            $data['id'],
            $data['lastName'],
            $data['name'],
            $data['birthday'],
            $data['city'],
            $data['state'],
            $data['zip'],
            $data['ssn'],
            $data['fico'],
            $data['email'],
            $data['phone'],
            $data['monthIncome'],
        );
    }

    // mutators

    public function getId(): string
    {
        return $this->id;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getBirthday(): string
    {
        return $this->birthday;
    }

    public function getCity(): string
    {
        return $this->city;
    }

    public function getState(): string
    {
        return $this->state;
    }

    public function getZip(): string
    {
        return $this->zip;
    }

    public function getSsn(): string
    {
        return $this->ssn;
    }

    public function getFico(): int
    {
        return $this->fico;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPhone(): string
    {
        return $this->phone;
    }

    public function getMonthIncome(): int
    {
        return $this->monthIncome;
    }

    // business logic

    /**
     * @return array{
     *     id:string,
     *     lastName:string,
     *     name:string,
     *     birthday:string,
     *     city:string,
     *     state:string,
     *     zip:string,
     *     ssn:string,
     *     fico:int,
     *     phone:string,
     *     email:string,
     *     monthIncome:int,
     * }
     */
    public function castToArray(): array
    {
        return get_object_vars($this);
    }
}