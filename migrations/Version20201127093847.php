<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201127093847 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE niveau_competence');
        $this->addSql('ALTER TABLE niveau DROP FOREIGN KEY FK_4BDFF36BA660B158');
        $this->addSql('DROP INDEX IDX_4BDFF36BA660B158 ON niveau');
        $this->addSql('ALTER TABLE niveau CHANGE competences_id competence_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE niveau ADD CONSTRAINT FK_4BDFF36B15761DAB FOREIGN KEY (competence_id) REFERENCES competence (id)');
        $this->addSql('CREATE INDEX IDX_4BDFF36B15761DAB ON niveau (competence_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE niveau_competence (niveau_id INT NOT NULL, competence_id INT NOT NULL, INDEX IDX_C058EEB215761DAB (competence_id), INDEX IDX_C058EEB2B3E9C81 (niveau_id), PRIMARY KEY(niveau_id, competence_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE niveau_competence ADD CONSTRAINT FK_C058EEB215761DAB FOREIGN KEY (competence_id) REFERENCES competence (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE niveau_competence ADD CONSTRAINT FK_C058EEB2B3E9C81 FOREIGN KEY (niveau_id) REFERENCES niveau (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE niveau DROP FOREIGN KEY FK_4BDFF36B15761DAB');
        $this->addSql('DROP INDEX IDX_4BDFF36B15761DAB ON niveau');
        $this->addSql('ALTER TABLE niveau CHANGE competence_id competences_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE niveau ADD CONSTRAINT FK_4BDFF36BA660B158 FOREIGN KEY (competences_id) REFERENCES competence (id)');
        $this->addSql('CREATE INDEX IDX_4BDFF36BA660B158 ON niveau (competences_id)');
    }
}
