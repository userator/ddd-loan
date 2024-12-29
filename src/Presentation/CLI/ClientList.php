<?php

namespace App\Presentation\CLI;

use App\Application\Dto\ClientDto;
use App\Application\Exception\ApplicationException;
use App\Application\UseCase\ClientList as ClientListUseCase;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ClientList extends Command
{
    public function __construct(
        private ClientListUseCase $useCase,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setName('app:client-list')
            ->setDescription('Показать список клиентов')
            ->setHelp('Эта команда показывает список клиентов');
    }

    /**
     * @throws ApplicationException
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $clientLines = array_map(
            fn(ClientDto $dto) => $dto->castToArray(),
            $this->useCase->listClients(),
        );

        (new Table($output))
            ->setHeaders(array_keys(current($clientLines)))
            ->setRows($clientLines)
            ->render();

        return Command::SUCCESS;
    }
}