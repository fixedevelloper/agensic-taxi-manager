<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221119064305 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE shipping (id INT AUTO_INCREMENT NOT NULL, address_id INT DEFAULT NULL, place_id INT DEFAULT NULL, status VARCHAR(255) NOT NULL, distance INT NOT NULL, date_created DATETIME DEFAULT NULL, date_modified DATETIME DEFAULT NULL, INDEX IDX_2D1C1724F5B7AF75 (address_id), INDEX IDX_2D1C1724DA6A219 (place_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE shipping ADD CONSTRAINT FK_2D1C1724F5B7AF75 FOREIGN KEY (address_id) REFERENCES address_shipping (id)');
        $this->addSql('ALTER TABLE shipping ADD CONSTRAINT FK_2D1C1724DA6A219 FOREIGN KEY (place_id) REFERENCES place (id)');
        $this->addSql('ALTER TABLE address_shipping ADD address VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE ride ADD distance VARCHAR(255) DEFAULT NULL, ADD estimate_time INT DEFAULT NULL, ADD longitudestart VARCHAR(255) DEFAULT NULL, ADD latitudestart VARCHAR(255) DEFAULT NULL, ADD longitudestop VARCHAR(255) DEFAULT NULL, ADD latitudestop VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE shipping DROP FOREIGN KEY FK_2D1C1724F5B7AF75');
        $this->addSql('ALTER TABLE shipping DROP FOREIGN KEY FK_2D1C1724DA6A219');
        $this->addSql('DROP TABLE shipping');
        $this->addSql('ALTER TABLE address_shipping DROP address');
        $this->addSql('ALTER TABLE ride DROP distance, DROP estimate_time, DROP longitudestart, DROP latitudestart, DROP longitudestop, DROP latitudestop');
    }
}
