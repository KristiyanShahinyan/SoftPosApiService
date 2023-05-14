<?php

declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20201201151601 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Create table to store the generated random values';
    }

    public function up(Schema $schema) : void
    {
        $sql = <<<SQL
CREATE TABLE random (
id SERIAL NOT NULL,
base_64_encoded_random VARCHAR(64) NOT NULL,
expires_on TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL,
created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL,
updated_on TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL,
is_deleted BOOLEAN DEFAULT 'false',
PRIMARY KEY(id)
);
SQL;

        $this->addSql($sql);
    }

    public function down(Schema $schema) : void
    {
        $this->addSql('DROP TABLE random');
    }
}
