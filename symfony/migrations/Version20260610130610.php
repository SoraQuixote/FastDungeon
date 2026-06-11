<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260610130610 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE stat (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(100) NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('DROP TABLE personnage_attaque_special');
        $this->addSql('ALTER TABLE attaque ADD CONSTRAINT FK_95751B92313CAE28 FOREIGN KEY (type_magique_id) REFERENCES type_magique (id)');
        $this->addSql('ALTER TABLE campagne ADD CONSTRAINT FK_539B5D16A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE campagne_plateau ADD CONSTRAINT FK_D8247DE116227374 FOREIGN KEY (campagne_id) REFERENCES campagne (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE campagne_plateau ADD CONSTRAINT FK_D8247DE1927847DB FOREIGN KEY (plateau_id) REFERENCES plateau (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE personnage ADD stat_id INT DEFAULT NULL, DROP state_force, DROP state_constitution, DROP state_rapidite, DROP state_intelligence, DROP resistance_physique, DROP resistance_magique, DROP resistance_mentale');
        $this->addSql('ALTER TABLE personnage ADD CONSTRAINT FK_6AEA486DA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE personnage ADD CONSTRAINT FK_6AEA486DE4000E4F FOREIGN KEY (armure_id) REFERENCES armure (id)');
        $this->addSql('ALTER TABLE personnage ADD CONSTRAINT FK_6AEA486D21D9C0A FOREIGN KEY (arme_id) REFERENCES arme (id)');
        $this->addSql('ALTER TABLE personnage ADD CONSTRAINT FK_6AEA486D9502F0B FOREIGN KEY (stat_id) REFERENCES stat (id)');
        $this->addSql('CREATE INDEX IDX_6AEA486D9502F0B ON personnage (stat_id)');
        $this->addSql('ALTER TABLE personnage_objet ADD CONSTRAINT FK_EC9E40025E315342 FOREIGN KEY (personnage_id) REFERENCES personnage (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE personnage_objet ADD CONSTRAINT FK_EC9E4002F520CF5A FOREIGN KEY (objet_id) REFERENCES objet (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE personnage_attaque ADD CONSTRAINT FK_B96A23935E315342 FOREIGN KEY (personnage_id) REFERENCES personnage (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE personnage_attaque ADD CONSTRAINT FK_B96A2393118FE712 FOREIGN KEY (attaque_id) REFERENCES attaque (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE personnage_campagne ADD CONSTRAINT FK_24B072B85E315342 FOREIGN KEY (personnage_id) REFERENCES personnage (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE personnage_campagne ADD CONSTRAINT FK_24B072B816227374 FOREIGN KEY (campagne_id) REFERENCES campagne (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE pnj ADD CONSTRAINT FK_FDA97F2DE4000E4F FOREIGN KEY (armure_id) REFERENCES armure (id)');
        $this->addSql('ALTER TABLE pnj ADD CONSTRAINT FK_FDA97F2D21D9C0A FOREIGN KEY (arme_id) REFERENCES arme (id)');
        $this->addSql('ALTER TABLE pnj_objet ADD CONSTRAINT FK_813A775E51796E0B FOREIGN KEY (pnj_id) REFERENCES pnj (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE pnj_objet ADD CONSTRAINT FK_813A775EF520CF5A FOREIGN KEY (objet_id) REFERENCES objet (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE pnj_attaque ADD CONSTRAINT FK_17D9F96051796E0B FOREIGN KEY (pnj_id) REFERENCES pnj (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE pnj_attaque ADD CONSTRAINT FK_17D9F960118FE712 FOREIGN KEY (attaque_id) REFERENCES attaque (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE pnj_campagne ADD CONSTRAINT FK_AA62C451796E0B FOREIGN KEY (pnj_id) REFERENCES pnj (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE pnj_campagne ADD CONSTRAINT FK_AA62C416227374 FOREIGN KEY (campagne_id) REFERENCES campagne (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_role ADD CONSTRAINT FK_2DE8C6A3A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_role ADD CONSTRAINT FK_2DE8C6A3D60322AC FOREIGN KEY (role_id) REFERENCES `role` (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE personnage_attaque_special (id INT AUTO_INCREMENT NOT NULL, degats INT DEFAULT NULL, id_personnage_id INT DEFAULT NULL, id_attaque_id INT DEFAULT NULL, INDEX IDX_D3C108D4B40567C5 (id_attaque_id), INDEX IDX_D3C108D4E0198227 (id_personnage_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_0900_ai_ci` ENGINE = MyISAM COMMENT = \'\' ');
        $this->addSql('DROP TABLE stat');
        $this->addSql('ALTER TABLE attaque DROP FOREIGN KEY FK_95751B92313CAE28');
        $this->addSql('ALTER TABLE campagne DROP FOREIGN KEY FK_539B5D16A76ED395');
        $this->addSql('ALTER TABLE campagne_plateau DROP FOREIGN KEY FK_D8247DE116227374');
        $this->addSql('ALTER TABLE campagne_plateau DROP FOREIGN KEY FK_D8247DE1927847DB');
        $this->addSql('ALTER TABLE personnage DROP FOREIGN KEY FK_6AEA486DA76ED395');
        $this->addSql('ALTER TABLE personnage DROP FOREIGN KEY FK_6AEA486DE4000E4F');
        $this->addSql('ALTER TABLE personnage DROP FOREIGN KEY FK_6AEA486D21D9C0A');
        $this->addSql('ALTER TABLE personnage DROP FOREIGN KEY FK_6AEA486D9502F0B');
        $this->addSql('DROP INDEX IDX_6AEA486D9502F0B ON personnage');
        $this->addSql('ALTER TABLE personnage ADD state_force INT NOT NULL, ADD state_constitution INT NOT NULL, ADD state_rapidite INT NOT NULL, ADD state_intelligence INT NOT NULL, ADD resistance_physique INT NOT NULL, ADD resistance_magique INT NOT NULL, ADD resistance_mentale INT NOT NULL, DROP stat_id');
        $this->addSql('ALTER TABLE personnage_attaque DROP FOREIGN KEY FK_B96A23935E315342');
        $this->addSql('ALTER TABLE personnage_attaque DROP FOREIGN KEY FK_B96A2393118FE712');
        $this->addSql('ALTER TABLE personnage_campagne DROP FOREIGN KEY FK_24B072B85E315342');
        $this->addSql('ALTER TABLE personnage_campagne DROP FOREIGN KEY FK_24B072B816227374');
        $this->addSql('ALTER TABLE personnage_objet DROP FOREIGN KEY FK_EC9E40025E315342');
        $this->addSql('ALTER TABLE personnage_objet DROP FOREIGN KEY FK_EC9E4002F520CF5A');
        $this->addSql('ALTER TABLE pnj DROP FOREIGN KEY FK_FDA97F2DE4000E4F');
        $this->addSql('ALTER TABLE pnj DROP FOREIGN KEY FK_FDA97F2D21D9C0A');
        $this->addSql('ALTER TABLE pnj_attaque DROP FOREIGN KEY FK_17D9F96051796E0B');
        $this->addSql('ALTER TABLE pnj_attaque DROP FOREIGN KEY FK_17D9F960118FE712');
        $this->addSql('ALTER TABLE pnj_campagne DROP FOREIGN KEY FK_AA62C451796E0B');
        $this->addSql('ALTER TABLE pnj_campagne DROP FOREIGN KEY FK_AA62C416227374');
        $this->addSql('ALTER TABLE pnj_objet DROP FOREIGN KEY FK_813A775E51796E0B');
        $this->addSql('ALTER TABLE pnj_objet DROP FOREIGN KEY FK_813A775EF520CF5A');
        $this->addSql('ALTER TABLE user_role DROP FOREIGN KEY FK_2DE8C6A3A76ED395');
        $this->addSql('ALTER TABLE user_role DROP FOREIGN KEY FK_2DE8C6A3D60322AC');
    }
}
