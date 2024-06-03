<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240603153914 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commande_ligne CHANGE produit_id produit_id INT NOT NULL');
        $this->addSql('ALTER TABLE commandes DROP FOREIGN KEY FK_35D4282CF347EFB');
        $this->addSql('DROP INDEX IDX_35D4282CF347EFB ON commandes');
        $this->addSql('ALTER TABLE commandes DROP produit_id, CHANGE informations_sup informations_sup VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commandes ADD produit_id INT NOT NULL, CHANGE informations_sup informations_sup VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE commandes ADD CONSTRAINT FK_35D4282CF347EFB FOREIGN KEY (produit_id) REFERENCES produit (id)');
        $this->addSql('CREATE INDEX IDX_35D4282CF347EFB ON commandes (produit_id)');
        $this->addSql('ALTER TABLE commande_ligne CHANGE produit_id produit_id INT DEFAULT NULL');
    }
}
