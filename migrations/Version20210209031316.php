<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210209031316 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE niveau_competence');
        $this->addSql('ALTER TABLE profil_de_sortie ADD apprenants_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE profil_de_sortie ADD CONSTRAINT FK_8F96B7F6D4B7C9BD FOREIGN KEY (apprenants_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_8F96B7F6D4B7C9BD ON profil_de_sortie (apprenants_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE niveau_competence (niveau_id INT NOT NULL, competence_id INT NOT NULL, INDEX IDX_C058EEB215761DAB (competence_id), INDEX IDX_C058EEB2B3E9C81 (niveau_id), PRIMARY KEY(niveau_id, competence_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE niveau_competence ADD CONSTRAINT FK_C058EEB215761DAB FOREIGN KEY (competence_id) REFERENCES competence (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE niveau_competence ADD CONSTRAINT FK_C058EEB2B3E9C81 FOREIGN KEY (niveau_id) REFERENCES niveau (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE profil_de_sortie DROP FOREIGN KEY FK_8F96B7F6D4B7C9BD');
        $this->addSql('DROP INDEX IDX_8F96B7F6D4B7C9BD ON profil_de_sortie');
        $this->addSql('ALTER TABLE profil_de_sortie DROP apprenants_id');
    }
}
