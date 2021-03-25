<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210218092648 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE ensembles_produits (ensembles_id INT NOT NULL, produits_id INT NOT NULL, INDEX IDX_E3350EAC5BA556D3 (ensembles_id), INDEX IDX_E3350EACCD11A2CF (produits_id), PRIMARY KEY(ensembles_id, produits_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE ensembles_produits ADD CONSTRAINT FK_E3350EAC5BA556D3 FOREIGN KEY (ensembles_id) REFERENCES ensembles (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE ensembles_produits ADD CONSTRAINT FK_E3350EACCD11A2CF FOREIGN KEY (produits_id) REFERENCES produits (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE produits_saisons');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE produits_saisons (produits_id INT NOT NULL, saisons_id INT NOT NULL, INDEX IDX_26879B12CD11A2CF (produits_id), INDEX IDX_26879B1298E2D5DF (saisons_id), PRIMARY KEY(produits_id, saisons_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE produits_saisons ADD CONSTRAINT FK_26879B1298E2D5DF FOREIGN KEY (saisons_id) REFERENCES saisons (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE produits_saisons ADD CONSTRAINT FK_26879B12CD11A2CF FOREIGN KEY (produits_id) REFERENCES produits (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE ensembles_produits');
    }
}
