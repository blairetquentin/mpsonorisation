<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260505094444 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE config_batterie DROP FOREIGN KEY `FK_296E26CEBA134626`');
        $this->addSql('DROP INDEX IDX_296E26CEBA134626 ON config_batterie');
        $this->addSql('ALTER TABLE config_batterie CHANGE micro_symballe_id micro_cymbale_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE config_batterie ADD CONSTRAINT FK_296E26CE48ED105A FOREIGN KEY (micro_cymbale_id) REFERENCES materiel_suggere (id)');
        $this->addSql('CREATE INDEX IDX_296E26CE48ED105A ON config_batterie (micro_cymbale_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE config_batterie DROP FOREIGN KEY FK_296E26CE48ED105A');
        $this->addSql('DROP INDEX IDX_296E26CE48ED105A ON config_batterie');
        $this->addSql('ALTER TABLE config_batterie CHANGE micro_cymbale_id micro_symballe_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE config_batterie ADD CONSTRAINT `FK_296E26CEBA134626` FOREIGN KEY (micro_symballe_id) REFERENCES materiel_suggere (id)');
        $this->addSql('CREATE INDEX IDX_296E26CEBA134626 ON config_batterie (micro_symballe_id)');
    }
}
