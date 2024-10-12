<?php

namespace App\Presentation\CLI;

use App\Application\Dto\ProductDto;
use App\Application\UseCase\ProductList as ProductListUseCase;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ProductList extends Command
{
    public function __construct(
        private ProductListUseCase $useCase,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setName('app:product-list')
            ->setDescription('Показать список продуктов')
            ->setHelp('Эта команда показывает список продуктов');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $productLines = array_map(
            fn(ProductDto $dto) => $dto->castToArray(),
            $this->useCase->listProducts(),
        );

        (new Table($output))
            ->setHeaders(array_keys(current($productLines)))
            ->setRows($productLines)
            ->render();

        return Command::SUCCESS;
    }
}