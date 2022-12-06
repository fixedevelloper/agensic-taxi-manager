<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221206050427 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE article ADD type VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE place ADD image_id INT DEFAULT NULL, ADD rating INT DEFAULT NULL');
        $this->addSql('ALTER TABLE place ADD CONSTRAINT FK_741D53CD3DA5256D FOREIGN KEY (image_id) REFERENCES image (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_741D53CD3DA5256D ON place (image_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE article DROP type');
        $this->addSql('ALTER TABLE place DROP FOREIGN KEY FK_741D53CD3DA5256D');
        $this->addSql('DROP INDEX UNIQ_741D53CD3DA5256D ON place');
        $this->addSql('ALTER TABLE place DROP image_id, DROP rating');
    }
}
