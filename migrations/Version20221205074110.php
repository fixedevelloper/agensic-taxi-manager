<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221205074110 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE article ADD image_id INT DEFAULT NULL, DROP image');
        $this->addSql('ALTER TABLE article ADD CONSTRAINT FK_23A0E663DA5256D FOREIGN KEY (image_id) REFERENCES image (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_23A0E663DA5256D ON article (image_id)');
        $this->addSql('ALTER TABLE category ADD image_id INT DEFAULT NULL, DROP image');
        $this->addSql('ALTER TABLE category ADD CONSTRAINT FK_64C19C13DA5256D FOREIGN KEY (image_id) REFERENCES image (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_64C19C13DA5256D ON category (image_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE article DROP FOREIGN KEY FK_23A0E663DA5256D');
        $this->addSql('DROP INDEX UNIQ_23A0E663DA5256D ON article');
        $this->addSql('ALTER TABLE article ADD image VARCHAR(255) DEFAULT NULL, DROP image_id');
        $this->addSql('ALTER TABLE category DROP FOREIGN KEY FK_64C19C13DA5256D');
        $this->addSql('DROP INDEX UNIQ_64C19C13DA5256D ON category');
        $this->addSql('ALTER TABLE category ADD image VARCHAR(255) DEFAULT NULL, DROP image_id');
    }
}
