<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version21191118234318 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql("
            INSERT IGNORE INTO role (name, code, subrole) VALUES
                ('Super Administrator', 'ROLE_SUPER_ADMIN', false),
                ('Store Admin', 'ROLE_STORE_ADMIN', false),
                ('User', 'ROLE_USER', false),
                ('View dashboard', 'ROLE_VIEW_DASHBOARD', true),
                ('Read messages', 'ROLE_READ_MESSAGES', true),
                ('Write messages', 'ROLE_WRITE_MESSAGES', true),
                ('Add products', 'ROLE_ADD_PRODUCTS', true),
                ('Edit products', 'ROLE_EDIT_PRODUCTS', true),
                ('Delete products', 'ROLE_DELETE_PRODUCTS', true),
                ('View statistics', 'ROLE_VIEW_STATISTICS', true)
        ");

        $this->addSql("
            INSERT IGNORE INTO user (id, login, email, password_hash, roles) VALUES
                (1, 'admin', 'admin@admin.admin', 
                '\$argon2i\$v=19\$m=1024,t=16,p=2\$WkRnMTAwU25vWGRUaWJ3Yw\$Wws1yFIRpI1UP3sAUnTiKWSWnO6GWwEktswJO0BeuQA', 
                '[\"ROLE_SUPER_ADMIN\"]'),
                (2, 'producer', 'prod@prod.prod', 
                '\$argon2i\$v=19\$m=1024,t=16,p=2\$ZW5wem5oMFd1a2tYVlVxUg\$NYvqhwv5s787f3yzAJ0BA7M+rHcQF+FrOFHloFTCG2U', 
                '[]')
        ");

        $this->addSql("
            INSERT IGNORE INTO category (id, name, identifier, parent_id) VALUES 
                (1, 'Electronics', 'electronics', null),
                (2, 'Clothing', 'clothing', null),
                (3, 'Toys', 'toys', null),
                (4, 'Shirts', 'shirts', 2),
                (5, 'Trousers', 'trousers', 2),
                (6, 'Shoes', 'shoes', 2),
                (7, 'Hats', 'hats', 2),
                (8, 'Socks', 'socks', 2),
                (123, 'Photo cameras', 'photo-cameras', 1)
        ");

        $this->addSql("
            INSERT IGNORE INTO country (id, name, code) VALUES
                (1, 'Poland', 'PL'),
                (2, 'Germany', 'DE'),
                (3, 'France', 'FR'),
                (4, 'Canada', 'CA'),
                (5, 'United States of America', 'US'),
                (6, 'Netherlands', 'NL'),
                (7, 'Belgium', 'BE'),
                (8, 'Sri Lanka', 'LK'),
                (9, 'United Kingdom', 'GB'),
                (10, 'Japan', 'JP')
        ");

        $this->addSql("
            INSERT IGNORE INTO city (id, name, country_id) VALUES
                (1, 'Warsaw', 1),
                (2, 'Lodz', 1),
                (3, 'Berlin', 2),
                (4, 'New York', 5),
                (5, 'Ottawa', 4),
                (6, 'Springdale', 5),
                (7, 'Burgoberbach', 2),
                (8, 'Sri Jayawardenepura Kotte', 8),
                (9, 'Llanfair­pwllgwyngyll­gogery­chwyrn­drobwll­llan­tysilio­gogo­goch', 9),
                (10, 'Essendon', 9),
                (11, 'Almelo', 6),
                (12, 'San Diego', 5),
                (13, 'Garvan', 9),
                (14, 'Annemasse', 3),
                (15, 'Toronto', 4)
        ");

        $this->addSql("
            INSERT INTO store (id, short_name, full_name, first_name, last_name, vat, country_id, city_id, postal_code, street, email, phone, admin_id, active, identifier, date_created) VALUES
                (1, 'Opticomp', 'Opticomp Clothing', null, null,
                'US773365342', 5, 12, 'CA 92121', '331 Hamill Avenue', 'LarrySLowell@armyspy.com',
                '858-401-5106', 4, true, 'opticomp', now()),
                (2, 'Omni', 'Omni Architectural Designs', 'Christopher', 'Harris',
                'GB028338520', 9, 13, 'PH33 3AF', '50 Old Chapel Road', 'RileyCraig@teleworm.us',
                '078 2966 5839', 2, true, 'omni', now())
        ");

        $this->addSql("
            INSERT INTO product (id, name, price, category_id, store_id, active, path_slug, date_created, user_created) VALUES 
                (10, 'Sony Alpha a6400 Mirrorless Digital Camera with 18-135mm Lens', 1298, 1, 2, 0, 'Sony-Alpha-a6400-Mirrorless-Digital-Camera-with-18-135mm-Lens', now(), 0)
        ");
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql("DELETE FROM product");
        $this->addSql("DELETE FROM store");
        $this->addSql("DELETE FROM city");
        $this->addSql("DELETE FROM country");
        $this->addSql("DELETE FROM category");
        $this->addSql("DELETE FROM user");
        $this->addSql("DELETE FROM role");
    }
}
