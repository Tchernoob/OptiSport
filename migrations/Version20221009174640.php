<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221009174640 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE partner_mods');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE partner_mods (partner_id INT NOT NULL, mods_id INT NOT NULL, INDEX IDX_332AC1E29393F8FE (partner_id), INDEX IDX_332AC1E22978D09 (mods_id), PRIMARY KEY(partner_id, mods_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE partner_mods ADD CONSTRAINT FK_332AC1E22978D09 FOREIGN KEY (mods_id) REFERENCES mods (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE partner_mods ADD CONSTRAINT FK_332AC1E29393F8FE FOREIGN KEY (partner_id) REFERENCES partner (id) ON DELETE CASCADE');
    }
}
