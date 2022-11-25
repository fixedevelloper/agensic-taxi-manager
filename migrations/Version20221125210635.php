<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221125210635 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE driver ADD callid VARCHAR(255) DEFAULT NULL, ADD lac INT DEFAULT NULL, ADD radiotype VARCHAR(255) DEFAULT NULL, ADD mobilenetworkcode VARCHAR(255) DEFAULT NULL, ADD mobilenetcode VARCHAR(255) DEFAULT NULL, ADD countrycode VARCHAR(255) DEFAULT NULL, ADD carrier VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE driver DROP callid, DROP lac, DROP radiotype, DROP mobilenetworkcode, DROP mobilenetcode, DROP countrycode, DROP carrier');
    }
}
