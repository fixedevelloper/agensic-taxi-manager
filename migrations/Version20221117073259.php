<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221117073259 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE address_shipping (id INT AUTO_INCREMENT NOT NULL, customer_id INT DEFAULT NULL, name VARCHAR(255) DEFAULT NULL, longitude VARCHAR(255) DEFAULT NULL, latitude VARCHAR(255) DEFAULT NULL, INDEX IDX_D393E9A59395C3F3 (customer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE place (id INT AUTO_INCREMENT NOT NULL, propretaire_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, phone VARCHAR(255) DEFAULT NULL, bp VARCHAR(255) DEFAULT NULL, address VARCHAR(255) DEFAULT NULL, longitude VARCHAR(255) DEFAULT NULL, latitude VARCHAR(255) DEFAULT NULL, INDEX IDX_741D53CD9D1A1D9C (propretaire_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE address_shipping ADD CONSTRAINT FK_D393E9A59395C3F3 FOREIGN KEY (customer_id) REFERENCES customer (id)');
        $this->addSql('ALTER TABLE place ADD CONSTRAINT FK_741D53CD9D1A1D9C FOREIGN KEY (propretaire_id) REFERENCES proprietaire (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE address_shipping DROP FOREIGN KEY FK_D393E9A59395C3F3');
        $this->addSql('ALTER TABLE place DROP FOREIGN KEY FK_741D53CD9D1A1D9C');
        $this->addSql('DROP TABLE address_shipping');
        $this->addSql('DROP TABLE place');
    }
}
