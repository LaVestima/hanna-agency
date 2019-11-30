<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191114220919 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE conversation DROP FOREIGN KEY FK_8A8E26E920C3C701');
        $this->addSql('ALTER TABLE conversation DROP FOREIGN KEY FK_8A8E26E9D2F7B13D');
        $this->addSql('DROP INDEX IDX_8A8E26E9D2F7B13D ON conversation');
        $this->addSql('DROP INDEX IDX_8A8E26E920C3C701 ON conversation');
        $this->addSql('ALTER TABLE conversation DROP user_from_id, DROP user_to_id');
        $this->addSql('ALTER TABLE message ADD read_at DATETIME DEFAULT NULL, DROP is_read, DROP is_from_initiator, CHANGE user_id user_id INT NOT NULL');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307FA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE message RENAME INDEX user_id TO IDX_B6BD307FA76ED395');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE conversation ADD user_from_id INT NOT NULL, ADD user_to_id INT NOT NULL');
        $this->addSql('ALTER TABLE conversation ADD CONSTRAINT FK_8A8E26E920C3C701 FOREIGN KEY (user_from_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE conversation ADD CONSTRAINT FK_8A8E26E9D2F7B13D FOREIGN KEY (user_to_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_8A8E26E9D2F7B13D ON conversation (user_to_id)');
        $this->addSql('CREATE INDEX IDX_8A8E26E920C3C701 ON conversation (user_from_id)');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307FA76ED395');
        $this->addSql('ALTER TABLE message ADD is_read TINYINT(1) NOT NULL, ADD is_from_initiator TINYINT(1) NOT NULL, DROP read_at, CHANGE user_id user_id BIGINT NOT NULL');
        $this->addSql('ALTER TABLE message RENAME INDEX idx_b6bd307fa76ed395 TO user_id');
    }
}
