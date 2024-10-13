<?php

declare(strict_types=1);

namespace App\Migration;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Types\Types;
use Doctrine\Migrations\AbstractMigration;

final class Version20241012214741 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'create table loan';
    }

    public function up(Schema $schema): void
    {
        $table = $schema->createTable('loan');

        $table->addColumn('id', Types::GUID);
        $table->addColumn('clientId', Types::GUID);
        $table->addColumn('productId', Types::GUID);
        $table->addColumn('issuedAt', Types::DATETIME_IMMUTABLE);

        $table->setPrimaryKey(['id']);
    }

    public function down(Schema $schema): void
    {
        $schema->dropTable('loan');
    }
}
