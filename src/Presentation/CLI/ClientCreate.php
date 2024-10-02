<?php

namespace App\Presentation\CLI;

use App\Application\UseCase\ClientCreate as ClientCreateUseCase;
use App\Presentation\Tool\ClientCaster;
use Faker\Factory;
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
         * @var QuestionHelper $helper
         */
        $helper = $this->getHelper('question');
        $faker = Factory::create();

        $client = $this->useCase->createClient([
            'lastName' => $helper->ask($input, $output, new Question('Введите фамилию: ', $faker->lastName())),
            'name' => $helper->ask($input, $output, new Question('Введите имя: ', $faker->firstName())),
            'age' => $helper->ask($input, $output, new Question('Введите возраст: ', $faker->numberBetween(18, 99))),
            'city' => $helper->ask($input, $output, new Question('Введите город: ', $faker->city())),
            'state' => $helper->ask($input, $output, new Question('Введите код штата (##): ', $faker->randomElement(['CA', 'NY', 'NV', 'WA']))),
            'zip' => $helper->ask($input, $output, new Question('Введите ZIP (#####): ', $faker->numberBetween(10000, 99999))),
            'ssn' => $helper->ask($input, $output, new Question('Введите SSN (###-##-####): ', $faker->numerify('###-##-####'))),
            'fico' => $helper->ask($input, $output, new Question('Введите FICO (от 300 до 850): ', $faker->numberBetween(300, 850))),
            'email' => $helper->ask($input, $output, new Question('Введите емайл: ', $faker->email())),
            'phone' => $helper->ask($input, $output, new Question('Введите тел. номер (###-###-####): ', $faker->numerify('###-###-####'))),
            'monthIncome' => $helper->ask($input, $output, new Question('Введите месячный доход: ', $faker->numberBetween(100, 9999))),
        ]);

        $output->writeln('');
        $output->writeln('Создан пользователь с ID [' . $client->getId()->getValue() . ']');

        $clientLines = ClientCaster::batchCastToArray([$client]);

        (new Table($output))
            ->setHeaders(array_keys(current($clientLines)))
            ->setRows($clientLines)
            ->render();

        return Command::SUCCESS;
    }
}
