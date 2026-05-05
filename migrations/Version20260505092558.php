<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260505092558 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE config_batterie ADD micro_tom_id INT DEFAULT NULL, ADD micro_symballe_id INT DEFAULT NULL, ADD micro_grosse_caisse_id INT DEFAULT NULL, ADD micro_caisse_claire_id INT DEFAULT NULL, ADD micro_charleston_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE config_batterie ADD CONSTRAINT FK_296E26CE5FFE0EA FOREIGN KEY (micro_tom_id) REFERENCES materiel_suggere (id)');
        $this->addSql('ALTER TABLE config_batterie ADD CONSTRAINT FK_296E26CEBA134626 FOREIGN KEY (micro_symballe_id) REFERENCES materiel_suggere (id)');
        $this->addSql('ALTER TABLE config_batterie ADD CONSTRAINT FK_296E26CE9C25E161 FOREIGN KEY (micro_grosse_caisse_id) REFERENCES materiel_suggere (id)');
        $this->addSql('ALTER TABLE config_batterie ADD CONSTRAINT FK_296E26CED20E409D FOREIGN KEY (micro_caisse_claire_id) REFERENCES materiel_suggere (id)');
        $this->addSql('ALTER TABLE config_batterie ADD CONSTRAINT FK_296E26CE4E82E58F FOREIGN KEY (micro_charleston_id) REFERENCES materiel_suggere (id)');
        $this->addSql('CREATE INDEX IDX_296E26CE5FFE0EA ON config_batterie (micro_tom_id)');
        $this->addSql('CREATE INDEX IDX_296E26CEBA134626 ON config_batterie (micro_symballe_id)');
        $this->addSql('CREATE INDEX IDX_296E26CE9C25E161 ON config_batterie (micro_grosse_caisse_id)');
        $this->addSql('CREATE INDEX IDX_296E26CED20E409D ON config_batterie (micro_caisse_claire_id)');
        $this->addSql('CREATE INDEX IDX_296E26CE4E82E58F ON config_batterie (micro_charleston_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE config_batterie DROP FOREIGN KEY FK_296E26CE5FFE0EA');
        $this->addSql('ALTER TABLE config_batterie DROP FOREIGN KEY FK_296E26CEBA134626');
        $this->addSql('ALTER TABLE config_batterie DROP FOREIGN KEY FK_296E26CE9C25E161');
        $this->addSql('ALTER TABLE config_batterie DROP FOREIGN KEY FK_296E26CED20E409D');
        $this->addSql('ALTER TABLE config_batterie DROP FOREIGN KEY FK_296E26CE4E82E58F');
        $this->addSql('DROP INDEX IDX_296E26CE5FFE0EA ON config_batterie');
        $this->addSql('DROP INDEX IDX_296E26CEBA134626 ON config_batterie');
        $this->addSql('DROP INDEX IDX_296E26CE9C25E161 ON config_batterie');
        $this->addSql('DROP INDEX IDX_296E26CED20E409D ON config_batterie');
        $this->addSql('DROP INDEX IDX_296E26CE4E82E58F ON config_batterie');
        $this->addSql('ALTER TABLE config_batterie DROP micro_tom_id, DROP micro_symballe_id, DROP micro_grosse_caisse_id, DROP micro_caisse_claire_id, DROP micro_charleston_id');
    }
}
