<?php

namespace App\Presentation\CLI;

use App\Application\UseCase\ProductCreate as ProductCreateUseCase;
use App\Presentation\Tool\ProductCaster;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class ProductCreate extends Command
{

    public function __construct(
        private ProductCreateUseCase $useCase,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setName('app:product-create')
            ->setDescription('Create products')
            ->setHelp('This command created available products');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        /**
         * @var QuestionHelper
         */
        $helper = $this->getHelper('question');

        $product = $this->useCase->createProduct([
            'name' => $helper->ask($input, $output, new Question('Введите название: ', 'симплекс')),
            'term' => $helper->ask($input, $output, new Question('Введите длительность: ', 365)),
            'interestRate' => $helper->ask($input, $output, new Question('Введите ставку: ', 4.0)),
            'amount' => $helper->ask($input, $output, new Question('Введите сумму: ', 4000)),
        ]);

        $output->writeln('');
        $output->writeln('Создан продукт с ID [' . $product->getId() . ']');

        $productLines = ProductCaster::batchCastToArray([$product]);

        (new Table($output))
            ->setHeaders(array_keys(current($productLines)))
            ->setRows($productLines)
            ->render();

        return Command::SUCCESS;
    }
}