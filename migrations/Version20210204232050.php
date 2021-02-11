<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210204232050 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE Commandes_produits (commandes_id INT NOT NULL, produits_id INT NOT NULL, INDEX IDX_D58023F08BF5C2E6 (commandes_id), INDEX IDX_D58023F0CD11A2CF (produits_id), PRIMARY KEY(commandes_id, produits_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE Commandes_produits ADD CONSTRAINT FK_D58023F08BF5C2E6 FOREIGN KEY (commandes_id) REFERENCES Commandes (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE Commandes_produits ADD CONSTRAINT FK_D58023F0CD11A2CF FOREIGN KEY (produits_id) REFERENCES Produits (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE Commandes ADD utilisateurs_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE Commandes ADD CONSTRAINT FK_35D4282C1E969C5 FOREIGN KEY (utilisateurs_id) REFERENCES Utilisateurs (id)');
        $this->addSql('CREATE INDEX IDX_35D4282C1E969C5 ON Commandes (utilisateurs_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE Commandes_produits');
        $this->addSql('ALTER TABLE Commandes DROP FOREIGN KEY FK_35D4282C1E969C5');
        $this->addSql('DROP INDEX IDX_35D4282C1E969C5 ON Commandes');
        $this->addSql('ALTER TABLE Commandes DROP utilisateurs_id');
    }
}
