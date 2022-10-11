<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221009164630 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE mods_client_mods DROP FOREIGN KEY FK_CFA3578450AAC974');
        $this->addSql('ALTER TABLE partner_client_mods DROP FOREIGN KEY FK_FBF187D650AAC974');
        $this->addSql('ALTER TABLE structure_client_mods DROP FOREIGN KEY FK_23D4133B50AAC974');
        $this->addSql('DROP TABLE client_mods');
        $this->addSql('DROP TABLE mods_client_mods');
        $this->addSql('DROP TABLE partner_client_mods');
        $this->addSql('DROP TABLE structure_client_mods');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE client_mods (id INT AUTO_INCREMENT NOT NULL, is_active TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE mods_client_mods (mods_id INT NOT NULL, client_mods_id INT NOT NULL, INDEX IDX_CFA357842978D09 (mods_id), INDEX IDX_CFA3578450AAC974 (client_mods_id), PRIMARY KEY(mods_id, client_mods_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE partner_client_mods (partner_id INT NOT NULL, client_mods_id INT NOT NULL, INDEX IDX_FBF187D69393F8FE (partner_id), INDEX IDX_FBF187D650AAC974 (client_mods_id), PRIMARY KEY(partner_id, client_mods_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE structure_client_mods (structure_id INT NOT NULL, client_mods_id INT NOT NULL, INDEX IDX_23D4133B2534008B (structure_id), INDEX IDX_23D4133B50AAC974 (client_mods_id), PRIMARY KEY(structure_id, client_mods_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE mods_client_mods ADD CONSTRAINT FK_CFA357842978D09 FOREIGN KEY (mods_id) REFERENCES mods (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE mods_client_mods ADD CONSTRAINT FK_CFA3578450AAC974 FOREIGN KEY (client_mods_id) REFERENCES client_mods (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE partner_client_mods ADD CONSTRAINT FK_FBF187D650AAC974 FOREIGN KEY (client_mods_id) REFERENCES client_mods (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE partner_client_mods ADD CONSTRAINT FK_FBF187D69393F8FE FOREIGN KEY (partner_id) REFERENCES partner (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE structure_client_mods ADD CONSTRAINT FK_23D4133B2534008B FOREIGN KEY (structure_id) REFERENCES structure (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE structure_client_mods ADD CONSTRAINT FK_23D4133B50AAC974 FOREIGN KEY (client_mods_id) REFERENCES client_mods (id) ON DELETE CASCADE');
    }
}
