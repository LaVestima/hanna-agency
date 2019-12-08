<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191201000727 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE parameter_category (id INT AUTO_INCREMENT NOT NULL, parameter_id INT NOT NULL, category_id INT NOT NULL, INDEX IDX_EB3EC45D7C56DBD6 (parameter_id), INDEX IDX_EB3EC45D12469DE2 (category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ml_model (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, created_at DATETIME NOT NULL, type VARCHAR(255) NOT NULL, content JSON NOT NULL, INDEX IDX_8EB57374A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE parameter_category ADD CONSTRAINT FK_EB3EC45D7C56DBD6 FOREIGN KEY (parameter_id) REFERENCES parameter (id)');
        $this->addSql('ALTER TABLE parameter_category ADD CONSTRAINT FK_EB3EC45D12469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE ml_model ADD CONSTRAINT FK_8EB57374A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('DROP TABLE Customers');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649D60322AC');
        $this->addSql('DROP INDEX User_Role_FK ON user');
        $this->addSql('ALTER TABLE user ADD is_active TINYINT(1) NOT NULL, DROP role_id');
        $this->addSql('CREATE UNIQUE INDEX productReview_user_product ON product_review (user_id, product_id)');
        $this->addSql('ALTER TABLE page_visit DROP FOREIGN KEY FK_25FF16EFA76ED395');
        $this->addSql('ALTER TABLE page_visit ADD CONSTRAINT FK_25FF16EFA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE message ADD user_id INT NOT NULL, ADD read_at DATETIME DEFAULT NULL, DROP is_read, DROP is_from_initiator');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307FA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_B6BD307FA76ED395 ON message (user_id)');
        $this->addSql('ALTER TABLE conversation DROP FOREIGN KEY FK_8A8E26E920C3C701');
        $this->addSql('ALTER TABLE conversation DROP FOREIGN KEY FK_8A8E26E9D2F7B13D');
        $this->addSql('DROP INDEX IDX_8A8E26E920C3C701 ON conversation');
        $this->addSql('DROP INDEX IDX_8A8E26E9D2F7B13D ON conversation');
        $this->addSql('ALTER TABLE conversation DROP user_from_id, DROP user_to_id');
        $this->addSql('ALTER TABLE parameter ADD type INT NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE Customers (country_id INT NOT NULL, city_id INT NOT NULL, ID INT AUTO_INCREMENT NOT NULL, Date_Created DATETIME NOT NULL, Date_Deleted DATETIME DEFAULT NULL, User_Created INT NOT NULL, User_Deleted INT DEFAULT NULL, Identification_Number VARCHAR(10) NOT NULL COLLATE utf8mb4_unicode_ci, First_Name VARCHAR(50) NOT NULL COLLATE utf8mb4_unicode_ci, Last_Name VARCHAR(50) NOT NULL COLLATE utf8mb4_unicode_ci, Gender VARCHAR(1) NOT NULL COLLATE utf8mb4_unicode_ci, Company_Name VARCHAR(200) DEFAULT NULL COLLATE utf8mb4_unicode_ci, VAT VARCHAR(50) DEFAULT NULL COLLATE utf8mb4_unicode_ci, Email VARCHAR(200) NOT NULL COLLATE utf8mb4_unicode_ci, Phone VARCHAR(50) NOT NULL COLLATE utf8mb4_unicode_ci, Default_Discount INT DEFAULT NULL, Path_Slug VARCHAR(50) NOT NULL COLLATE utf8mb4_unicode_ci, Postal_Code VARCHAR(20) NOT NULL COLLATE utf8mb4_unicode_ci, Street VARCHAR(200) NOT NULL COLLATE utf8mb4_unicode_ci, ID_USERS INT DEFAULT NULL, INDEX Customers_ID_USERS_FK (ID_USERS), UNIQUE INDEX Customers_Identification_Number_U (Identification_Number), UNIQUE INDEX Customers_Path_Slug_U (Path_Slug), INDEX IDX_E0A2CC828BAC62AF (city_id), INDEX IDX_E0A2CC82F92F3E70 (country_id), PRIMARY KEY(ID)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE Customers ADD CONSTRAINT FK_E0A2CC823B997DA3 FOREIGN KEY (ID_USERS) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE Customers ADD CONSTRAINT FK_E0A2CC828BAC62AF FOREIGN KEY (city_id) REFERENCES city (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE Customers ADD CONSTRAINT FK_E0A2CC82F92F3E70 FOREIGN KEY (country_id) REFERENCES country (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('DROP TABLE parameter_category');
        $this->addSql('DROP TABLE ml_model');
        $this->addSql('ALTER TABLE conversation ADD user_from_id INT NOT NULL, ADD user_to_id INT NOT NULL');
        $this->addSql('ALTER TABLE conversation ADD CONSTRAINT FK_8A8E26E920C3C701 FOREIGN KEY (user_from_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE conversation ADD CONSTRAINT FK_8A8E26E9D2F7B13D FOREIGN KEY (user_to_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_8A8E26E920C3C701 ON conversation (user_from_id)');
        $this->addSql('CREATE INDEX IDX_8A8E26E9D2F7B13D ON conversation (user_to_id)');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307FA76ED395');
        $this->addSql('DROP INDEX IDX_B6BD307FA76ED395 ON message');
        $this->addSql('ALTER TABLE message ADD is_read TINYINT(1) NOT NULL, ADD is_from_initiator TINYINT(1) NOT NULL, DROP user_id, DROP read_at');
        $this->addSql('ALTER TABLE page_visit DROP FOREIGN KEY FK_25FF16EFA76ED395');
        $this->addSql('ALTER TABLE page_visit ADD CONSTRAINT FK_25FF16EFA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE parameter DROP type');
        $this->addSql('DROP INDEX productReview_user_product ON product_review');
        $this->addSql('ALTER TABLE user ADD role_id INT DEFAULT NULL, DROP is_active');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649D60322AC FOREIGN KEY (role_id) REFERENCES role (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX User_Role_FK ON user (role_id)');
    }
}
