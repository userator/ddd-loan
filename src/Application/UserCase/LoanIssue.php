<?php

namespace App\Application\UserCase;

use App\Application\Exception\ApplicationException;
use App\Application\Factory\LoanFactory;
use App\Application\Service\EventDispatcher;
use App\Domain\Entity\Client;
use App\Domain\Entity\Loan;
use App\Domain\Entity\Product;
use App\Domain\Event\LoanIssued;
use App\Domain\Repository\ClientRepository;
use App\Domain\Repository\LoanRepository;
use App\Domain\Repository\ProductRepository;
use App\Domain\Service\LoanIssuer;
use App\Domain\Service\LoanOfferer;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Uid\UuidV4;
use Throwable;

class LoanIssue
{
    public function __construct(
        private ClientRepository $clientRepository,
        private ProductRepository $productRepository,
        private LoanRepository $loanRepository,
        private EventDispatcher $dispatcher,
    ) {
    }

    /**
     * @return Client[]
     * @throws ApplicationException
     */
    public function findClients(): array
    {
        $clients = $this->clientRepository->findAll();

        if ([] === $clients) {
            throw new ApplicationException('Клиентов не найдено');
        }

        return $clients;
    }

    /**
     * @return Product[]
     * @throws ApplicationException
     */
    public function findProducts(): array
    {
        $products = $this->productRepository->findAll();

        if ([] === $products) {
            throw new ApplicationException('Продуктов не найдено');
        }

        return $products;
    }

    public function issueLoan(string $clientId, string $productId): Loan
    {
        $client = $this->clientRepository->findById($clientId);

        if (null === $client) {
            throw new ApplicationException('Клиент не найден');
        }

        $product = $this->productRepository->findById($productId);

        if (null === $product) {
            throw new ApplicationException('Продукт не найден');
        }

        if (!$client->checkPossibility()) {
            throw new ApplicationException('Нельзя выдать займ клиенту');
        }

        try {
            $loan = new Loan(
                (new UuidV4())->toRfc4122(),
                $client,
                $product,
            );

            $this->loanRepository->save($loan);

            $this->dispatcher->dispatch(new LoanIssued($loan));

            return $loan;
        } catch (Throwable $exception) {
            throw new ApplicationException($exception->getMessage(), $exception);
        }
    }
}