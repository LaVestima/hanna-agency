<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191128230948 extends AbstractMigration
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
        $this->addSql('ALTER TABLE parameter_category ADD CONSTRAINT FK_EB3EC45D7C56DBD6 FOREIGN KEY (parameter_id) REFERENCES parameter (id)');
        $this->addSql('ALTER TABLE parameter_category ADD CONSTRAINT FK_EB3EC45D12469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('CREATE UNIQUE INDEX Product_Image_File_Path_U ON product_image (file_path)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE parameter_category');
        $this->addSql('DROP INDEX Product_Image_File_Path_U ON product_image');
    }
}
