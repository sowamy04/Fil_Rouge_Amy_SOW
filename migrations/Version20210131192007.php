<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210131192007 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE promo_referentiel (promo_id INT NOT NULL, referentiel_id INT NOT NULL, INDEX IDX_638B8B6BD0C07AFF (promo_id), INDEX IDX_638B8B6B805DB139 (referentiel_id), PRIMARY KEY(promo_id, referentiel_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE promo_referentiel ADD CONSTRAINT FK_638B8B6BD0C07AFF FOREIGN KEY (promo_id) REFERENCES promo (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE promo_referentiel ADD CONSTRAINT FK_638B8B6B805DB139 FOREIGN KEY (referentiel_id) REFERENCES referentiel (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE niveau_competence');
        $this->addSql('ALTER TABLE promo DROP FOREIGN KEY FK_B0139AFBB8F4689C');
        $this->addSql('DROP INDEX IDX_B0139AFBB8F4689C ON promo');
        $this->addSql('ALTER TABLE promo ADD langue VARCHAR(255) NOT NULL, ADD lieu VARCHAR(255) NOT NULL, ADD referent_agate VARCHAR(255) NOT NULL, ADD fabrique VARCHAR(255) NOT NULL, ADD date_debut DATE NOT NULL, ADD date_fin DATE NOT NULL, DROP referentiels_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE niveau_competence (niveau_id INT NOT NULL, competence_id INT NOT NULL, INDEX IDX_C058EEB2B3E9C81 (niveau_id), INDEX IDX_C058EEB215761DAB (competence_id), PRIMARY KEY(niveau_id, competence_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE niveau_competence ADD CONSTRAINT FK_C058EEB215761DAB FOREIGN KEY (competence_id) REFERENCES competence (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE niveau_competence ADD CONSTRAINT FK_C058EEB2B3E9C81 FOREIGN KEY (niveau_id) REFERENCES niveau (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE promo_referentiel');
        $this->addSql('ALTER TABLE promo ADD referentiels_id INT DEFAULT NULL, DROP langue, DROP lieu, DROP referent_agate, DROP fabrique, DROP date_debut, DROP date_fin');
        $this->addSql('ALTER TABLE promo ADD CONSTRAINT FK_B0139AFBB8F4689C FOREIGN KEY (referentiels_id) REFERENCES referentiel (id)');
        $this->addSql('CREATE INDEX IDX_B0139AFBB8F4689C ON promo (referentiels_id)');
    }
}
