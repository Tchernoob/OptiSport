<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220714152454 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE mods_client_mods (mods_id INT NOT NULL, client_mods_id INT NOT NULL, INDEX IDX_CFA357842978D09 (mods_id), INDEX IDX_CFA3578450AAC974 (client_mods_id), PRIMARY KEY(mods_id, client_mods_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE mods_client_mods ADD CONSTRAINT FK_CFA357842978D09 FOREIGN KEY (mods_id) REFERENCES mods (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE mods_client_mods ADD CONSTRAINT FK_CFA3578450AAC974 FOREIGN KEY (client_mods_id) REFERENCES client_mods (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE mods_client_mods');
    }
}
