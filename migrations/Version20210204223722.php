<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210204223722 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE Utilisateurs ADD Favoris_utilisateurs_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE Utilisateurs ADD CONSTRAINT FK_497B315EF4C24E9C FOREIGN KEY (favoris_utilisateurs_id) REFERENCES Favoris_utilisateurs (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_497B315EF4C24E9C ON utilisateurs (favoris_utilisateurs_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE Utilisateurs DROP FOREIGN KEY FK_497B315EF4C24E9C');
        $this->addSql('DROP INDEX UNIQ_497B315EF4C24E9C ON Utilisateurs');
        $this->addSql('ALTER TABLE Utilisateurs DROP favoris_utilisateurs_id');
    }
}
