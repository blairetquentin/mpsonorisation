<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260504121029 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE materiel_suggere DROP FOREIGN KEY `FK_405C11E0166053B4`');
        $this->addSql('DROP INDEX IDX_405C11E0166053B4 ON materiel_suggere');
        $this->addSql('ALTER TABLE materiel_suggere DROP scene_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE materiel_suggere ADD scene_id INT NOT NULL');
        $this->addSql('ALTER TABLE materiel_suggere ADD CONSTRAINT `FK_405C11E0166053B4` FOREIGN KEY (scene_id) REFERENCES scene (id)');
        $this->addSql('CREATE INDEX IDX_405C11E0166053B4 ON materiel_suggere (scene_id)');
    }
}
