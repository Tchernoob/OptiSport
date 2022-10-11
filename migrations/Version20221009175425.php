<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221009175425 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE mods_template_mods');
        $this->addSql('ALTER TABLE mods DROP created_at');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE mods_template_mods (mods_id INT NOT NULL, template_mods_id INT NOT NULL, INDEX IDX_F4763C862978D09 (mods_id), INDEX IDX_F4763C86512FF630 (template_mods_id), PRIMARY KEY(mods_id, template_mods_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE mods_template_mods ADD CONSTRAINT FK_F4763C862978D09 FOREIGN KEY (mods_id) REFERENCES mods (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE mods_template_mods ADD CONSTRAINT FK_F4763C86512FF630 FOREIGN KEY (template_mods_id) REFERENCES template_mods (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE mods ADD created_at DATETIME NOT NULL');
    }
}
