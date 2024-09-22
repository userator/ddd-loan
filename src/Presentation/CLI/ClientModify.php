<?php

namespace App\Presentation\CLI;

use App\Application\UseCase\ClientModify as ClientModifyUseCase;
use App\Presentation\Tool\ClientCaster;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
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
         * @var QuestionHelper
         */
        $helper = $this->getHelper('question');

        $clientId = (string)$helper->ask($input, $output, new Question('Введите ID модифицируемого клиента: '));

        $client = $this->useCase->findClient($clientId);

        $clientModified = $this->useCase->modifyClient(
            $clientId,
            [
                'lastName' => $helper->ask($input, $output, new Question('Введите фамилию: ', $client->getLastName())),
                'name' => $helper->ask($input, $output, new Question('Введите имя: ', $client->getName())),
                'age' => $helper->ask($input, $output, new Question('Введите возраст: ', $client->getAge())),
                'city' => $helper->ask($input, $output, new Question('Введите город: ', $client->getAddress()->getCity())),
                'state' => $helper->ask($input, $output, new Question('Введите код штата: ', $client->getAddress()->getState())),
                'zip' => $helper->ask($input, $output, new Question('Введите ZIP: ', $client->getAddress()->getZip())),
                'ssn' => $helper->ask($input, $output, new Question('Введите SSN: ', $client->getSsn()->getValue())),
                'fico' => $helper->ask($input, $output, new Question('Введите FICO: ', $client->getFico()->getValue())),
                'email' => $helper->ask($input, $output, new Question('Введите емайл: ', $client->getEmail()->getValue())),
                'phone' => $helper->ask($input, $output, new Question('Введите тел. номер: ', $client->getPhone()->getValue())),
                'monthIncome' => $helper->ask($input, $output, new Question('Введите месячный доход: ', $client->getMonthIncome())),
            ]
        );

        $output->writeln('');
        $output->writeln('Модифицирован пользователь с ID [' . $client->getId() . ']');

        $clientLines = ClientCaster::batchCastToArray([$clientModified]);

        (new Table($output))
            ->setHeaders(array_keys(current($clientLines)))
            ->setRows($clientLines)
            ->render();

        return Command::SUCCESS;
    }
}