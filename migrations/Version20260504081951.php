<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260504081951 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE materiel_suggere ADD instrument_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE materiel_suggere ADD CONSTRAINT FK_405C11E0CF11D9C FOREIGN KEY (instrument_id) REFERENCES instruments (id)');
        $this->addSql('CREATE INDEX IDX_405C11E0CF11D9C ON materiel_suggere (instrument_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE materiel_suggere DROP FOREIGN KEY FK_405C11E0CF11D9C');
        $this->addSql('DROP INDEX IDX_405C11E0CF11D9C ON materiel_suggere');
        $this->addSql('ALTER TABLE materiel_suggere DROP instrument_id');
    }
}
