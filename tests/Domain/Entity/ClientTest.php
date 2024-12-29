<?php

namespace App\Tests\Domain\Entity;

use App\Domain\Entity\Client;
use App\Domain\Exception\DomainException;
use App\Domain\Service\Decider;
use App\Domain\ValueObject\Address;
use App\Domain\ValueObject\Email;
use App\Domain\ValueObject\Fico;
use App\Domain\ValueObject\Id;
use App\Domain\ValueObject\Name;
use App\Domain\ValueObject\Phone;
use App\Domain\ValueObject\Ssn;
use App\Infrastructure\Service\FakeStateDecider;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

class ClientTest extends TestCase
{
    /**
     * @throws DomainException
     */
    public static function provideCheckPossibility(): array
    {
        return [
            'success' => [
                'client' => new Client(
                    new Id('550e8400-e29b-41d4-a716-446655440000'),
                    new Name('Безфамильный'),
                    new Name('Нонейм'),
                    new DateTimeImmutable('-40 years'),
                    new Address(
                        'Unknown City',
                        'CA',
                        '10001',
                    ),
                    new Ssn('333-22-4444'),
                    new Fico(600),
                    new Email('noname@domain.com'),
                    new Phone('333-333-4444'),
                    5000,
                ),
                'decider' => new FakeStateDecider(true),
                'result' => true,
            ],
            'fail credit score' => [
                'client' => new Client(
                    new Id('550e8400-e29b-41d4-a716-446655440000'),
                    new Name('Безфамильный'),
                    new Name('Нонейм'),
                    new DateTimeImmutable('-40 years'),
                    new Address(
                        'Unknown City',
                        'CA',
                        '10001',
                    ),
                    new Ssn('333-22-4444'),
                    new Fico(300),
                    new Email('noname@domain.com'),
                    new Phone('333-333-4444'),
                    5000,
                ),
                'decider' => new FakeStateDecider(true),
                'result' => false,
            ],
            'fail month income' => [
                'client' => new Client(
                    new Id('550e8400-e29b-41d4-a716-446655440000'),
                    new Name('Безфамильный'),
                    new Name('Нонейм'),
                    new DateTimeImmutable('-40 years'),
                    new Address(
                        'Unknown City',
                        'CA',
                        '10001',
                    ),
                    new Ssn('333-22-4444'),
                    new Fico(600),
                    new Email('noname@domain.com'),
                    new Phone('333-333-4444'),
                    100,
                ),
                'decider' => new FakeStateDecider(true),
                'result' => false,
            ],
            'fail age' => [
                'client' => new Client(
                    new Id('550e8400-e29b-41d4-a716-446655440000'),
                    new Name('Безфамильный'),
                    new Name('Нонейм'),
                    new DateTimeImmutable('-99 years'),
                    new Address(
                        'Unknown City',
                        'CA',
                        '10001',
                    ),
                    new Ssn('333-22-4444'),
                    new Fico(600),
                    new Email('noname@domain.com'),
                    new Phone('333-333-4444'),
                    5000,
                ),
                'decider' => new FakeStateDecider(true),
                'result' => false,
            ],
            'fail excluded state (WA)' => [
                'client' => new Client(
                    new Id('550e8400-e29b-41d4-a716-446655440000'),
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
                'decider' => new FakeStateDecider(true),
                'result' => false,
            ],
            'fail randomize state (NY)' => [
                'client' => new Client(
                    new Id('550e8400-e29b-41d4-a716-446655440000'),
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
                'decider' => new FakeStateDecider(false),
                'result' => false,
            ],
            'success randomize state (NY)' => [
                'client' => new Client(
                    new Id('550e8400-e29b-41d4-a716-446655440000'),
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
                'decider' => new FakeStateDecider(true),
                'result' => true,
            ],
        ];
    }

    /**
     * @dataProvider provideCheckPossibility()
     */
    public function testCheckPossibility(Client $client, Decider $decider, bool $result): void
    {
        $this->assertEquals($result, $client->score($decider));
    }
}
