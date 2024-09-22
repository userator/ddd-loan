<?php

namespace App\Presentation\CLI;

use App\Application\UserCase\ProductList as ProductListUseCase;
use App\Presentation\Tool\ProductCaster;
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
            ->setDescription('List products')
            ->setHelp('This command list available products');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $productLines = ProductCaster::batchCastToArray(
            $this->useCase->listProducts(),
        );

        (new Table($output))
            ->setHeaders(array_keys(current($productLines)))
            ->setRows($productLines)
            ->render();

        return Command::SUCCESS;
    }
}