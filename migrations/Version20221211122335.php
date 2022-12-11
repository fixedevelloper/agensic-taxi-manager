<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221211122335 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE driver ADD isdriver TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE shipping ADD driver_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE shipping ADD CONSTRAINT FK_2D1C1724C3423909 FOREIGN KEY (driver_id) REFERENCES driver (id)');
        $this->addSql('CREATE INDEX IDX_2D1C1724C3423909 ON shipping (driver_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE driver DROP isdriver');
        $this->addSql('ALTER TABLE shipping DROP FOREIGN KEY FK_2D1C1724C3423909');
        $this->addSql('DROP INDEX IDX_2D1C1724C3423909 ON shipping');
        $this->addSql('ALTER TABLE shipping DROP driver_id');
    }
}
