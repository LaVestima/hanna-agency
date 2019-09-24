<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190916214406 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE store (id INT AUTO_INCREMENT NOT NULL, city_id INT NOT NULL, country_id INT NOT NULL, owner_id INT NOT NULL, date_created DATETIME NOT NULL, date_deleted DATETIME DEFAULT NULL, short_name VARCHAR(50) NOT NULL, full_name VARCHAR(200) NOT NULL, first_name VARCHAR(50) DEFAULT NULL, last_name VARCHAR(50) DEFAULT NULL, vat VARCHAR(50) DEFAULT NULL, postal_code VARCHAR(20) NOT NULL, street VARCHAR(200) NOT NULL, email VARCHAR(200) NOT NULL, phone VARCHAR(50) NOT NULL, identifier VARCHAR(50) NOT NULL, frontpage_html LONGTEXT DEFAULT NULL, active TINYINT(1) NOT NULL, INDEX Store_Country_FK (country_id), INDEX Store_City_FK (city_id), INDEX Store_Owner_FK (owner_id), UNIQUE INDEX Store_Path_Slug_U (identifier), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE order_product_variant (id INT AUTO_INCREMENT NOT NULL, order_id INT NOT NULL, product_variant_id INT NOT NULL, status_id INT NOT NULL, quantity INT NOT NULL, discount INT NOT NULL, note VARCHAR(200) DEFAULT NULL, INDEX Orders_Products_ID_ORDERS_FK (order_id), INDEX Orders_Products_ID_PRODUCTS_SIZES_FK (product_variant_id), INDEX Orders_Products_ID_STATUSES_FK (status_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE order_2983 (id INT AUTO_INCREMENT NOT NULL, user_created INT DEFAULT NULL, user_deleted INT DEFAULT NULL, user_id INT NOT NULL, date_created DATETIME NOT NULL, date_deleted DATETIME DEFAULT NULL, path_slug VARCHAR(50) NOT NULL, code VARCHAR(24) NOT NULL, INDEX Orders_User_Created_FK (user_created), INDEX Orders_User_Deleted_FK (user_deleted), INDEX Orders_User_FK (user_id), UNIQUE INDEX Orders_Path_Slug_U (path_slug), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_variant (id INT AUTO_INCREMENT NOT NULL, product_id INT NOT NULL, variant_id INT NOT NULL, identifier VARCHAR(50) NOT NULL, availability INT NOT NULL, INDEX IDX_209AA41D4584665A (product_id), INDEX IDX_209AA41D3B69A9AF (variant_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE order_status (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) NOT NULL, UNIQUE INDEX Orders_Statuses_Name_U (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, role_id INT DEFAULT NULL, date_created DATETIME NOT NULL, date_deleted DATETIME DEFAULT NULL, login VARCHAR(50) NOT NULL, email VARCHAR(100) NOT NULL, password_hash VARCHAR(200) NOT NULL, path_slug VARCHAR(50) NOT NULL, roles JSON NOT NULL, identifier VARCHAR(50) NOT NULL, first_name VARCHAR(255) DEFAULT NULL, last_name VARCHAR(255) DEFAULT NULL, INDEX User_Role_FK (role_id), UNIQUE INDEX User_Login_U (login), UNIQUE INDEX User_Email_U (email), UNIQUE INDEX User_Password_Hash_U (password_hash), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, parent_id INT DEFAULT NULL, name VARCHAR(50) NOT NULL, note VARCHAR(200) DEFAULT NULL, identifier LONGTEXT NOT NULL, icon LONGTEXT DEFAULT NULL, INDEX IDX_64C19C1727ACA70 (parent_id), UNIQUE INDEX Categories_Name_U (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product (id INT AUTO_INCREMENT NOT NULL, category_id INT NOT NULL, store_id INT NOT NULL, date_created DATETIME NOT NULL, date_deleted DATETIME DEFAULT NULL, user_created INT NOT NULL, user_deleted INT DEFAULT NULL, name LONGTEXT NOT NULL, price NUMERIC(10, 2) NOT NULL, path_slug VARCHAR(512) NOT NULL, active TINYINT(1) NOT NULL, description LONGTEXT DEFAULT NULL, INDEX Product_Category_FK (category_id), INDEX Product_Store_FK (store_id), UNIQUE INDEX Product_Path_Slug_U (path_slug), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_parameter (id INT AUTO_INCREMENT NOT NULL, parameter_id INT NOT NULL, product_id INT NOT NULL, value LONGTEXT NOT NULL, INDEX Product_Parameter_Product_FK (product_id), INDEX Product_Parameter_Parameter_FK (parameter_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_image (id INT AUTO_INCREMENT NOT NULL, product_id INT NOT NULL, file_path VARCHAR(512) NOT NULL, sequence_position INT NOT NULL, INDEX Product_Image_Product_FK (product_id), UNIQUE INDEX Product_Image_File_Path_U (file_path), UNIQUE INDEX Product_Image_Product_Sequence_Position_U (product_id, sequence_position), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_shipment_option (id INT AUTO_INCREMENT NOT NULL, product_id INT NOT NULL, shipment_option_id INT NOT NULL, Cost NUMERIC(10, 2) NOT NULL, INDEX Product_Shipment_Option_ID_PRODUCTS_FK (product_id), INDEX Product_Shipment_Option_ID_SHIPMENT_OPTIONS_FK (shipment_option_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_review (id INT AUTO_INCREMENT NOT NULL, product_id INT NOT NULL, user_id INT DEFAULT NULL, rating INT NOT NULL, content LONGTEXT NOT NULL, INDEX IDX_1B3FC0624584665A (product_id), INDEX IDX_1B3FC062A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE variant (id INT AUTO_INCREMENT NOT NULL, name LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE address (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, country_id INT NOT NULL, name LONGTEXT NOT NULL, INDEX IDX_D4E6F81A76ED395 (user_id), INDEX IDX_D4E6F81F92F3E70 (country_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE role (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) NOT NULL, code VARCHAR(50) NOT NULL, subrole TINYINT(1) NOT NULL, UNIQUE INDEX Role_Name_U (name), UNIQUE INDEX Role_Code_U (code), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE token (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, date_created DATETIME NOT NULL, date_expired DATETIME NOT NULL, token VARCHAR(100) NOT NULL, INDEX Token_User_FK (user_id), UNIQUE INDEX Token_Token_U (token), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_setting (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, locale VARCHAR(2) NOT NULL, newsletter TINYINT(1) NOT NULL, UNIQUE INDEX User_Setting_User_U (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE login_attempt (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, date_created DATETIME NOT NULL, ip_address VARCHAR(50) NOT NULL, is_failed TINYINT(1) NOT NULL, INDEX Login_Attempt_User_FK (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE page_visit (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, route LONGTEXT NOT NULL, client_ip VARCHAR(64) NOT NULL, route_params LONGTEXT DEFAULT NULL, date_created DATETIME NOT NULL, query_params LONGTEXT DEFAULT NULL, INDEX IDX_25FF16EFA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE conversation (id INT AUTO_INCREMENT NOT NULL, user_from_id INT NOT NULL, user_to_id INT NOT NULL, identifier VARCHAR(50) NOT NULL, INDEX IDX_8A8E26E920C3C701 (user_from_id), INDEX IDX_8A8E26E9D2F7B13D (user_to_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE store_subuser (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, store_id INT NOT NULL, password_hash VARCHAR(200) NOT NULL, roles JSON NOT NULL, identifier VARCHAR(50) NOT NULL, INDEX IDX_4D2E8525A76ED395 (user_id), INDEX IDX_4D2E8525B092A811 (store_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE store_opinion (id INT AUTO_INCREMENT NOT NULL, store_id INT NOT NULL, user_id INT NOT NULL, content LONGTEXT DEFAULT NULL, rating INT NOT NULL, INDEX IDX_B20A91C0B092A811 (store_id), INDEX IDX_B20A91C0A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE city (id INT AUTO_INCREMENT NOT NULL, country_id INT NOT NULL, name VARCHAR(100) NOT NULL, note VARCHAR(200) DEFAULT NULL, INDEX Cities_Country_FK (country_id), UNIQUE INDEX Cities_Name_Countries_U (name, country_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE country (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, code VARCHAR(3) NOT NULL, UNIQUE INDEX Countries_Name_U (Name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE message (id INT AUTO_INCREMENT NOT NULL, conversation_id INT NOT NULL, date_created DATETIME NOT NULL, content LONGTEXT NOT NULL, is_read TINYINT(1) NOT NULL, is_from_initiator TINYINT(1) NOT NULL, INDEX IDX_B6BD307F9AC0396 (conversation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Customers (country_id INT NOT NULL, city_id INT NOT NULL, ID INT AUTO_INCREMENT NOT NULL, Date_Created DATETIME NOT NULL, Date_Deleted DATETIME DEFAULT NULL, User_Created INT NOT NULL, User_Deleted INT DEFAULT NULL, Identification_Number VARCHAR(10) NOT NULL, First_Name VARCHAR(50) NOT NULL, Last_Name VARCHAR(50) NOT NULL, Gender VARCHAR(1) NOT NULL, Company_Name VARCHAR(200) DEFAULT NULL, VAT VARCHAR(50) DEFAULT NULL, Email VARCHAR(200) NOT NULL, Phone VARCHAR(50) NOT NULL, Default_Discount INT DEFAULT NULL, Path_Slug VARCHAR(50) NOT NULL, Postal_Code VARCHAR(20) NOT NULL, Street VARCHAR(200) NOT NULL, ID_USERS INT DEFAULT NULL, INDEX IDX_E0A2CC82F92F3E70 (country_id), INDEX IDX_E0A2CC828BAC62AF (city_id), INDEX Customers_ID_USERS_FK (ID_USERS), UNIQUE INDEX Customers_Identification_Number_U (Identification_Number), UNIQUE INDEX Customers_Path_Slug_U (Path_Slug), PRIMARY KEY(ID)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE shipment_option (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) NOT NULL, cost NUMERIC(10, 2) NOT NULL, UNIQUE INDEX Shipment_Option_Name_U (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE parameter (id INT AUTO_INCREMENT NOT NULL, name LONGTEXT NOT NULL, unit LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Sizes (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) NOT NULL, note VARCHAR(200) DEFAULT NULL, UNIQUE INDEX Sizes_Name_U (Name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE store ADD CONSTRAINT FK_FF5758778BAC62AF FOREIGN KEY (city_id) REFERENCES city (id)');
        $this->addSql('ALTER TABLE store ADD CONSTRAINT FK_FF575877F92F3E70 FOREIGN KEY (country_id) REFERENCES country (id)');
        $this->addSql('ALTER TABLE store ADD CONSTRAINT FK_FF5758777E3C61F9 FOREIGN KEY (owner_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE order_product_variant ADD CONSTRAINT FK_EB9367418D9F6D38 FOREIGN KEY (order_id) REFERENCES order_2983 (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE order_product_variant ADD CONSTRAINT FK_EB936741A80EF684 FOREIGN KEY (product_variant_id) REFERENCES product_variant (id)');
        $this->addSql('ALTER TABLE order_product_variant ADD CONSTRAINT FK_EB9367416BF700BD FOREIGN KEY (status_id) REFERENCES order_status (id)');
        $this->addSql('ALTER TABLE order_2983 ADD CONSTRAINT FK_B562D6D3EA30A9B2 FOREIGN KEY (user_created) REFERENCES user (id)');
        $this->addSql('ALTER TABLE order_2983 ADD CONSTRAINT FK_B562D6D3B3365039 FOREIGN KEY (user_deleted) REFERENCES user (id)');
        $this->addSql('ALTER TABLE order_2983 ADD CONSTRAINT FK_B562D6D3A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE product_variant ADD CONSTRAINT FK_209AA41D4584665A FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE product_variant ADD CONSTRAINT FK_209AA41D3B69A9AF FOREIGN KEY (variant_id) REFERENCES variant (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649D60322AC FOREIGN KEY (role_id) REFERENCES role (id)');
        $this->addSql('ALTER TABLE category ADD CONSTRAINT FK_64C19C1727ACA70 FOREIGN KEY (parent_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD12469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04ADB092A811 FOREIGN KEY (store_id) REFERENCES store (id)');
        $this->addSql('ALTER TABLE product_parameter ADD CONSTRAINT FK_4437279D7C56DBD6 FOREIGN KEY (parameter_id) REFERENCES parameter (id)');
        $this->addSql('ALTER TABLE product_parameter ADD CONSTRAINT FK_4437279D4584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE product_image ADD CONSTRAINT FK_64617F034584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE product_shipment_option ADD CONSTRAINT FK_CE3D9FE84584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE product_shipment_option ADD CONSTRAINT FK_CE3D9FE89DD71B5E FOREIGN KEY (shipment_option_id) REFERENCES shipment_option (id)');
        $this->addSql('ALTER TABLE product_review ADD CONSTRAINT FK_1B3FC0624584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE product_review ADD CONSTRAINT FK_1B3FC062A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE address ADD CONSTRAINT FK_D4E6F81A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE address ADD CONSTRAINT FK_D4E6F81F92F3E70 FOREIGN KEY (country_id) REFERENCES country (id)');
        $this->addSql('ALTER TABLE token ADD CONSTRAINT FK_5F37A13BA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_setting ADD CONSTRAINT FK_C779A692A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE login_attempt ADD CONSTRAINT FK_8C11C1BA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE page_visit ADD CONSTRAINT FK_25FF16EFA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE conversation ADD CONSTRAINT FK_8A8E26E920C3C701 FOREIGN KEY (user_from_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE conversation ADD CONSTRAINT FK_8A8E26E9D2F7B13D FOREIGN KEY (user_to_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE store_subuser ADD CONSTRAINT FK_4D2E8525A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE store_subuser ADD CONSTRAINT FK_4D2E8525B092A811 FOREIGN KEY (store_id) REFERENCES store (id)');
        $this->addSql('ALTER TABLE store_opinion ADD CONSTRAINT FK_B20A91C0B092A811 FOREIGN KEY (store_id) REFERENCES store (id)');
        $this->addSql('ALTER TABLE store_opinion ADD CONSTRAINT FK_B20A91C0A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE city ADD CONSTRAINT FK_2D5B0234F92F3E70 FOREIGN KEY (country_id) REFERENCES country (id)');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307F9AC0396 FOREIGN KEY (conversation_id) REFERENCES conversation (id)');
        $this->addSql('ALTER TABLE Customers ADD CONSTRAINT FK_E0A2CC823B997DA3 FOREIGN KEY (ID_USERS) REFERENCES user (id)');
        $this->addSql('ALTER TABLE Customers ADD CONSTRAINT FK_E0A2CC82F92F3E70 FOREIGN KEY (country_id) REFERENCES country (id)');
        $this->addSql('ALTER TABLE Customers ADD CONSTRAINT FK_E0A2CC828BAC62AF FOREIGN KEY (city_id) REFERENCES city (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04ADB092A811');
        $this->addSql('ALTER TABLE store_subuser DROP FOREIGN KEY FK_4D2E8525B092A811');
        $this->addSql('ALTER TABLE store_opinion DROP FOREIGN KEY FK_B20A91C0B092A811');
        $this->addSql('ALTER TABLE order_product_variant DROP FOREIGN KEY FK_EB9367418D9F6D38');
        $this->addSql('ALTER TABLE order_product_variant DROP FOREIGN KEY FK_EB936741A80EF684');
        $this->addSql('ALTER TABLE order_product_variant DROP FOREIGN KEY FK_EB9367416BF700BD');
        $this->addSql('ALTER TABLE store DROP FOREIGN KEY FK_FF5758777E3C61F9');
        $this->addSql('ALTER TABLE order_2983 DROP FOREIGN KEY FK_B562D6D3EA30A9B2');
        $this->addSql('ALTER TABLE order_2983 DROP FOREIGN KEY FK_B562D6D3B3365039');
        $this->addSql('ALTER TABLE order_2983 DROP FOREIGN KEY FK_B562D6D3A76ED395');
        $this->addSql('ALTER TABLE product_review DROP FOREIGN KEY FK_1B3FC062A76ED395');
        $this->addSql('ALTER TABLE address DROP FOREIGN KEY FK_D4E6F81A76ED395');
        $this->addSql('ALTER TABLE token DROP FOREIGN KEY FK_5F37A13BA76ED395');
        $this->addSql('ALTER TABLE user_setting DROP FOREIGN KEY FK_C779A692A76ED395');
        $this->addSql('ALTER TABLE login_attempt DROP FOREIGN KEY FK_8C11C1BA76ED395');
        $this->addSql('ALTER TABLE page_visit DROP FOREIGN KEY FK_25FF16EFA76ED395');
        $this->addSql('ALTER TABLE conversation DROP FOREIGN KEY FK_8A8E26E920C3C701');
        $this->addSql('ALTER TABLE conversation DROP FOREIGN KEY FK_8A8E26E9D2F7B13D');
        $this->addSql('ALTER TABLE store_subuser DROP FOREIGN KEY FK_4D2E8525A76ED395');
        $this->addSql('ALTER TABLE store_opinion DROP FOREIGN KEY FK_B20A91C0A76ED395');
        $this->addSql('ALTER TABLE Customers DROP FOREIGN KEY FK_E0A2CC823B997DA3');
        $this->addSql('ALTER TABLE category DROP FOREIGN KEY FK_64C19C1727ACA70');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04AD12469DE2');
        $this->addSql('ALTER TABLE product_variant DROP FOREIGN KEY FK_209AA41D4584665A');
        $this->addSql('ALTER TABLE product_parameter DROP FOREIGN KEY FK_4437279D4584665A');
        $this->addSql('ALTER TABLE product_image DROP FOREIGN KEY FK_64617F034584665A');
        $this->addSql('ALTER TABLE product_shipment_option DROP FOREIGN KEY FK_CE3D9FE84584665A');
        $this->addSql('ALTER TABLE product_review DROP FOREIGN KEY FK_1B3FC0624584665A');
        $this->addSql('ALTER TABLE product_variant DROP FOREIGN KEY FK_209AA41D3B69A9AF');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649D60322AC');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307F9AC0396');
        $this->addSql('ALTER TABLE store DROP FOREIGN KEY FK_FF5758778BAC62AF');
        $this->addSql('ALTER TABLE Customers DROP FOREIGN KEY FK_E0A2CC828BAC62AF');
        $this->addSql('ALTER TABLE store DROP FOREIGN KEY FK_FF575877F92F3E70');
        $this->addSql('ALTER TABLE address DROP FOREIGN KEY FK_D4E6F81F92F3E70');
        $this->addSql('ALTER TABLE city DROP FOREIGN KEY FK_2D5B0234F92F3E70');
        $this->addSql('ALTER TABLE Customers DROP FOREIGN KEY FK_E0A2CC82F92F3E70');
        $this->addSql('ALTER TABLE product_shipment_option DROP FOREIGN KEY FK_CE3D9FE89DD71B5E');
        $this->addSql('ALTER TABLE product_parameter DROP FOREIGN KEY FK_4437279D7C56DBD6');
        $this->addSql('DROP TABLE store');
        $this->addSql('DROP TABLE order_product_variant');
        $this->addSql('DROP TABLE order_2983');
        $this->addSql('DROP TABLE product_variant');
        $this->addSql('DROP TABLE order_status');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE product');
        $this->addSql('DROP TABLE product_parameter');
        $this->addSql('DROP TABLE product_image');
        $this->addSql('DROP TABLE product_shipment_option');
        $this->addSql('DROP TABLE product_review');
        $this->addSql('DROP TABLE variant');
        $this->addSql('DROP TABLE address');
        $this->addSql('DROP TABLE role');
        $this->addSql('DROP TABLE token');
        $this->addSql('DROP TABLE user_setting');
        $this->addSql('DROP TABLE login_attempt');
        $this->addSql('DROP TABLE page_visit');
        $this->addSql('DROP TABLE conversation');
        $this->addSql('DROP TABLE store_subuser');
        $this->addSql('DROP TABLE store_opinion');
        $this->addSql('DROP TABLE city');
        $this->addSql('DROP TABLE country');
        $this->addSql('DROP TABLE message');
        $this->addSql('DROP TABLE Customers');
        $this->addSql('DROP TABLE shipment_option');
        $this->addSql('DROP TABLE parameter');
        $this->addSql('DROP TABLE Sizes');
    }
}
