<?php

declare(strict_types=1);

namespace App\Migration;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Types\Types;
use Doctrine\Migrations\AbstractMigration;

final class Version20241012214313 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'create table product';
    }

    public function up(Schema $schema): void
    {
        $table = $schema->createTable('product');

        $table->addColumn('id', Types::GUID);
        $table->addColumn('name', Types::STRING);
        $table->addColumn('term', Types::SMALLINT);
        $table->addColumn('interestRate', Types::SMALLFLOAT);
        $table->addColumn('amount', Types::INTEGER);

        $table->setPrimaryKey(['id']);
        $table->addUniqueConstraint(['name']);
    }

    public function down(Schema $schema): void
    {
        $schema->dropTable('product');
    }
}
