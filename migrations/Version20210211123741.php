<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210211123741 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE taille (id INT AUTO_INCREMENT NOT NULL, valeur VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE type (id INT AUTO_INCREMENT NOT NULL, description VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE favoris_utilisateurs ADD taille_haut_id INT DEFAULT NULL, ADD taille_bas_id INT DEFAULT NULL, ADD chaussures_id INT DEFAULT NULL, DROP taille_haut, DROP taille_bas, DROP chaussures, CHANGE budget_id budget_id INT NOT NULL');
        $this->addSql('ALTER TABLE favoris_utilisateurs ADD CONSTRAINT FK_8C3FE72A789F4F70 FOREIGN KEY (taille_haut_id) REFERENCES taille (id)');
        $this->addSql('ALTER TABLE favoris_utilisateurs ADD CONSTRAINT FK_8C3FE72AD0A99A79 FOREIGN KEY (taille_bas_id) REFERENCES taille (id)');
        $this->addSql('ALTER TABLE favoris_utilisateurs ADD CONSTRAINT FK_8C3FE72AB13A3C88 FOREIGN KEY (chaussures_id) REFERENCES taille (id)');
        $this->addSql('CREATE INDEX IDX_8C3FE72A789F4F70 ON favoris_utilisateurs (taille_haut_id)');
        $this->addSql('CREATE INDEX IDX_8C3FE72AD0A99A79 ON favoris_utilisateurs (taille_bas_id)');
        $this->addSql('CREATE INDEX IDX_8C3FE72AB13A3C88 ON favoris_utilisateurs (chaussures_id)');
        $this->addSql('ALTER TABLE produits ADD type_id INT NOT NULL, ADD taille_id INT NOT NULL, DROP type, DROP taille');
        $this->addSql('ALTER TABLE produits ADD CONSTRAINT FK_BE2DDF8CC54C8C93 FOREIGN KEY (type_id) REFERENCES type (id)');
        $this->addSql('ALTER TABLE produits ADD CONSTRAINT FK_BE2DDF8CFF25611A FOREIGN KEY (taille_id) REFERENCES taille (id)');
        $this->addSql('CREATE INDEX IDX_BE2DDF8CC54C8C93 ON produits (type_id)');
        $this->addSql('CREATE INDEX IDX_BE2DDF8CFF25611A ON produits (taille_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE favoris_utilisateurs DROP FOREIGN KEY FK_8C3FE72A789F4F70');
        $this->addSql('ALTER TABLE favoris_utilisateurs DROP FOREIGN KEY FK_8C3FE72AD0A99A79');
        $this->addSql('ALTER TABLE favoris_utilisateurs DROP FOREIGN KEY FK_8C3FE72AB13A3C88');
        $this->addSql('ALTER TABLE produits DROP FOREIGN KEY FK_BE2DDF8CFF25611A');
        $this->addSql('ALTER TABLE produits DROP FOREIGN KEY FK_BE2DDF8CC54C8C93');
        $this->addSql('DROP TABLE taille');
        $this->addSql('DROP TABLE type');
        $this->addSql('DROP INDEX IDX_8C3FE72A789F4F70 ON favoris_utilisateurs');
        $this->addSql('DROP INDEX IDX_8C3FE72AD0A99A79 ON favoris_utilisateurs');
        $this->addSql('DROP INDEX IDX_8C3FE72AB13A3C88 ON favoris_utilisateurs');
        $this->addSql('ALTER TABLE favoris_utilisateurs ADD taille_haut VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, ADD taille_bas VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, ADD chaussures VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, DROP taille_haut_id, DROP taille_bas_id, DROP chaussures_id, CHANGE budget_id budget_id INT DEFAULT NULL');
        $this->addSql('DROP INDEX IDX_BE2DDF8CC54C8C93 ON produits');
        $this->addSql('DROP INDEX IDX_BE2DDF8CFF25611A ON produits');
        $this->addSql('ALTER TABLE produits ADD type VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, ADD taille VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, DROP type_id, DROP taille_id');
    }
}
