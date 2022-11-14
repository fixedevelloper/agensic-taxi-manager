<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221112121057 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE image (id INT AUTO_INCREMENT NOT NULL, car_id INT DEFAULT NULL, src VARCHAR(255) DEFAULT NULL, name VARCHAR(255) DEFAULT NULL, alt VARCHAR(255) DEFAULT NULL, position INT DEFAULT NULL, date_created DATETIME DEFAULT NULL, date_modified DATETIME DEFAULT NULL, INDEX IDX_C53D045FC3C6F69F (car_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE image ADD CONSTRAINT FK_C53D045FC3C6F69F FOREIGN KEY (car_id) REFERENCES car (id)');
        $this->addSql('ALTER TABLE affectation_ride DROP FOREIGN KEY FK_7E561BFE302A8A70');
        $this->addSql('DROP INDEX IDX_7E561BFE302A8A70 ON affectation_ride');
        $this->addSql('ALTER TABLE affectation_ride CHANGE ride_id car_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE affectation_ride ADD CONSTRAINT FK_7E561BFEC3C6F69F FOREIGN KEY (car_id) REFERENCES car (id)');
        $this->addSql('CREATE INDEX IDX_7E561BFEC3C6F69F ON affectation_ride (car_id)');
        $this->addSql('ALTER TABLE driver ADD compte_id INT DEFAULT NULL, ADD image_id INT DEFAULT NULL, ADD cni VARCHAR(255) DEFAULT NULL, ADD permitdriver VARCHAR(255) NOT NULL, ADD expirated_permit DATE DEFAULT NULL');
        $this->addSql('ALTER TABLE driver ADD CONSTRAINT FK_11667CD9F2C56620 FOREIGN KEY (compte_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE driver ADD CONSTRAINT FK_11667CD93DA5256D FOREIGN KEY (image_id) REFERENCES image (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_11667CD9F2C56620 ON driver (compte_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_11667CD93DA5256D ON driver (image_id)');
        $this->addSql('ALTER TABLE proprietaire ADD cni VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE driver DROP FOREIGN KEY FK_11667CD93DA5256D');
        $this->addSql('ALTER TABLE image DROP FOREIGN KEY FK_C53D045FC3C6F69F');
        $this->addSql('DROP TABLE image');
        $this->addSql('ALTER TABLE driver DROP FOREIGN KEY FK_11667CD9F2C56620');
        $this->addSql('DROP INDEX UNIQ_11667CD9F2C56620 ON driver');
        $this->addSql('DROP INDEX UNIQ_11667CD93DA5256D ON driver');
        $this->addSql('ALTER TABLE driver DROP compte_id, DROP image_id, DROP cni, DROP permitdriver, DROP expirated_permit');
        $this->addSql('ALTER TABLE proprietaire DROP cni');
        $this->addSql('ALTER TABLE affectation_ride DROP FOREIGN KEY FK_7E561BFEC3C6F69F');
        $this->addSql('DROP INDEX IDX_7E561BFEC3C6F69F ON affectation_ride');
        $this->addSql('ALTER TABLE affectation_ride CHANGE car_id ride_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE affectation_ride ADD CONSTRAINT FK_7E561BFE302A8A70 FOREIGN KEY (ride_id) REFERENCES ride (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_7E561BFE302A8A70 ON affectation_ride (ride_id)');
    }
}
