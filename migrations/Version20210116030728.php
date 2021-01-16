<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210116030728 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE profil_sortie_promo DROP FOREIGN KEY FK_5F9B40766409EF73');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6496409EF73');
        $this->addSql('DROP TABLE niveau_competence');
        $this->addSql('DROP TABLE profil_sortie');
        $this->addSql('DROP TABLE profil_sortie_promo');
        $this->addSql('DROP INDEX IDX_8D93D6496409EF73 ON user');
        $this->addSql('ALTER TABLE user DROP profil_sortie_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE niveau_competence (niveau_id INT NOT NULL, competence_id INT NOT NULL, INDEX IDX_C058EEB215761DAB (competence_id), INDEX IDX_C058EEB2B3E9C81 (niveau_id), PRIMARY KEY(niveau_id, competence_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE profil_sortie (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE profil_sortie_promo (profil_sortie_id INT NOT NULL, promo_id INT NOT NULL, INDEX IDX_5F9B4076D0C07AFF (promo_id), INDEX IDX_5F9B40766409EF73 (profil_sortie_id), PRIMARY KEY(profil_sortie_id, promo_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE niveau_competence ADD CONSTRAINT FK_C058EEB215761DAB FOREIGN KEY (competence_id) REFERENCES competence (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE niveau_competence ADD CONSTRAINT FK_C058EEB2B3E9C81 FOREIGN KEY (niveau_id) REFERENCES niveau (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE profil_sortie_promo ADD CONSTRAINT FK_5F9B40766409EF73 FOREIGN KEY (profil_sortie_id) REFERENCES profil_sortie (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE profil_sortie_promo ADD CONSTRAINT FK_5F9B4076D0C07AFF FOREIGN KEY (promo_id) REFERENCES promo (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user ADD profil_sortie_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6496409EF73 FOREIGN KEY (profil_sortie_id) REFERENCES profil_sortie (id)');
        $this->addSql('CREATE INDEX IDX_8D93D6496409EF73 ON user (profil_sortie_id)');
    }
}
