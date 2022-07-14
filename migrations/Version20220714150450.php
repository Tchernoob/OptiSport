<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220714150450 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE client_mods (id INT AUTO_INCREMENT NOT NULL, is_active TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE department (id INT AUTO_INCREMENT NOT NULL, region_id INT NOT NULL, name VARCHAR(255) NOT NULL, code VARCHAR(3) NOT NULL, INDEX IDX_CD1DE18A98260155 (region_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE mods (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE mods_template_mods (mods_id INT NOT NULL, template_mods_id INT NOT NULL, INDEX IDX_F4763C862978D09 (mods_id), INDEX IDX_F4763C86512FF630 (template_mods_id), PRIMARY KEY(mods_id, template_mods_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE partner (id INT AUTO_INCREMENT NOT NULL, template_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, is_active TINYINT(1) NOT NULL, email VARCHAR(255) NOT NULL, summary VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, url LONGTEXT NOT NULL, logo VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_312B3E165DA0FB8 (template_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE partner_client_mods (partner_id INT NOT NULL, client_mods_id INT NOT NULL, INDEX IDX_FBF187D69393F8FE (partner_id), INDEX IDX_FBF187D650AAC974 (client_mods_id), PRIMARY KEY(partner_id, client_mods_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE region (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE structure (id INT AUTO_INCREMENT NOT NULL, partner_id INT NOT NULL, department_id INT NOT NULL, template_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, is_active TINYINT(1) NOT NULL, email VARCHAR(255) NOT NULL, summary VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, url VARCHAR(255) NOT NULL, logo VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_6F0137EA9393F8FE (partner_id), INDEX IDX_6F0137EAAE80F5DF (department_id), INDEX IDX_6F0137EA5DA0FB8 (template_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE template (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE template_template_mods (template_id INT NOT NULL, template_mods_id INT NOT NULL, INDEX IDX_AD9BA9B05DA0FB8 (template_id), INDEX IDX_AD9BA9B0512FF630 (template_mods_id), PRIMARY KEY(template_id, template_mods_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE template_mods (id INT AUTO_INCREMENT NOT NULL, is_active TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE department ADD CONSTRAINT FK_CD1DE18A98260155 FOREIGN KEY (region_id) REFERENCES region (id)');
        $this->addSql('ALTER TABLE mods_template_mods ADD CONSTRAINT FK_F4763C862978D09 FOREIGN KEY (mods_id) REFERENCES mods (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE mods_template_mods ADD CONSTRAINT FK_F4763C86512FF630 FOREIGN KEY (template_mods_id) REFERENCES template_mods (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE partner ADD CONSTRAINT FK_312B3E165DA0FB8 FOREIGN KEY (template_id) REFERENCES template (id)');
        $this->addSql('ALTER TABLE partner_client_mods ADD CONSTRAINT FK_FBF187D69393F8FE FOREIGN KEY (partner_id) REFERENCES partner (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE partner_client_mods ADD CONSTRAINT FK_FBF187D650AAC974 FOREIGN KEY (client_mods_id) REFERENCES client_mods (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE structure ADD CONSTRAINT FK_6F0137EA9393F8FE FOREIGN KEY (partner_id) REFERENCES partner (id)');
        $this->addSql('ALTER TABLE structure ADD CONSTRAINT FK_6F0137EAAE80F5DF FOREIGN KEY (department_id) REFERENCES department (id)');
        $this->addSql('ALTER TABLE structure ADD CONSTRAINT FK_6F0137EA5DA0FB8 FOREIGN KEY (template_id) REFERENCES template (id)');
        $this->addSql('ALTER TABLE template_template_mods ADD CONSTRAINT FK_AD9BA9B05DA0FB8 FOREIGN KEY (template_id) REFERENCES template (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE template_template_mods ADD CONSTRAINT FK_AD9BA9B0512FF630 FOREIGN KEY (template_mods_id) REFERENCES template_mods (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE partner_client_mods DROP FOREIGN KEY FK_FBF187D650AAC974');
        $this->addSql('ALTER TABLE structure DROP FOREIGN KEY FK_6F0137EAAE80F5DF');
        $this->addSql('ALTER TABLE mods_template_mods DROP FOREIGN KEY FK_F4763C862978D09');
        $this->addSql('ALTER TABLE partner_client_mods DROP FOREIGN KEY FK_FBF187D69393F8FE');
        $this->addSql('ALTER TABLE structure DROP FOREIGN KEY FK_6F0137EA9393F8FE');
        $this->addSql('ALTER TABLE department DROP FOREIGN KEY FK_CD1DE18A98260155');
        $this->addSql('ALTER TABLE partner DROP FOREIGN KEY FK_312B3E165DA0FB8');
        $this->addSql('ALTER TABLE structure DROP FOREIGN KEY FK_6F0137EA5DA0FB8');
        $this->addSql('ALTER TABLE template_template_mods DROP FOREIGN KEY FK_AD9BA9B05DA0FB8');
        $this->addSql('ALTER TABLE mods_template_mods DROP FOREIGN KEY FK_F4763C86512FF630');
        $this->addSql('ALTER TABLE template_template_mods DROP FOREIGN KEY FK_AD9BA9B0512FF630');
        $this->addSql('DROP TABLE client_mods');
        $this->addSql('DROP TABLE department');
        $this->addSql('DROP TABLE mods');
        $this->addSql('DROP TABLE mods_template_mods');
        $this->addSql('DROP TABLE partner');
        $this->addSql('DROP TABLE partner_client_mods');
        $this->addSql('DROP TABLE region');
        $this->addSql('DROP TABLE structure');
        $this->addSql('DROP TABLE template');
        $this->addSql('DROP TABLE template_template_mods');
        $this->addSql('DROP TABLE template_mods');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
