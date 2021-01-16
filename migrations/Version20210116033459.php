<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210116033459 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE profil_de_sortie_promo (profil_de_sortie_id INT NOT NULL, promo_id INT NOT NULL, INDEX IDX_2945BAA265E0C4D3 (profil_de_sortie_id), INDEX IDX_2945BAA2D0C07AFF (promo_id), PRIMARY KEY(profil_de_sortie_id, promo_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE profil_de_sortie_promo ADD CONSTRAINT FK_2945BAA265E0C4D3 FOREIGN KEY (profil_de_sortie_id) REFERENCES profil_de_sortie (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE profil_de_sortie_promo ADD CONSTRAINT FK_2945BAA2D0C07AFF FOREIGN KEY (promo_id) REFERENCES promo (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE niveau_competence');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE niveau_competence (niveau_id INT NOT NULL, competence_id INT NOT NULL, INDEX IDX_C058EEB215761DAB (competence_id), INDEX IDX_C058EEB2B3E9C81 (niveau_id), PRIMARY KEY(niveau_id, competence_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE niveau_competence ADD CONSTRAINT FK_C058EEB215761DAB FOREIGN KEY (competence_id) REFERENCES competence (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE niveau_competence ADD CONSTRAINT FK_C058EEB2B3E9C81 FOREIGN KEY (niveau_id) REFERENCES niveau (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE profil_de_sortie_promo');
    }
}
