<?php

namespace App\Domain\ValueObject;

use App\Domain\Exception\DomainException;

class Address
{
    private const ADDRESS_SEPARATOR = ', ';
    private const CITY_REGEX = '/^([A-Za-z]+(?: [A-Za-z]+)*)$/';
    private const SATE_REGEX = '/^[A-Z]{2}$/';
    private const ZIP_REGEX = '/^\d{5}(?:[-\s]\d{4})?$/';

    public function __construct(
        private string $city,
        private string $state,
        private string $zip,
    ) {
        if (false === (bool)preg_match(self::CITY_REGEX, $this->city)) {
            throw new DomainException(sprintf('Invalid city [%s]', $this->city));
        }

        if (false === (bool)preg_match(self::SATE_REGEX, $this->state)) {
            throw new DomainException(sprintf('Invalid state code [%s]', $this->state));
        }

        if (false === (bool)preg_match(self::ZIP_REGEX, $this->zip)) {
            throw new DomainException(sprintf('Invalid ZIP [%s]', $this->zip));
        }
    }

    public static function createFromString(string $address): self
    {
        $parts = explode(self::ADDRESS_SEPARATOR, $address, 3);

        return new self($parts[0] ?? '', $parts[1] ?? '', $parts[2] ?? '');
    }

    // mutators

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

    // tools

    public function makeValue(): string
    {
        return implode(self::ADDRESS_SEPARATOR, [$this->city, $this->state, $this->zip]);
    }
}