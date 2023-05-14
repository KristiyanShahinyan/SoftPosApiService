<?php

declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20201016142616 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Rename the table to have more descriptive name.';
    }

    public function up(Schema $schema) : void
    {
        $this->addSql('ALTER TABLE update RENAME TO app_update');
        $this->addSql('ALTER SEQUENCE update_id_seq RENAME TO app_update_id_seq');
    }

    public function down(Schema $schema) : void
    {
        $this->addSql('ALTER TABLE app_update RENAME TO update');
        $this->addSql('ALTER SEQUENCE app_update_id_seq RENAME TO update_id_seq');
    }
}
