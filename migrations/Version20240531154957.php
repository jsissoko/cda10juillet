<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240531154957 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE categories_produit (categories_id INT NOT NULL, produit_id INT NOT NULL, INDEX IDX_1AF3D87A21214B7 (categories_id), INDEX IDX_1AF3D87F347EFB (produit_id), PRIMARY KEY(categories_id, produit_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE categories_produit ADD CONSTRAINT FK_1AF3D87A21214B7 FOREIGN KEY (categories_id) REFERENCES categories (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE categories_produit ADD CONSTRAINT FK_1AF3D87F347EFB FOREIGN KEY (produit_id) REFERENCES produit (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE categories_produit DROP FOREIGN KEY FK_1AF3D87A21214B7');
        $this->addSql('ALTER TABLE categories_produit DROP FOREIGN KEY FK_1AF3D87F347EFB');
        $this->addSql('DROP TABLE categories_produit');
    }
}
