<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240601174823 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commande_ligne ADD produit_id INT NOT NULL');
        $this->addSql('ALTER TABLE commande_ligne ADD CONSTRAINT FK_6E980440F347EFB FOREIGN KEY (produit_id) REFERENCES produit (id)');
        $this->addSql('CREATE INDEX IDX_6E980440F347EFB ON commande_ligne (produit_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commande_ligne DROP FOREIGN KEY FK_6E980440F347EFB');
        $this->addSql('DROP INDEX IDX_6E980440F347EFB ON commande_ligne');
        $this->addSql('ALTER TABLE commande_ligne DROP produit_id');
    }
}
