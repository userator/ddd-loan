<?php

namespace App\Infrastructure\Repository;

use App\Domain\Entity\Loan;
use App\Domain\Exception\DomainException;
use App\Domain\Factory\LoanFactory;
use App\Domain\Repository\LoanRepository;
use App\Domain\ValueObject\Id;
use Doctrine\DBAL\Connection;
use Throwable;

class DbalLoanRepository implements LoanRepository
{
    public function __construct(
        private Connection $connection,
    ) {
    }

    /**
     * @throws DomainException
     */
    public function findById(Id $id): ?Loan
    {
        try {
            $line = $this->connection->executeQuery(
                <<<SQL
                SELECT
                    loan.id loan_id,
                    loan.issuedat "loan_issuedAt",
                    client.id client_id,
                    client.firstname "client_firstName",
                    client.lastname "client_lastName",
                    client.birthday client_birthday,
                    client.city client_city,
                    client.state client_state,
                    client.zip client_zip,
                    client.ssn client_ssn,
                    client.fico client_fico,
                    client.email client_email,
                    client.phone client_phone,
                    client.monthincome "client_monthIncome",
                    product.id product_id,
                    product.name product_name,
                    product.term product_term,
                    product.interestrate "product_interestRate",
                    product.amount product_amount
                FROM loan
                INNER JOIN client ON client.id = loan.clientid
                INNER JOIN product ON product.id = loan.productid
                WHERE loan.id = :id
                SQL,
                ['id' => $id->getValue()],
            )->fetchAssociative();
        } catch (Throwable $exception) {
            throw new DomainException($exception->getMessage(), $exception);
        }

        if (false === $line) {
            return null;
        }

        $data = $this->extractPrefixedLine('loan_', $line);
        $data['client'] = $this->extractPrefixedLine('client_', $line);
        $data['product'] = $this->extractPrefixedLine('product_', $line);

        return LoanFactory::createFromArray($data);
    }

    /**
     * @inheritDoc
     * @throws DomainException
     */
    public function findAll(): array
    {
        try {
            $lines = $this->connection->executeQuery(
                <<<SQL
                SELECT
                    loan.id loan_id,
                    loan.issuedat "loan_issuedAt",
                    client.id client_id,
                    client.firstname "client_firstName",
                    client.lastname "client_lastName",
                    client.birthday client_birthday,
                    client.city client_city,
                    client.state client_state,
                    client.zip client_zip,
                    client.ssn client_ssn,
                    client.fico client_fico,
                    client.email client_email,
                    client.phone client_phone,
                    client.monthincome "client_monthIncome",
                    product.id product_id,
                    product.name product_name,
                    product.term product_term,
                    product.interestrate "product_interestRate",
                    product.amount product_amount
                FROM loan
                INNER JOIN client ON client.id = loan.clientid
                INNER JOIN product ON product.id = loan.productid
                SQL,
            )->fetchAllAssociative();
        } catch (Throwable $exception) {
            throw new DomainException($exception->getMessage(), $exception);
        }

        if ([] === $lines) {
            return [];
        }

        $datas = array_map(
            function (array $line): array {
                $data = $this->extractPrefixedLine('loan_', $line);
                $data['client'] = $this->extractPrefixedLine('client_', $line);
                $data['product'] = $this->extractPrefixedLine('product_', $line);
                return $data;
            },
            $lines,
        );

        return LoanFactory::createFromArrays($datas);
    }

    /**
     * @throws DomainException
     */
    public function save(Loan $entity): void
    {
        try {
            $this->connection->executeStatement(
                <<<SQL
                INSERT INTO loan (
                    id,
                    clientid,
                    productid,
                    issuedat
                ) VALUES (
                    :id,
                    :clientid,
                    :productid,
                    :issuedat
                ) ON CONFLICT (id) DO UPDATE SET
                    clientid = :clientid,
                    productid = :productid,
                    issuedat = :issuedat
                SQL,
                [
                    'id' => $entity->getId()->getValue(),
                    'clientid' => $entity->getClient()->getId()->getValue(),
                    'productid' => $entity->getProduct()->getId()->getValue(),
                    'issuedat' => $entity->getIssuedAt()->format(
                        $this->connection->getDatabasePlatform()->getDateTimeFormatString()
                    ),
                ],
            );
        } catch (Throwable $exception) {
            throw new DomainException($exception->getMessage(), $exception);
        }
    }

    /**
     * @param string $prefix
     * @param array<mixed> $line
     * @return array<mixed>
     */
    private function extractPrefixedLine(string $prefix, array $line): array
    {
        $newline = [];

        foreach ($line as $key => $value) {
            if (str_starts_with($key, $prefix)) {
                $newline[str_replace($prefix, '', $key)] = $value;
            }
        }

        return $newline;
    }
}
