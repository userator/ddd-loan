<?php

namespace App\Presentation\CLI;

use App\Application\UseCase\ClientCreate as ClientCreateUseCase;
use App\Presentation\Tool\ClientCaster;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class ClientCreate extends Command
{

    public function __construct(
        private ClientCreateUseCase $useCase,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setName('app:client-create')
            ->setDescription('Создать нового клиента')
            ->setHelp('Эта команда создаёт нового клиента.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        /**
         * @var QuestionHelper
         */
        $helper = $this->getHelper('question');

        $client = $this->useCase->createClient([
            'lastName' => $helper->ask($input, $output, new Question('Введите фамилию: ', 'Неизвестный')),
            'name' => $helper->ask($input, $output, new Question('Введите имя: ', 'Михаил')),
            'age' => $helper->ask($input, $output, new Question('Введите возраст: ', 38)),
            'addressCity' => $helper->ask($input, $output, new Question('Введите город: ', 'New York')),
            'addressState' => $helper->ask($input, $output, new Question('Введите код штата: ', 'NY')),
            'addressZip' => $helper->ask($input, $output, new Question('Введите ZIP: ', '10001')),
            'ssn' => $helper->ask($input, $output, new Question('Введите SSN: ', '333-22-4444')),
            'fico' => $helper->ask($input, $output, new Question('Введите FICO: ', 700)),
            'email' => $helper->ask($input, $output, new Question('Введите емайл: ', 'some@example.com')),
            'phone' => $helper->ask($input, $output, new Question('Введите тел. номер: ', '333-333-4444')),
            'monthIncome' => $helper->ask($input, $output, new Question('Введите месячный доход: ', 10000)),
        ]);

        $output->writeln('');
        $output->writeln('Создан пользователь с ID [' . $client->getId() . ']');

        $clientLines = ClientCaster::batchCastToArray([$client]);

        (new Table($output))
            ->setHeaders(array_keys(current($clientLines)))
            ->setRows($clientLines)
            ->render();

        return Command::SUCCESS;
    }
}