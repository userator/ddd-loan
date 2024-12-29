<?php

namespace App\Tests\Domain\Entity;

use App\Domain\Entity\Client;
use App\Domain\Entity\Loan;
use App\Domain\Entity\Product;
use App\Domain\Exception\DomainException;
use App\Domain\ValueObject\Address;
use App\Domain\ValueObject\Email;
use App\Domain\ValueObject\Fico;
use App\Domain\ValueObject\Id;
use App\Domain\ValueObject\Name;
use App\Domain\ValueObject\Phone;
use App\Domain\ValueObject\Ssn;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

class LoanTest extends TestCase
{
    /**
     * @throws DomainException
     */
    public static function provideCalcInterestRate(): array
    {
        return [
            'для NY (ставка продукта + NY коэффициент)' => [
                'loan' => new Loan(
                    new Id('550e8400-e29b-41d4-a716-446655440001'),
                    new Client(
                        new Id('550e8400-e29b-41d4-a716-446655440002'),
                        new Name('Безфамильный'),
                        new Name('Нонейм'),
                        new DateTimeImmutable('-40 years'),
                        new Address(
                            'Unknown City',
                            'NY',
                            '10001',
                        ),
                        new Ssn('333-22-4444'),
                        new Fico(600),
                        new Email('noname@domain.com'),
                        new Phone('333-333-4444'),
                        5000,
                    ),
                    new Product(
                        new Id('550e8400-e29b-41d4-a716-446655440003'),
                        'Test',
                        365,
                        4.0,
                        5000,
                    ),
                    new DateTimeImmutable(),
                ),
                'result' => 4.0 + Loan::RATE,
            ],
            'для WA (ставка продукта)' => [
                'loan' => new Loan(
                    new Id('550e8400-e29b-41d4-a716-446655440004'),
                    new Client(
                        new Id('550e8400-e29b-41d4-a716-446655440005'),
                        new Name('Безфамильный'),
                        new Name('Нонейм'),
                        new DateTimeImmutable('-40 years'),
                        new Address(
                            'Unknown City',
                            'WA',
                            '10001',
                        ),
                        new Ssn('333-22-4444'),
                        new Fico(600),
                        new Email('noname@domain.com'),
                        new Phone('333-333-4444'),
                        5000,
                    ),
                    new Product(
                        new Id('550e8400-e29b-41d4-a716-446655440006'),
                        'Test',
                        365,
                        4.0,
                        5000,
                    ),
                    new DateTimeImmutable(),
                ),
                'result' => 4.0,
            ],
        ];
    }

    /**
     * @dataProvider provideCalcInterestRate()
     */
    public function testCalcInterestRate(Loan $loan, float $result): void
    {
        self::assertEquals($result, $loan->calcInterestRate());
    }
}
