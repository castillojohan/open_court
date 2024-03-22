<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240322144953 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE lesson (id INT AUTO_INCREMENT NOT NULL, capacity INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE `member` CHANGE user_id user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE slot ADD lesson_id INT DEFAULT NULL, CHANGE memb_id memb_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE slot ADD CONSTRAINT FK_AC0E2067CDF80196 FOREIGN KEY (lesson_id) REFERENCES lesson (id)');
        $this->addSql('CREATE INDEX IDX_AC0E2067CDF80196 ON slot (lesson_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE slot DROP FOREIGN KEY FK_AC0E2067CDF80196');
        $this->addSql('DROP TABLE lesson');
        $this->addSql('DROP INDEX IDX_AC0E2067CDF80196 ON slot');
        $this->addSql('ALTER TABLE slot DROP lesson_id, CHANGE memb_id memb_id INT NOT NULL');
        $this->addSql('ALTER TABLE `member` CHANGE user_id user_id INT NOT NULL');
    }
}
