<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260408094334 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE categorie (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(50) NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE element_scene (id INT AUTO_INCREMENT NOT NULL, quantite INT NOT NULL, position_x DOUBLE PRECISION NOT NULL, position_y DOUBLE PRECISION NOT NULL, scene_id INT NOT NULL, instrument_id INT NOT NULL, INDEX IDX_1D5B9B43166053B4 (scene_id), INDEX IDX_1D5B9B43CF11D9C (instrument_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE instruments (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(50) NOT NULL, url_instrument VARCHAR(255) NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE materiel (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, url_materiel VARCHAR(255) DEFAULT NULL, reference VARCHAR(255) NOT NULL, stock_dispo INT NOT NULL, stock_total INT NOT NULL, sous_categorie_id INT NOT NULL, INDEX IDX_18D2B091365BF48 (sous_categorie_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE materiel_suggere (id INT AUTO_INCREMENT NOT NULL, quantite INT NOT NULL, scene_id INT NOT NULL, materiel_id INT NOT NULL, INDEX IDX_405C11E0166053B4 (scene_id), INDEX IDX_405C11E016880AAF (materiel_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE panier (id INT AUTO_INCREMENT NOT NULL, date_location DATE NOT NULL, adresse_location VARCHAR(255) NOT NULL, user_id INT NOT NULL, INDEX IDX_24CC0DF2A76ED395 (user_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE panier_materiel (id INT AUTO_INCREMENT NOT NULL, quantite INT NOT NULL, panier_id INT NOT NULL, materiel_id INT NOT NULL, INDEX IDX_8297B9E3F77D927C (panier_id), INDEX IDX_8297B9E316880AAF (materiel_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE scene (id INT AUTO_INCREMENT NOT NULL, statut TINYINT DEFAULT NULL, nom_evenement VARCHAR(255) NOT NULL, date_evenement DATE NOT NULL, nom_artiste VARCHAR(50) NOT NULL, user_id INT NOT NULL, INDEX IDX_D979EFDAA76ED395 (user_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE sous_categorie (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(50) NOT NULL, categorie_id INT NOT NULL, INDEX IDX_52743D7BBCF5E72D (categorie_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE statut (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(50) NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(50) NOT NULL, prenom VARCHAR(50) NOT NULL, date_naissance DATE NOT NULL, email VARCHAR(50) NOT NULL, telephone VARCHAR(50) DEFAULT NULL, adresse VARCHAR(255) DEFAULT NULL, ville_id INT DEFAULT NULL, statut_id INT DEFAULT NULL, INDEX IDX_8D93D649A73F0036 (ville_id), INDEX IDX_8D93D649F6203804 (statut_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE ville (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(50) NOT NULL, departement VARCHAR(50) NOT NULL, region VARCHAR(50) NOT NULL, code_postal VARCHAR(10) NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0E3BD61CE16BA31DBBF396750 (queue_name, available_at, delivered_at, id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE element_scene ADD CONSTRAINT FK_1D5B9B43166053B4 FOREIGN KEY (scene_id) REFERENCES scene (id)');
        $this->addSql('ALTER TABLE element_scene ADD CONSTRAINT FK_1D5B9B43CF11D9C FOREIGN KEY (instrument_id) REFERENCES instruments (id)');
        $this->addSql('ALTER TABLE materiel ADD CONSTRAINT FK_18D2B091365BF48 FOREIGN KEY (sous_categorie_id) REFERENCES sous_categorie (id)');
        $this->addSql('ALTER TABLE materiel_suggere ADD CONSTRAINT FK_405C11E0166053B4 FOREIGN KEY (scene_id) REFERENCES scene (id)');
        $this->addSql('ALTER TABLE materiel_suggere ADD CONSTRAINT FK_405C11E016880AAF FOREIGN KEY (materiel_id) REFERENCES materiel (id)');
        $this->addSql('ALTER TABLE panier ADD CONSTRAINT FK_24CC0DF2A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE panier_materiel ADD CONSTRAINT FK_8297B9E3F77D927C FOREIGN KEY (panier_id) REFERENCES panier (id)');
        $this->addSql('ALTER TABLE panier_materiel ADD CONSTRAINT FK_8297B9E316880AAF FOREIGN KEY (materiel_id) REFERENCES materiel (id)');
        $this->addSql('ALTER TABLE scene ADD CONSTRAINT FK_D979EFDAA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE sous_categorie ADD CONSTRAINT FK_52743D7BBCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649A73F0036 FOREIGN KEY (ville_id) REFERENCES ville (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649F6203804 FOREIGN KEY (statut_id) REFERENCES statut (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE element_scene DROP FOREIGN KEY FK_1D5B9B43166053B4');
        $this->addSql('ALTER TABLE element_scene DROP FOREIGN KEY FK_1D5B9B43CF11D9C');
        $this->addSql('ALTER TABLE materiel DROP FOREIGN KEY FK_18D2B091365BF48');
        $this->addSql('ALTER TABLE materiel_suggere DROP FOREIGN KEY FK_405C11E0166053B4');
        $this->addSql('ALTER TABLE materiel_suggere DROP FOREIGN KEY FK_405C11E016880AAF');
        $this->addSql('ALTER TABLE panier DROP FOREIGN KEY FK_24CC0DF2A76ED395');
        $this->addSql('ALTER TABLE panier_materiel DROP FOREIGN KEY FK_8297B9E3F77D927C');
        $this->addSql('ALTER TABLE panier_materiel DROP FOREIGN KEY FK_8297B9E316880AAF');
        $this->addSql('ALTER TABLE scene DROP FOREIGN KEY FK_D979EFDAA76ED395');
        $this->addSql('ALTER TABLE sous_categorie DROP FOREIGN KEY FK_52743D7BBCF5E72D');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649A73F0036');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649F6203804');
        $this->addSql('DROP TABLE categorie');
        $this->addSql('DROP TABLE element_scene');
        $this->addSql('DROP TABLE instruments');
        $this->addSql('DROP TABLE materiel');
        $this->addSql('DROP TABLE materiel_suggere');
        $this->addSql('DROP TABLE panier');
        $this->addSql('DROP TABLE panier_materiel');
        $this->addSql('DROP TABLE scene');
        $this->addSql('DROP TABLE sous_categorie');
        $this->addSql('DROP TABLE statut');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE ville');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
