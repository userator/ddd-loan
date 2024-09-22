<?php

namespace App\Presentation\CLI;

use App\Application\UseCase\LoanList as LoanListUseCase;
use App\Presentation\Tool\LoanCaster;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class LoanList extends Command
{
    public function __construct(
        private LoanListUseCase $useCase,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setName('app:loan-list')
            ->setDescription('List loans')
            ->setHelp('This command list available loans');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $loanLines = LoanCaster::batchCastToArray(
            $this->useCase->listLoans(),
        );

        (new Table($output))
            ->setHeaders(array_keys(current($loanLines)))
            ->setRows($loanLines)
            ->render();

        return Command::SUCCESS;
    }
}