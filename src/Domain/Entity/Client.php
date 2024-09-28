<?php

namespace App\Domain\Entity;

use App\Domain\Exception\DomainException;
use App\Domain\Service\Randomizer;
use App\Domain\ValueObject\Address;
use App\Domain\ValueObject\Email;
use App\Domain\ValueObject\Fico;
use App\Domain\ValueObject\Id;
use App\Domain\ValueObject\Phone;
use App\Domain\ValueObject\Ssn;

class Client
{
    public function __construct(
        private Id $id,
        private string $lastName,
        private string $name,
        private int $age,
        private Address $address,
        private Ssn $ssn,
        private Fico $fico,
        private Email $email,
        private Phone $phone,
        private int $monthIncome,
    ) {
        $this->lastName = trim($this->lastName);
        $this->name = trim($this->name);
    }

    /**
     * @param array<mixed> $data
     * @throws DomainException
     */
    public static function createFromArray(array $data): self
    {
        if (!isset(
            $data['id'],
            $data['lastName'],
            $data['name'],
            $data['age'],
            $data['ssn'],
            $data['fico'],
            $data['email'],
            $data['phone'],
            $data['monthIncome'],
        )) {
            throw new DomainException('Invalid argument');
        }

        return new self(
            new Id((string)$data['id']),
            (string)$data['lastName'],
            (string)$data['name'],
            (int)$data['age'],
            Address::createFromArray($data),
            new Ssn((string)$data['ssn']),
            new Fico((int)$data['fico']),
            new Email((string)$data['email']),
            new Phone((string)$data['phone']),
            (int)$data['monthIncome'],
        );
    }

    // mutators

    public function getId(): Id
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

    public function getAge(): int
    {
        return $this->age;
    }

    public function getAddress(): Address
    {
        return $this->address;
    }

    public function getSsn(): Ssn
    {
        return $this->ssn;
    }

    public function getFico(): Fico
    {
        return $this->fico;
    }

    public function getEmail(): Email
    {
        return $this->email;
    }

    public function getPhone(): Phone
    {
        return $this->phone;
    }

    public function getMonthIncome(): int
    {
        return $this->monthIncome;
    }

    // business logic

    public function getFullName(): string
    {
        return $this->name . ' ' . $this->lastName;
    }

    /*
     * Проверка возможности выдачи кредита.
     *
     * Кредитный рейтинг клиента должен быть больше 500.
     * Доход клиента должен быть не менее 1000$ в месяц.
     * Возраст клиента от 18 до 60 лет.
     * Кредит выдается только в штатах CA, NY, NV
     * Клиентам из NY рандомно отказываем.
     */
    public function checkPossibility(Randomizer $randomizer): bool
    {
        if ($this->getFico()->getValue() <= 500) {
            return false;
        }

        if ($this->getMonthIncome() < 1000) {
            return false;
        }

        if ($this->getAge() < 18 || $this->getAge() > 60) {
            return false;
        }

        if (!in_array($this->getAddress()->getState(), ['CA', 'NY', 'NV'])) {
            return false;
        }

        if ('NY' === $this->getAddress()->getState()) {
            return $randomizer->randomize();
        }

        return true;
    }
}