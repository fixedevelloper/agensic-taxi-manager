<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221209022651 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE line_shipping (id INT AUTO_INCREMENT NOT NULL, article_id INT DEFAULT NULL, shipping_id INT DEFAULT NULL, quantity INT NOT NULL, amount DOUBLE PRECISION DEFAULT NULL, INDEX IDX_FE7F475E7294869C (article_id), INDEX IDX_FE7F475E4887F3F8 (shipping_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE line_shipping ADD CONSTRAINT FK_FE7F475E7294869C FOREIGN KEY (article_id) REFERENCES article (id)');
        $this->addSql('ALTER TABLE line_shipping ADD CONSTRAINT FK_FE7F475E4887F3F8 FOREIGN KEY (shipping_id) REFERENCES shipping (id)');
        $this->addSql('ALTER TABLE shipping ADD address VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE line_shipping DROP FOREIGN KEY FK_FE7F475E7294869C');
        $this->addSql('ALTER TABLE line_shipping DROP FOREIGN KEY FK_FE7F475E4887F3F8');
        $this->addSql('DROP TABLE line_shipping');
        $this->addSql('ALTER TABLE shipping DROP address');
    }
}
