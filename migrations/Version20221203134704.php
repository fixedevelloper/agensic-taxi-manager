<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221203134704 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE article (id INT AUTO_INCREMENT NOT NULL, category_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, price DOUBLE PRECISION NOT NULL, image VARCHAR(255) DEFAULT NULL, status VARCHAR(255) NOT NULL, INDEX IDX_23A0E6612469DE2 (category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, place_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, image VARCHAR(255) DEFAULT NULL, INDEX IDX_64C19C1DA6A219 (place_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE article ADD CONSTRAINT FK_23A0E6612469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE category ADD CONSTRAINT FK_64C19C1DA6A219 FOREIGN KEY (place_id) REFERENCES place (id)');
        $this->addSql('ALTER TABLE shipping DROP FOREIGN KEY FK_2D1C1724F5B7AF75');
        $this->addSql('DROP INDEX IDX_2D1C1724F5B7AF75 ON shipping');
        $this->addSql('ALTER TABLE shipping ADD customer_id INT DEFAULT NULL, ADD name VARCHAR(255) DEFAULT NULL, ADD total DOUBLE PRECISION DEFAULT NULL, ADD priceshipping DOUBLE PRECISION DEFAULT NULL, ADD longitude_start DOUBLE PRECISION DEFAULT NULL, ADD lng_start DOUBLE PRECISION DEFAULT NULL, ADD lng_end DOUBLE PRECISION DEFAULT NULL, ADD lat_start DOUBLE PRECISION DEFAULT NULL, ADD lat_end DOUBLE PRECISION DEFAULT NULL, CHANGE address_id article_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE shipping ADD CONSTRAINT FK_2D1C17247294869C FOREIGN KEY (article_id) REFERENCES article (id)');
        $this->addSql('ALTER TABLE shipping ADD CONSTRAINT FK_2D1C17249395C3F3 FOREIGN KEY (customer_id) REFERENCES customer (id)');
        $this->addSql('CREATE INDEX IDX_2D1C17247294869C ON shipping (article_id)');
        $this->addSql('CREATE INDEX IDX_2D1C17249395C3F3 ON shipping (customer_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE shipping DROP FOREIGN KEY FK_2D1C17247294869C');
        $this->addSql('ALTER TABLE article DROP FOREIGN KEY FK_23A0E6612469DE2');
        $this->addSql('ALTER TABLE category DROP FOREIGN KEY FK_64C19C1DA6A219');
        $this->addSql('DROP TABLE article');
        $this->addSql('DROP TABLE category');
        $this->addSql('ALTER TABLE shipping DROP FOREIGN KEY FK_2D1C17249395C3F3');
        $this->addSql('DROP INDEX IDX_2D1C17247294869C ON shipping');
        $this->addSql('DROP INDEX IDX_2D1C17249395C3F3 ON shipping');
        $this->addSql('ALTER TABLE shipping ADD address_id INT DEFAULT NULL, DROP article_id, DROP customer_id, DROP name, DROP total, DROP priceshipping, DROP longitude_start, DROP lng_start, DROP lng_end, DROP lat_start, DROP lat_end');
        $this->addSql('ALTER TABLE shipping ADD CONSTRAINT FK_2D1C1724F5B7AF75 FOREIGN KEY (address_id) REFERENCES address_shipping (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_2D1C1724F5B7AF75 ON shipping (address_id)');
    }
}
