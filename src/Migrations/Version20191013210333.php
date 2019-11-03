<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191013210333 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE Sizes');
        $this->addSql('ALTER TABLE order_2983 ADD shipment_option_id INT NOT NULL, ADD address_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE order_2983 ADD CONSTRAINT FK_B562D6D39DD71B5E FOREIGN KEY (shipment_option_id) REFERENCES shipment_option (id)');
        $this->addSql('ALTER TABLE order_2983 ADD CONSTRAINT FK_B562D6D3F5B7AF75 FOREIGN KEY (address_id) REFERENCES address (id)');
        $this->addSql('CREATE INDEX IDX_B562D6D39DD71B5E ON order_2983 (shipment_option_id)');
        $this->addSql('CREATE INDEX IDX_B562D6D3F5B7AF75 ON order_2983 (address_id)');
        $this->addSql('ALTER TABLE user DROP path_slug');
        $this->addSql('ALTER TABLE store CHANGE admin_id admin_id INT NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE Sizes (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) NOT NULL COLLATE utf8mb4_unicode_ci, note VARCHAR(200) DEFAULT NULL COLLATE utf8mb4_unicode_ci, UNIQUE INDEX Sizes_Name_U (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE order_2983 DROP FOREIGN KEY FK_B562D6D39DD71B5E');
        $this->addSql('ALTER TABLE order_2983 DROP FOREIGN KEY FK_B562D6D3F5B7AF75');
        $this->addSql('DROP INDEX IDX_B562D6D39DD71B5E ON order_2983');
        $this->addSql('DROP INDEX IDX_B562D6D3F5B7AF75 ON order_2983');
        $this->addSql('ALTER TABLE order_2983 DROP shipment_option_id, DROP address_id');
        $this->addSql('ALTER TABLE store CHANGE admin_id admin_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD path_slug VARCHAR(50) NOT NULL COLLATE utf8mb4_unicode_ci');
    }
}
