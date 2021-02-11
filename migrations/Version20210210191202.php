<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210210191202 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ensembles ADD budget_id INT NOT NULL');
        $this->addSql('ALTER TABLE ensembles ADD CONSTRAINT FK_D0E337C736ABA6B8 FOREIGN KEY (budget_id) REFERENCES budget (id)');
        $this->addSql('CREATE INDEX IDX_D0E337C736ABA6B8 ON ensembles (budget_id)');
        $this->addSql('ALTER TABLE favoris_utilisateurs ADD budget_id INT NOT NULL');
        $this->addSql('ALTER TABLE favoris_utilisateurs ADD CONSTRAINT FK_8C3FE72A36ABA6B8 FOREIGN KEY (budget_id) REFERENCES budget (id)');
        $this->addSql('CREATE INDEX IDX_8C3FE72A36ABA6B8 ON favoris_utilisateurs (budget_id)');
        $this->addSql('ALTER TABLE produits ADD budget_id INT NOT NULL');
        $this->addSql('ALTER TABLE produits ADD CONSTRAINT FK_BE2DDF8C36ABA6B8 FOREIGN KEY (budget_id) REFERENCES budget (id)');
        $this->addSql('CREATE INDEX IDX_BE2DDF8C36ABA6B8 ON produits (budget_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ensembles DROP FOREIGN KEY FK_D0E337C736ABA6B8');
        $this->addSql('DROP INDEX IDX_D0E337C736ABA6B8 ON ensembles');
        $this->addSql('ALTER TABLE ensembles DROP budget_id');
        $this->addSql('ALTER TABLE favoris_utilisateurs DROP FOREIGN KEY FK_8C3FE72A36ABA6B8');
        $this->addSql('DROP INDEX IDX_8C3FE72A36ABA6B8 ON favoris_utilisateurs');
        $this->addSql('ALTER TABLE favoris_utilisateurs DROP budget_id');
        $this->addSql('ALTER TABLE produits DROP FOREIGN KEY FK_BE2DDF8C36ABA6B8');
        $this->addSql('DROP INDEX IDX_BE2DDF8C36ABA6B8 ON produits');
        $this->addSql('ALTER TABLE produits DROP budget_id');
    }
}
