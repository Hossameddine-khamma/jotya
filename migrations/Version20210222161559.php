<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210222161559 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE favoris_utilisateurs ADD genre_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE favoris_utilisateurs ADD CONSTRAINT FK_8C3FE72A4296D31F FOREIGN KEY (genre_id) REFERENCES genre (id)');
        $this->addSql('CREATE INDEX IDX_8C3FE72A4296D31F ON favoris_utilisateurs (genre_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE favoris_utilisateurs DROP FOREIGN KEY FK_8C3FE72A4296D31F');
        $this->addSql('DROP INDEX IDX_8C3FE72A4296D31F ON favoris_utilisateurs');
        $this->addSql('ALTER TABLE favoris_utilisateurs DROP genre_id');
    }
}
