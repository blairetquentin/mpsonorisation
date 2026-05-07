<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260507080811 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE element_scene ADD micro_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE element_scene ADD CONSTRAINT FK_1D5B9B43C7D55619 FOREIGN KEY (micro_id) REFERENCES instruments (id)');
        $this->addSql('CREATE INDEX IDX_1D5B9B43C7D55619 ON element_scene (micro_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE element_scene DROP FOREIGN KEY FK_1D5B9B43C7D55619');
        $this->addSql('DROP INDEX IDX_1D5B9B43C7D55619 ON element_scene');
        $this->addSql('ALTER TABLE element_scene DROP micro_id');
    }
}
