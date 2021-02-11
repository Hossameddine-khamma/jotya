<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210204222859 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE Produits_ensembles (produits_id INT NOT NULL, ensembles_id INT NOT NULL, INDEX IDX_B8C1B2C7CD11A2CF (produits_id), INDEX IDX_B8C1B2C75BA556D3 (ensembles_id), PRIMARY KEY(produits_id, ensembles_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE Produits_ensembles ADD CONSTRAINT FK_B8C1B2C7CD11A2CF FOREIGN KEY (produits_id) REFERENCES Produits (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE Produits_ensembles ADD CONSTRAINT FK_B8C1B2C75BA556D3 FOREIGN KEY (ensembles_id) REFERENCES Ensembles (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE Produits_ensembles');
    }
}
