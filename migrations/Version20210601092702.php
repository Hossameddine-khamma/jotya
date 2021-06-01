<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210601092702 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE favoris_utilisateurs ADD utilisateurs_id INT DEFAULT NULL, CHANGE budget_id budget_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE favoris_utilisateurs ADD CONSTRAINT FK_8C3FE72A1E969C5 FOREIGN KEY (utilisateurs_id) REFERENCES utilisateurs (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8C3FE72A1E969C5 ON favoris_utilisateurs (utilisateurs_id)');
        $this->addSql('ALTER TABLE utilisateurs DROP FOREIGN KEY FK_497B315EF4C24E9C');
        $this->addSql('DROP INDEX UNIQ_497B315EF4C24E9C ON utilisateurs');
        $this->addSql('ALTER TABLE utilisateurs DROP Favoris_utilisateurs_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE favoris_utilisateurs DROP FOREIGN KEY FK_8C3FE72A1E969C5');
        $this->addSql('DROP INDEX UNIQ_8C3FE72A1E969C5 ON favoris_utilisateurs');
        $this->addSql('ALTER TABLE favoris_utilisateurs DROP utilisateurs_id, CHANGE budget_id budget_id INT NOT NULL');
        $this->addSql('ALTER TABLE utilisateurs ADD Favoris_utilisateurs_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE utilisateurs ADD CONSTRAINT FK_497B315EF4C24E9C FOREIGN KEY (Favoris_utilisateurs_id) REFERENCES favoris_utilisateurs (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_497B315EF4C24E9C ON utilisateurs (Favoris_utilisateurs_id)');
    }
}
