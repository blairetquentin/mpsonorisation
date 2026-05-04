<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260504125855 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE element_scene ADD materiel_suggere_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE element_scene ADD CONSTRAINT FK_1D5B9B43FEA72B1F FOREIGN KEY (materiel_suggere_id) REFERENCES materiel_suggere (id)');
        $this->addSql('CREATE INDEX IDX_1D5B9B43FEA72B1F ON element_scene (materiel_suggere_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE element_scene DROP FOREIGN KEY FK_1D5B9B43FEA72B1F');
        $this->addSql('DROP INDEX IDX_1D5B9B43FEA72B1F ON element_scene');
        $this->addSql('ALTER TABLE element_scene DROP materiel_suggere_id');
    }
}
