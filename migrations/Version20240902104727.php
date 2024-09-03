<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240902104727 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add column for etsy link';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE product ADD etsy_url VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE product DROP etsy_url');
    }
}
