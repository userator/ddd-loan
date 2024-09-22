<?php

namespace App\Domain\Entity;

use App\Domain\ValueObject\Address;
use App\Domain\ValueObject\Email;
use App\Domain\ValueObject\Fico;
use App\Domain\ValueObject\Phone;
use App\Domain\ValueObject\Ssn;

class Client
{
    public function __construct(
        private string $id,
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

    /**
     * алгоритм расчета кредитного рейтинга
     */
    public function calcCreditScore(): int
    {
        return 800;
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
    public function checkPossibility(): bool
    {
        if ($this->calcCreditScore() <= 500) {
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
            return (bool)rand(0, 1);
        }

        return true;
    }
}