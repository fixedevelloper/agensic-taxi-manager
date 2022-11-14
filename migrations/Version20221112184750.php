<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221112184750 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE gps_device (id INT AUTO_INCREMENT NOT NULL, sim_number VARCHAR(255) NOT NULL, emei VARCHAR(255) NOT NULL, last_longitude VARCHAR(255) NOT NULL, last_latitude VARCHAR(255) NOT NULL, last_speed VARCHAR(255) NOT NULL, last_altitude VARCHAR(255) NOT NULL, last_status VARCHAR(255) NOT NULL, last_track_time VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE car ADD gpsdevice_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE car ADD CONSTRAINT FK_773DE69DB6EEFCFA FOREIGN KEY (gpsdevice_id) REFERENCES gps_device (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_773DE69DB6EEFCFA ON car (gpsdevice_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE car DROP FOREIGN KEY FK_773DE69DB6EEFCFA');
        $this->addSql('DROP TABLE gps_device');
        $this->addSql('DROP INDEX UNIQ_773DE69DB6EEFCFA ON car');
        $this->addSql('ALTER TABLE car DROP gpsdevice_id');
    }
}
