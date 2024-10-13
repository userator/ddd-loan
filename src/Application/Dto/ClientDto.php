<?php

namespace App\Application\Dto;

class ClientDto
{
    public const BIRTHDAY_FORMAT = 'Y-m-d';

    public function __construct(
        private string $id,
        private string $lastName,
        private string $firstName,
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

    // mutators

    public function getId(): string
    {
        return $this->id;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
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
     *     firstName:string,
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