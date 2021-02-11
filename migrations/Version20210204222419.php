<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210204222419 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE Produits_styles (produits_id INT NOT NULL, styles_id INT NOT NULL, INDEX IDX_274BE116CD11A2CF (produits_id), INDEX IDX_274BE116B0A3C560 (styles_id), PRIMARY KEY(produits_id, styles_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE Produits_styles ADD CONSTRAINT FK_274BE116CD11A2CF FOREIGN KEY (produits_id) REFERENCES Produits (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE Produits_styles ADD CONSTRAINT FK_274BE116B0A3C560 FOREIGN KEY (styles_id) REFERENCES Styles (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE Produits_styles');
    }
}
