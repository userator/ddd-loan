<?php

namespace App\Presentation\CLI;

use App\Application\Dto\ClientDto;
use App\Application\UseCase\ClientScore as ClientScoreUseCase;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;

class ClientScore extends Command
{
    public function __construct(
        private ClientScoreUseCase $useCase,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setName('app:client-score')
            ->setDescription('Оценить платежеспособность существующего клиента')
            ->setHelp('Эта команда оценивает платежеспособность существующего клиента.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        /**
         * @var QuestionHelper $helper
         */
        $helper = $this->getHelper('question');

        $clientLines = array_map(
            fn(ClientDto $dto) => $dto->castToArray(),
            $this->useCase->findClients(),
        );

        (new Table($output))
            ->setHeaders(array_keys(current($clientLines)))
            ->setRows($clientLines)
            ->render();

        $clientId = (string)$helper->ask($input, $output, new ChoiceQuestion('Введите ID клиента: ', array_column($clientLines, 'id')));

        $output->writeln('');
        $output->writeln(
            $this->useCase->scoreClient($clientId)
                ? 'Клиент оценен платежеспособным'
                : 'Клиент оценен неплатежеспособным'
        );

        return Command::SUCCESS;
    }
}
