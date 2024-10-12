<?php

namespace App\Application\UseCase;

use App\Application\Dto\ClientDto;
use App\Application\Dto\LoanDto;
use App\Application\Dto\ProductDto;
use App\Application\Exception\ApplicationException;
use App\Application\Factory\ClientDtoFactory;
use App\Application\Factory\LoanDtoFactory;
use App\Application\Factory\ProductDtoFactory;
use App\Application\Service\EventDispatcher;
use App\Domain\Entity\Loan;
use App\Domain\Event\LoanIssued;
use App\Domain\Repository\ClientRepository;
use App\Domain\Repository\LoanRepository;
use App\Domain\Repository\ProductRepository;
use App\Domain\Service\Randomizer;
use App\Domain\Service\UuidGenerator;
use App\Domain\ValueObject\Id;
use Throwable;

class LoanIssue
{
    public function __construct(
        private ClientRepository $clientRepository,
        private ProductRepository $productRepository,
        private LoanRepository $loanRepository,
        private EventDispatcher $dispatcher,
        private Randomizer $randomizer,
        private UuidGenerator $uuidGenerator,
    ) {
    }

    /**
     * @return ClientDto[]
     * @throws ApplicationException
     */
    public function findClients(): array
    {
        $clients = $this->clientRepository->findAll();

        if ([] === $clients) {
            throw new ApplicationException('Клиентов не найдено');
        }

        return ClientDtoFactory::createFromEntities($clients);
    }

    /**
     * @return ProductDto[]
     * @throws ApplicationException
     */
    public function findProducts(): array
    {
        $products = $this->productRepository->findAll();

        if ([] === $products) {
            throw new ApplicationException('Продуктов не найдено');
        }

        return ProductDtoFactory::createFromEntities($products);
    }

    /**
     * @throws ApplicationException
     */
    public function issueLoan(string $clientId, string $productId): LoanDto
    {
        try {
            $client = $this->clientRepository->findById(new Id($clientId));
        } catch (Throwable $exception) {
            throw new ApplicationException(sprintf('Клиент не найден по ID [%s]', $clientId), $exception);
        }

        if (null === $client) {
            throw new ApplicationException('Клиент не найден');
        }

        try {
            $product = $this->productRepository->findById(new Id($productId));
        } catch (Throwable $exception) {
            throw new ApplicationException(sprintf('Продукт не найден по ID [%s]', $productId), $exception);
        }

        if (null === $product) {
            throw new ApplicationException('Продукт не найден');
        }

        if (!$client->checkPossibility($this->randomizer)) {
            throw new ApplicationException('Нельзя выдать займ клиенту');
        }

        try {
            $loan = new Loan(
                new Id($this->uuidGenerator->generate()),
                $client,
                $product,
            );

            $this->loanRepository->save($loan);

            $this->dispatcher->dispatch(new LoanIssued($loan->getId()->getValue()));

            return LoanDtoFactory::createFromEntity($loan);
        } catch (Throwable $exception) {
            throw new ApplicationException($exception->getMessage(), $exception);
        }
    }
}