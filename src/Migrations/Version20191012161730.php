<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191012161730 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE order_product_variant DROP FOREIGN KEY FK_EB9367416BF700BD');
        $this->addSql('DROP TABLE order_status');
        $this->addSql('ALTER TABLE store CHANGE owner_id admin_id integer;');
        $this->addSql('ALTER TABLE store RENAME INDEX store_owner_fk TO Store_Admin_FK');
        $this->addSql('DROP INDEX Orders_Products_ID_STATUSES_FK ON order_product_variant');
        $this->addSql('ALTER TABLE order_product_variant ADD status VARCHAR(64) NOT NULL, DROP status_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE order_status (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) NOT NULL COLLATE utf8mb4_unicode_ci, UNIQUE INDEX Orders_Statuses_Name_U (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE order_product_variant ADD status_id INT NOT NULL, DROP status');
        $this->addSql('ALTER TABLE order_product_variant ADD CONSTRAINT FK_EB9367416BF700BD FOREIGN KEY (status_id) REFERENCES order_status (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX Orders_Products_ID_STATUSES_FK ON order_product_variant (status_id)');
        $this->addSql('ALTER TABLE store RENAME INDEX store_admin_fk TO Store_Owner_FK');
    }
}
