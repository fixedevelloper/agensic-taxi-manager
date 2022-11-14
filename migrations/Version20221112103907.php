<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221112103907 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE affectation_ride (id INT AUTO_INCREMENT NOT NULL, ride_id INT DEFAULT NULL, driver_id INT DEFAULT NULL, is_enable TINYINT(1) NOT NULL, expired_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', date_created DATETIME DEFAULT NULL, date_modified DATETIME DEFAULT NULL, INDEX IDX_7E561BFE302A8A70 (ride_id), INDEX IDX_7E561BFEC3423909 (driver_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE car (id INT AUTO_INCREMENT NOT NULL, propretaire_id INT DEFAULT NULL, model VARCHAR(255) DEFAULT NULL, registration_number VARCHAR(255) DEFAULT NULL, marque VARCHAR(255) DEFAULT NULL, variant VARCHAR(255) DEFAULT NULL, rate INT DEFAULT NULL, baseprice DOUBLE PRECISION DEFAULT NULL, date_created DATETIME DEFAULT NULL, date_modified DATETIME DEFAULT NULL, INDEX IDX_773DE69D9D1A1D9C (propretaire_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE customer (id INT AUTO_INCREMENT NOT NULL, compte_id INT DEFAULT NULL, total_ride INT DEFAULT NULL, UNIQUE INDEX UNIQ_81398E09F2C56620 (compte_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE driver (id INT AUTO_INCREMENT NOT NULL, status TINYINT(1) NOT NULL, licence VARCHAR(255) DEFAULT NULL, date_created DATETIME DEFAULT NULL, date_modified DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE proprietaire (id INT AUTO_INCREMENT NOT NULL, compte_id INT DEFAULT NULL, date_created DATETIME DEFAULT NULL, date_modified DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_69E399D6F2C56620 (compte_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ride (id INT AUTO_INCREMENT NOT NULL, driver_id INT DEFAULT NULL, customer_id INT DEFAULT NULL, car_id INT DEFAULT NULL, pikupbegin DATETIME DEFAULT NULL, pickupend DATETIME DEFAULT NULL, startto VARCHAR(255) DEFAULT NULL, endto VARCHAR(255) DEFAULT NULL, amount DOUBLE PRECISION DEFAULT NULL, status VARCHAR(255) NOT NULL, date_created DATETIME DEFAULT NULL, date_modified DATETIME DEFAULT NULL, INDEX IDX_9B3D7CD0C3423909 (driver_id), INDEX IDX_9B3D7CD09395C3F3 (customer_id), INDEX IDX_9B3D7CD0C3C6F69F (car_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(255) DEFAULT NULL, email VARCHAR(255) DEFAULT NULL, name VARCHAR(255) DEFAULT NULL, phone VARCHAR(255) DEFAULT NULL, roles JSON DEFAULT NULL, password VARCHAR(255) DEFAULT NULL, isactivate TINYINT(1) DEFAULT NULL, avatar VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE wallet (id INT AUTO_INCREMENT NOT NULL, beneficiare_id INT DEFAULT NULL, amount DOUBLE PRECISION NOT NULL, walletnumber VARCHAR(255) NOT NULL, date_created DATETIME DEFAULT NULL, date_modified DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_7C68921FF6054787 (beneficiare_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE affectation_ride ADD CONSTRAINT FK_7E561BFE302A8A70 FOREIGN KEY (ride_id) REFERENCES ride (id)');
        $this->addSql('ALTER TABLE affectation_ride ADD CONSTRAINT FK_7E561BFEC3423909 FOREIGN KEY (driver_id) REFERENCES driver (id)');
        $this->addSql('ALTER TABLE car ADD CONSTRAINT FK_773DE69D9D1A1D9C FOREIGN KEY (propretaire_id) REFERENCES proprietaire (id)');
        $this->addSql('ALTER TABLE customer ADD CONSTRAINT FK_81398E09F2C56620 FOREIGN KEY (compte_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE proprietaire ADD CONSTRAINT FK_69E399D6F2C56620 FOREIGN KEY (compte_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE ride ADD CONSTRAINT FK_9B3D7CD0C3423909 FOREIGN KEY (driver_id) REFERENCES driver (id)');
        $this->addSql('ALTER TABLE ride ADD CONSTRAINT FK_9B3D7CD09395C3F3 FOREIGN KEY (customer_id) REFERENCES customer (id)');
        $this->addSql('ALTER TABLE ride ADD CONSTRAINT FK_9B3D7CD0C3C6F69F FOREIGN KEY (car_id) REFERENCES car (id)');
        $this->addSql('ALTER TABLE wallet ADD CONSTRAINT FK_7C68921FF6054787 FOREIGN KEY (beneficiare_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE affectation_ride DROP FOREIGN KEY FK_7E561BFE302A8A70');
        $this->addSql('ALTER TABLE affectation_ride DROP FOREIGN KEY FK_7E561BFEC3423909');
        $this->addSql('ALTER TABLE car DROP FOREIGN KEY FK_773DE69D9D1A1D9C');
        $this->addSql('ALTER TABLE customer DROP FOREIGN KEY FK_81398E09F2C56620');
        $this->addSql('ALTER TABLE proprietaire DROP FOREIGN KEY FK_69E399D6F2C56620');
        $this->addSql('ALTER TABLE ride DROP FOREIGN KEY FK_9B3D7CD0C3423909');
        $this->addSql('ALTER TABLE ride DROP FOREIGN KEY FK_9B3D7CD09395C3F3');
        $this->addSql('ALTER TABLE ride DROP FOREIGN KEY FK_9B3D7CD0C3C6F69F');
        $this->addSql('ALTER TABLE wallet DROP FOREIGN KEY FK_7C68921FF6054787');
        $this->addSql('DROP TABLE affectation_ride');
        $this->addSql('DROP TABLE car');
        $this->addSql('DROP TABLE customer');
        $this->addSql('DROP TABLE driver');
        $this->addSql('DROP TABLE proprietaire');
        $this->addSql('DROP TABLE ride');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE wallet');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
