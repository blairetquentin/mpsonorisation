<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260504072929 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE config_batterie DROP INDEX IDX_296E26CE7A912D19, ADD UNIQUE INDEX UNIQ_296E26CE7A912D19 (element_scene_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE config_batterie DROP INDEX UNIQ_296E26CE7A912D19, ADD INDEX IDX_296E26CE7A912D19 (element_scene_id)');
    }
}
