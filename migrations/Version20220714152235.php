<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220714152235 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE structure_client_mods (structure_id INT NOT NULL, client_mods_id INT NOT NULL, INDEX IDX_23D4133B2534008B (structure_id), INDEX IDX_23D4133B50AAC974 (client_mods_id), PRIMARY KEY(structure_id, client_mods_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE structure_client_mods ADD CONSTRAINT FK_23D4133B2534008B FOREIGN KEY (structure_id) REFERENCES structure (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE structure_client_mods ADD CONSTRAINT FK_23D4133B50AAC974 FOREIGN KEY (client_mods_id) REFERENCES client_mods (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE structure_client_mods');
    }
}
