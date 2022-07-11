<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220711002523 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE contact (id INT AUTO_INCREMENT NOT NULL, owner_id INT NOT NULL, gender VARCHAR(255) DEFAULT NULL, birthday DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', website VARCHAR(255) DEFAULT NULL, job_title VARCHAR(255) DEFAULT NULL, organization VARCHAR(255) DEFAULT NULL, note LONGTEXT DEFAULT NULL, department VARCHAR(255) DEFAULT NULL, name VARCHAR(255) NOT NULL, surname VARCHAR(255) DEFAULT NULL, phone_numbers LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', emails LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', social_networks LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', is_favorite TINYINT(1) DEFAULT 0 NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_4C62E6387E3C61F9 (owner_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `group` (id INT AUTO_INCREMENT NOT NULL, owner_id INT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_6DC044C57E3C61F9 (owner_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE group_contact (group_id INT NOT NULL, contact_id INT NOT NULL, INDEX IDX_CA62B234FE54D947 (group_id), INDEX IDX_CA62B234E7A1254A (contact_id), PRIMARY KEY(group_id, contact_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE contact ADD CONSTRAINT FK_4C62E6387E3C61F9 FOREIGN KEY (owner_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE `group` ADD CONSTRAINT FK_6DC044C57E3C61F9 FOREIGN KEY (owner_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE group_contact ADD CONSTRAINT FK_CA62B234FE54D947 FOREIGN KEY (group_id) REFERENCES `group` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE group_contact ADD CONSTRAINT FK_CA62B234E7A1254A FOREIGN KEY (contact_id) REFERENCES contact (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user ADD created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', ADD updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE group_contact DROP FOREIGN KEY FK_CA62B234E7A1254A');
        $this->addSql('ALTER TABLE group_contact DROP FOREIGN KEY FK_CA62B234FE54D947');
        $this->addSql('DROP TABLE contact');
        $this->addSql('DROP TABLE `group`');
        $this->addSql('DROP TABLE group_contact');
        $this->addSql('ALTER TABLE user DROP created_at, DROP updated_at');
    }
}
