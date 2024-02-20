<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240219150707 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE `member` (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, age DATE NOT NULL COMMENT \'(DC2Type:date_immutable)\', INDEX IDX_70E4FA78A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE `member` ADD CONSTRAINT FK_70E4FA78A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE article ADD memb_id INT NOT NULL');
        $this->addSql('ALTER TABLE article ADD CONSTRAINT FK_23A0E6646B9FCE4 FOREIGN KEY (memb_id) REFERENCES `member` (id)');
        $this->addSql('CREATE INDEX IDX_23A0E6646B9FCE4 ON article (memb_id)');
        $this->addSql('ALTER TABLE slot ADD memb_id INT NOT NULL');
        $this->addSql('ALTER TABLE slot ADD CONSTRAINT FK_AC0E206746B9FCE4 FOREIGN KEY (memb_id) REFERENCES `member` (id)');
        $this->addSql('CREATE INDEX IDX_AC0E206746B9FCE4 ON slot (memb_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE article DROP FOREIGN KEY FK_23A0E6646B9FCE4');
        $this->addSql('ALTER TABLE slot DROP FOREIGN KEY FK_AC0E206746B9FCE4');
        $this->addSql('ALTER TABLE `member` DROP FOREIGN KEY FK_70E4FA78A76ED395');
        $this->addSql('DROP TABLE `member`');
        $this->addSql('DROP INDEX IDX_AC0E206746B9FCE4 ON slot');
        $this->addSql('ALTER TABLE slot DROP memb_id');
        $this->addSql('DROP INDEX IDX_23A0E6646B9FCE4 ON article');
        $this->addSql('ALTER TABLE article DROP memb_id');
    }
}
