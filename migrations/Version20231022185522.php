<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231022185522 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE task CHANGE user_creat_id user_creat_id INT DEFAULT 2');
        $this->addSql('UPDATE `task` SET `user_creat_id`=2 WHERE `user_creat_id`=0');
        $this->addSql('ALTER TABLE task ADD CONSTRAINT FK_527EDB25210AB6CE FOREIGN KEY (user_creat_id) REFERENCES `user` (id)');
        $this->addSql('CREATE INDEX IDX_527EDB25210AB6CE ON task (user_creat_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE task DROP FOREIGN KEY FK_527EDB25210AB6CE');
        $this->addSql('DROP INDEX IDX_527EDB25210AB6CE ON task');
        $this->addSql('ALTER TABLE task CHANGE user_creat_id user_creat_id INT NOT NULL');
    }
}
