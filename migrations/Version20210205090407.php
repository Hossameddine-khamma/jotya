<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210205090407 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE Messages ADD expiditeur_id INT DEFAULT NULL, ADD destinataire_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE Messages ADD CONSTRAINT FK_DB021E96A0FBFEF FOREIGN KEY (expiditeur_id) REFERENCES Utilisateurs (id)');
        $this->addSql('ALTER TABLE Messages ADD CONSTRAINT FK_DB021E96A4F84F6E FOREIGN KEY (destinataire_id) REFERENCES Utilisateurs (id)');
        $this->addSql('CREATE INDEX IDX_DB021E96A0FBFEF ON Messages (expiditeur_id)');
        $this->addSql('CREATE INDEX IDX_DB021E96A4F84F6E ON Messages (destinataire_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE Messages DROP FOREIGN KEY FK_DB021E96A0FBFEF');
        $this->addSql('ALTER TABLE Messages DROP FOREIGN KEY FK_DB021E96A4F84F6E');
        $this->addSql('DROP INDEX IDX_DB021E96A0FBFEF ON Messages');
        $this->addSql('DROP INDEX IDX_DB021E96A4F84F6E ON Messages');
        $this->addSql('ALTER TABLE Messages DROP expiditeur_id, DROP destinataire_id');
    }
}
