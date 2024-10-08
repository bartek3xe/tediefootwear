<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20241008140526 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add tables for translations, refactor database structure';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE product_category_translation (id INT AUTO_INCREMENT NOT NULL, category_id INT DEFAULT NULL, language VARCHAR(2) NOT NULL, name VARCHAR(255) DEFAULT NULL, INDEX IDX_1DAAB48712469DE2 (category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_translation (id INT AUTO_INCREMENT NOT NULL, product_id INT DEFAULT NULL, language VARCHAR(2) NOT NULL, name VARCHAR(255) DEFAULT NULL, description LONGTEXT DEFAULT NULL, INDEX IDX_1846DB704584665A (product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE product_category_translation ADD CONSTRAINT FK_1DAAB48712469DE2 FOREIGN KEY (category_id) REFERENCES product_category (id)');
        $this->addSql('ALTER TABLE product_translation ADD CONSTRAINT FK_1846DB704584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE product DROP name, DROP description');
        $this->addSql('ALTER TABLE product_category DROP name');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE product_category_translation DROP FOREIGN KEY FK_1DAAB48712469DE2');
        $this->addSql('ALTER TABLE product_translation DROP FOREIGN KEY FK_1846DB704584665A');
        $this->addSql('DROP TABLE product_category_translation');
        $this->addSql('DROP TABLE product_translation');
        $this->addSql('ALTER TABLE product_category ADD name JSON NOT NULL');
        $this->addSql('ALTER TABLE product ADD name JSON NOT NULL, ADD description JSON NOT NULL');
    }
}
