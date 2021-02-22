<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210222155852 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE ensembles_saisons (ensembles_id INT NOT NULL, saisons_id INT NOT NULL, INDEX IDX_6FDF1B315BA556D3 (ensembles_id), INDEX IDX_6FDF1B3198E2D5DF (saisons_id), PRIMARY KEY(ensembles_id, saisons_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ensembles_styles (ensembles_id INT NOT NULL, styles_id INT NOT NULL, INDEX IDX_168C8FF05BA556D3 (ensembles_id), INDEX IDX_168C8FF0B0A3C560 (styles_id), PRIMARY KEY(ensembles_id, styles_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE genre (id INT AUTO_INCREMENT NOT NULL, description VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE ensembles_saisons ADD CONSTRAINT FK_6FDF1B315BA556D3 FOREIGN KEY (ensembles_id) REFERENCES ensembles (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE ensembles_saisons ADD CONSTRAINT FK_6FDF1B3198E2D5DF FOREIGN KEY (saisons_id) REFERENCES saisons (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE ensembles_styles ADD CONSTRAINT FK_168C8FF05BA556D3 FOREIGN KEY (ensembles_id) REFERENCES ensembles (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE ensembles_styles ADD CONSTRAINT FK_168C8FF0B0A3C560 FOREIGN KEY (styles_id) REFERENCES styles (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE ensembles ADD genre_id INT NOT NULL');
        $this->addSql('ALTER TABLE ensembles ADD CONSTRAINT FK_D0E337C74296D31F FOREIGN KEY (genre_id) REFERENCES genre (id)');
        $this->addSql('CREATE INDEX IDX_D0E337C74296D31F ON ensembles (genre_id)');
        $this->addSql('ALTER TABLE produits ADD genre_id INT NOT NULL');
        $this->addSql('ALTER TABLE produits ADD CONSTRAINT FK_BE2DDF8C4296D31F FOREIGN KEY (genre_id) REFERENCES genre (id)');
        $this->addSql('CREATE INDEX IDX_BE2DDF8C4296D31F ON produits (genre_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ensembles DROP FOREIGN KEY FK_D0E337C74296D31F');
        $this->addSql('ALTER TABLE produits DROP FOREIGN KEY FK_BE2DDF8C4296D31F');
        $this->addSql('DROP TABLE ensembles_saisons');
        $this->addSql('DROP TABLE ensembles_styles');
        $this->addSql('DROP TABLE genre');
        $this->addSql('DROP INDEX IDX_D0E337C74296D31F ON ensembles');
        $this->addSql('ALTER TABLE ensembles DROP genre_id');
        $this->addSql('DROP INDEX IDX_BE2DDF8C4296D31F ON produits');
        $this->addSql('ALTER TABLE produits DROP genre_id');
    }
}
