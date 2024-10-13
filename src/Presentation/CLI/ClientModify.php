<?php

namespace App\Presentation\CLI;

use App\Application\Dto\ClientDto;
use App\Application\UseCase\ClientModify as ClientModifyUseCase;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\Question;

class ClientModify extends Command
{

    public function __construct(
        private ClientModifyUseCase $useCase,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setName('app:client-modify')
            ->setDescription('Изменить существующего клиента')
            ->setHelp('Эта команда изменяет существующего клиента.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        /**
         * @var QuestionHelper $helper
         */
        $helper = $this->getHelper('question');

        $clientLines = array_map(
            fn(ClientDto $dto) => $dto->castToArray(),
            $this->useCase->listClients(),
        );

        (new Table($output))
            ->setHeaders(array_keys(current($clientLines)))
            ->setRows($clientLines)
            ->render();

        $clientId = (string)$helper->ask($input, $output, new ChoiceQuestion('Введите ID модифицируемого клиента: ', array_column($clientLines, 'id')));

        $client = $this->useCase->findClient($clientId);

        $clientModified = $this->useCase->modifyClient($clientId, [
            'lastName' => $helper->ask($input, $output, new Question('Введите фамилию (' . $client->getLastName() . '): ', $client->getLastName())),
            'firstName' => $helper->ask($input, $output, new Question('Введите имя: (' . $client->getFirstName() . '): ', $client->getFirstName())),
            'birthday' => $helper->ask($input, $output, new Question('Введите дату рождения (' . $client->getBirthday() . '): ', $client->getBirthday())),
            'city' => $helper->ask($input, $output, new Question('Введите город: (' . $client->getCity() . '): ', $client->getCity())),
            'state' => $helper->ask($input, $output, new Question('Введите код штата: (' . $client->getState() . '): ', $client->getState())),
            'zip' => $helper->ask($input, $output, new Question('Введите ZIP: (' . $client->getZip() . '): ', $client->getZip())),
            'ssn' => $helper->ask($input, $output, new Question('Введите SSN: (' . $client->getSsn() . '): ', $client->getSsn())),
            'fico' => $helper->ask($input, $output, new Question('Введите FICO: (' . $client->getFico() . '): ', $client->getFico())),
            'email' => $helper->ask($input, $output, new Question('Введите емайл: (' . $client->getEmail() . '): ', $client->getEmail())),
            'phone' => $helper->ask($input, $output, new Question('Введите тел. номер: (' . $client->getPhone() . '): ', $client->getPhone())),
            'monthIncome' => $helper->ask($input, $output, new Question('Введите месячный доход: (' . $client->getMonthIncome() . '): ', $client->getMonthIncome())),
        ]);

        $output->writeln('');
        $output->writeln('Модифицирован пользователь с ID [' . $client->getId() . ']');

        $clientLines = [$clientModified->castToArray()];

        (new Table($output))
            ->setHeaders(array_keys(current($clientLines)))
            ->setRows($clientLines)
            ->render();

        return Command::SUCCESS;
    }
}