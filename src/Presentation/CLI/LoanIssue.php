<?php

namespace App\Presentation\CLI;

use App\Application\Dto\ClientDto;
use App\Application\Dto\ProductDto;
use App\Application\UseCase\LoanIssue as LoanIssueUseCase;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;

class LoanIssue extends Command
{

    public function __construct(
        private LoanIssueUseCase $useCase,

    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setName('app:loan-issue')
            ->setDescription('Выдать новый займ')
            ->setHelp('Эта команда выдаёт новый займ.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        /**
         * @var QuestionHelper $helper
         */
        $helper = $this->getHelper('question');

        // клиент

        $clientLines = array_map(
            fn(ClientDto $dto) => $dto->castToArray(),
            $this->useCase->findClients(),
        );

        (new Table($output))
            ->setHeaders(array_keys(current($clientLines)))
            ->setRows($clientLines)
            ->render();

        $clientId = (string)$helper->ask($input, $output, new ChoiceQuestion('Введите ID клиента: ', array_column($clientLines, 'id')));

        // продукт

        $productLines = array_map(
            fn(ProductDto $dto) => $dto->castToArray(),
            $this->useCase->findProducts(),
        );

        (new Table($output))
            ->setHeaders(array_keys(current($productLines)))
            ->setRows($productLines)
            ->render();

        $productId = (string)$helper->ask($input, $output, new ChoiceQuestion('Введите ID продукта: ', array_column($productLines, 'id')));

        // займ

        $loan = $this->useCase->issueLoan($clientId, $productId);

        $output->writeln('');
        $output->writeln('Займ выдан ID [' . $loan->getId() . ']');

        $loanLines = [$loan->castToArray()];

        (new Table($output))
            ->setHeaders(array_keys(current($loanLines)))
            ->setRows($loanLines)
            ->render();

        return Command::SUCCESS;
    }
}