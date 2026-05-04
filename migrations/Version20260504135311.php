<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260504135311 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE materiel_suggere ADD config_batterie_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE materiel_suggere ADD CONSTRAINT FK_405C11E094A4B128 FOREIGN KEY (config_batterie_id) REFERENCES config_batterie (id)');
        $this->addSql('CREATE INDEX IDX_405C11E094A4B128 ON materiel_suggere (config_batterie_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE materiel_suggere DROP FOREIGN KEY FK_405C11E094A4B128');
        $this->addSql('DROP INDEX IDX_405C11E094A4B128 ON materiel_suggere');
        $this->addSql('ALTER TABLE materiel_suggere DROP config_batterie_id');
    }
}
