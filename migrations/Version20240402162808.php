<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240402162808 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE lesson_member (lesson_id INT NOT NULL, member_id INT NOT NULL, INDEX IDX_3720A2FCDF80196 (lesson_id), INDEX IDX_3720A2F7597D3FE (member_id), PRIMARY KEY(lesson_id, member_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE lesson_member ADD CONSTRAINT FK_3720A2FCDF80196 FOREIGN KEY (lesson_id) REFERENCES lesson (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE lesson_member ADD CONSTRAINT FK_3720A2F7597D3FE FOREIGN KEY (member_id) REFERENCES `member` (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE lesson_member DROP FOREIGN KEY FK_3720A2FCDF80196');
        $this->addSql('ALTER TABLE lesson_member DROP FOREIGN KEY FK_3720A2F7597D3FE');
        $this->addSql('DROP TABLE lesson_member');
    }
}
