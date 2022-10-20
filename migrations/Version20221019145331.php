<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221019145331 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE mods_template (mods_id INT NOT NULL, template_id INT NOT NULL, INDEX IDX_BEB79AB72978D09 (mods_id), INDEX IDX_BEB79AB75DA0FB8 (template_id), PRIMARY KEY(mods_id, template_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE mods_template ADD CONSTRAINT FK_BEB79AB72978D09 FOREIGN KEY (mods_id) REFERENCES mods (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE mods_template ADD CONSTRAINT FK_BEB79AB75DA0FB8 FOREIGN KEY (template_id) REFERENCES template (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE mods_template_mods');
        $this->addSql('DROP TABLE template_template_mods');
        $this->addSql('ALTER TABLE template_mods MODIFY id INT NOT NULL');
        $this->addSql('ALTER TABLE template_mods DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE template_mods ADD template_id INT NOT NULL, ADD mods_id INT NOT NULL, DROP id, DROP is_active');
        $this->addSql('ALTER TABLE template_mods ADD CONSTRAINT FK_B6D0D8975DA0FB8 FOREIGN KEY (template_id) REFERENCES template (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE template_mods ADD CONSTRAINT FK_B6D0D8972978D09 FOREIGN KEY (mods_id) REFERENCES mods (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_B6D0D8975DA0FB8 ON template_mods (template_id)');
        $this->addSql('CREATE INDEX IDX_B6D0D8972978D09 ON template_mods (mods_id)');
        $this->addSql('ALTER TABLE template_mods ADD PRIMARY KEY (template_id, mods_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE mods_template_mods (mods_id INT NOT NULL, template_mods_id INT NOT NULL, INDEX IDX_F4763C862978D09 (mods_id), INDEX IDX_F4763C86512FF630 (template_mods_id), PRIMARY KEY(mods_id, template_mods_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE template_template_mods (template_id INT NOT NULL, template_mods_id INT NOT NULL, INDEX IDX_AD9BA9B05DA0FB8 (template_id), INDEX IDX_AD9BA9B0512FF630 (template_mods_id), PRIMARY KEY(template_id, template_mods_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE mods_template_mods ADD CONSTRAINT FK_F4763C862978D09 FOREIGN KEY (mods_id) REFERENCES mods (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE mods_template_mods ADD CONSTRAINT FK_F4763C86512FF630 FOREIGN KEY (template_mods_id) REFERENCES template_mods (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE template_template_mods ADD CONSTRAINT FK_AD9BA9B0512FF630 FOREIGN KEY (template_mods_id) REFERENCES template_mods (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE template_template_mods ADD CONSTRAINT FK_AD9BA9B05DA0FB8 FOREIGN KEY (template_id) REFERENCES template (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE mods_template');
        $this->addSql('ALTER TABLE template_mods DROP FOREIGN KEY FK_B6D0D8975DA0FB8');
        $this->addSql('ALTER TABLE template_mods DROP FOREIGN KEY FK_B6D0D8972978D09');
        $this->addSql('DROP INDEX IDX_B6D0D8975DA0FB8 ON template_mods');
        $this->addSql('DROP INDEX IDX_B6D0D8972978D09 ON template_mods');
        $this->addSql('ALTER TABLE template_mods ADD id INT AUTO_INCREMENT NOT NULL, ADD is_active TINYINT(1) NOT NULL, DROP template_id, DROP mods_id, DROP PRIMARY KEY, ADD PRIMARY KEY (id)');
    }
}
