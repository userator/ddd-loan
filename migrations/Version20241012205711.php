<?php

declare(strict_types=1);

namespace App\Migration;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Types\Types;
use Doctrine\Migrations\AbstractMigration;

final class Version20241012205711 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'create table client';
    }

    public function up(Schema $schema): void
    {
        $table = $schema->createTable('client');

        $table->addColumn('id', Types::GUID);
        $table->addColumn('firstName', Types::STRING);
        $table->addColumn('lastName', Types::STRING);
        $table->addColumn('birthday', Types::DATE_IMMUTABLE);
        $table->addColumn('city', Types::STRING);
        $table->addColumn('state', Types::STRING);
        $table->addColumn('zip', Types::STRING);
        $table->addColumn('ssn', Types::STRING);
        $table->addColumn('fico', Types::SMALLINT);
        $table->addColumn('email', Types::STRING);
        $table->addColumn('phone', Types::STRING);
        $table->addColumn('monthIncome', Types::INTEGER);

        $table->setPrimaryKey(['id']);
        $table->addUniqueConstraint(['ssn']);
    }

    public function down(Schema $schema): void
    {
        $schema->dropTable('client');
    }
}
