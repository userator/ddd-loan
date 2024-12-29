<?php

namespace App\Presentation\CLI;

use App\Application\Exception\ApplicationException;
use App\Application\UseCase\ProductCreate as ProductCreateUseCase;
use Faker\Factory;
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
            ->setDescription('Создать новый продукт')
            ->setHelp('Эта команда создаёт новый продукт');
    }

    /**
     * @throws ApplicationException
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        /**
         * @var QuestionHelper $helper
         */
        $helper = $this->getHelper('question');
        $faker = Factory::create();

        $product = $this->useCase->createProduct([
            'name' => $helper->ask($input, $output, new Question('Введите название: ', $faker->word())),
            'term' => $helper->ask($input, $output, new Question('Введите длительность в днях: ', $faker->numberBetween(1, 365))),
            'interestRate' => $helper->ask($input, $output, new Question('Введите ставку (#.#): ', $faker->randomFloat(1, 1.0, 19.9))),
            'amount' => $helper->ask($input, $output, new Question('Введите сумму: ', $faker->numberBetween(1000, 99999))),
        ]);

        $output->writeln('');
        $output->writeln('Создан продукт с ID [' . $product->getId() . ']');

        $productLines = [$product->castToArray()];

        (new Table($output))
            ->setHeaders(array_keys(current($productLines)))
            ->setRows($productLines)
            ->render();

        return Command::SUCCESS;
    }
}