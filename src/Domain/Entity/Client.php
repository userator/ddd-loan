<?php

namespace App\Domain\Entity;

use App\Domain\Exception\DomainException;
use App\Domain\Service\Decider;
use App\Domain\ValueObject\Address;
use App\Domain\ValueObject\Email;
use App\Domain\ValueObject\Fico;
use App\Domain\ValueObject\Id;
use App\Domain\ValueObject\Name;
use App\Domain\ValueObject\Phone;
use App\Domain\ValueObject\Ssn;
use DateTimeImmutable;

class Client
{
    /**
     * @throws DomainException
     */
    public function __construct(
        private Id $id,
        private Name $lastName,
        private Name $firstName,
        private DateTimeImmutable $birthday,
        private Address $address,
        private Ssn $ssn,
        private Fico $fico,
        private Email $email,
        private Phone $phone,
        private int $monthIncome,
    ) {
        if (0 >= $this->birthday->diff(new DateTimeImmutable())->y) {
            throw new DomainException(sprintf('Некорректный день рождения [%s], должен быть больше 0', $this->birthday->diff(new DateTimeImmutable())->y));
        }

        if (0 >= $this->monthIncome) {
            throw new DomainException(sprintf('Некорректный месячный доход [%s], должен быть больше 0', $this->monthIncome));
        }
    }

    // mutators

    public function getId(): Id
    {
        return $this->id;
    }

    public function getLastName(): Name
    {
        return $this->lastName;
    }

    public function getFirstName(): Name
    {
        return $this->firstName;
    }

    public function getBirthday(): DateTimeImmutable
    {
        return $this->birthday;
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

    public function buildFullName(): string
    {
        return $this->firstName->getValue() . ' ' . $this->lastName->getValue();
    }

    public function calcAge(): int
    {
        return $this->birthday->diff(new DateTimeImmutable())->y;
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
    public function score(Decider $decider): bool
    {
        if ($this->getFico()->getValue() <= 500) {
            return false;
        }

        if ($this->getMonthIncome() < 1000) {
            return false;
        }

        if ($this->calcAge() < 18) {
            return false;
        }

        if ($this->calcAge() > 60) {
            return false;
        }

        if (!in_array($this->getAddress()->getState(), ['CA', 'NY', 'NV'])) {
            return false;
        }

        if ('NY' === $this->getAddress()->getState() && !$decider->decide()) {
            return false;
        }

        return true;
    }
}