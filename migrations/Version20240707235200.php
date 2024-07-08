<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240707235200 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE categories (id INT AUTO_INCREMENT NOT NULL, nom_categorie VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE categories_produit (categories_id INT NOT NULL, produit_id INT NOT NULL, INDEX IDX_1AF3D87A21214B7 (categories_id), INDEX IDX_1AF3D87F347EFB (produit_id), PRIMARY KEY(categories_id, produit_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE commande_ligne (id INT AUTO_INCREMENT NOT NULL, commande_id INT NOT NULL, produit_id INT NOT NULL, quantite INT NOT NULL, prix DOUBLE PRECISION NOT NULL, INDEX IDX_6E98044082EA2E54 (commande_id), INDEX IDX_6E980440F347EFB (produit_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE commandes (id INT AUTO_INCREMENT NOT NULL, utilisateur_id INT DEFAULT NULL, date DATETIME NOT NULL, status VARCHAR(255) NOT NULL, total DOUBLE PRECISION NOT NULL, pays VARCHAR(255) NOT NULL, ville VARCHAR(255) NOT NULL, code_postal VARCHAR(255) NOT NULL, nom_rue VARCHAR(255) NOT NULL, numero_rue INT NOT NULL, informations_sup VARCHAR(255) NOT NULL, matricule_cmd INT NOT NULL, UNIQUE INDEX UNIQ_35D4282C34782A00 (matricule_cmd), INDEX IDX_35D4282CFB88E14F (utilisateur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messages (id INT AUTO_INCREMENT NOT NULL, expediteur_id INT NOT NULL, destinataire_id INT NOT NULL, title VARCHAR(255) NOT NULL, message LONGTEXT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', is_read TINYINT(1) NOT NULL, INDEX IDX_DB021E9610335F61 (expediteur_id), INDEX IDX_DB021E96A4F84F6E (destinataire_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE produit (id INT AUTO_INCREMENT NOT NULL, utilisateur_id INT DEFAULT NULL, nom VARCHAR(55) NOT NULL, prix DOUBLE PRECISION NOT NULL, quantite INT NOT NULL, info_produit LONGTEXT NOT NULL, statut_produit TINYINT(1) NOT NULL, ref_prod VARCHAR(25) NOT NULL, image VARCHAR(255) DEFAULT NULL, INDEX IDX_29A5EC27FB88E14F (utilisateur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE utilisateur (id INT AUTO_INCREMENT NOT NULL, utilisateur_id INT DEFAULT NULL, employe_id INT DEFAULT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, nom VARCHAR(255) NOT NULL, pays VARCHAR(255) NOT NULL, ville VARCHAR(255) NOT NULL, nom_rue VARCHAR(255) NOT NULL, numero_rue VARCHAR(255) NOT NULL, telephone INT NOT NULL, matricule VARCHAR(10) DEFAULT NULL, verification_code VARCHAR(180) NOT NULL, is_active TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_1D1C63B3E7927C74 (email), INDEX IDX_1D1C63B3FB88E14F (utilisateur_id), INDEX IDX_1D1C63B31B65292 (employe_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE categories_produit ADD CONSTRAINT FK_1AF3D87A21214B7 FOREIGN KEY (categories_id) REFERENCES categories (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE categories_produit ADD CONSTRAINT FK_1AF3D87F347EFB FOREIGN KEY (produit_id) REFERENCES produit (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE commande_ligne ADD CONSTRAINT FK_6E98044082EA2E54 FOREIGN KEY (commande_id) REFERENCES commandes (id)');
        $this->addSql('ALTER TABLE commande_ligne ADD CONSTRAINT FK_6E980440F347EFB FOREIGN KEY (produit_id) REFERENCES produit (id)');
        $this->addSql('ALTER TABLE commandes ADD CONSTRAINT FK_35D4282CFB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE messages ADD CONSTRAINT FK_DB021E9610335F61 FOREIGN KEY (expediteur_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE messages ADD CONSTRAINT FK_DB021E96A4F84F6E FOREIGN KEY (destinataire_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT FK_29A5EC27FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE utilisateur ADD CONSTRAINT FK_1D1C63B3FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE utilisateur ADD CONSTRAINT FK_1D1C63B31B65292 FOREIGN KEY (employe_id) REFERENCES utilisateur (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE categories_produit DROP FOREIGN KEY FK_1AF3D87A21214B7');
        $this->addSql('ALTER TABLE categories_produit DROP FOREIGN KEY FK_1AF3D87F347EFB');
        $this->addSql('ALTER TABLE commande_ligne DROP FOREIGN KEY FK_6E98044082EA2E54');
        $this->addSql('ALTER TABLE commande_ligne DROP FOREIGN KEY FK_6E980440F347EFB');
        $this->addSql('ALTER TABLE commandes DROP FOREIGN KEY FK_35D4282CFB88E14F');
        $this->addSql('ALTER TABLE messages DROP FOREIGN KEY FK_DB021E9610335F61');
        $this->addSql('ALTER TABLE messages DROP FOREIGN KEY FK_DB021E96A4F84F6E');
        $this->addSql('ALTER TABLE produit DROP FOREIGN KEY FK_29A5EC27FB88E14F');
        $this->addSql('ALTER TABLE utilisateur DROP FOREIGN KEY FK_1D1C63B3FB88E14F');
        $this->addSql('ALTER TABLE utilisateur DROP FOREIGN KEY FK_1D1C63B31B65292');
        $this->addSql('DROP TABLE categories');
        $this->addSql('DROP TABLE categories_produit');
        $this->addSql('DROP TABLE commande_ligne');
        $this->addSql('DROP TABLE commandes');
        $this->addSql('DROP TABLE messages');
        $this->addSql('DROP TABLE produit');
        $this->addSql('DROP TABLE utilisateur');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
