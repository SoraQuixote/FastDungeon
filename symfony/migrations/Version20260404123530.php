<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260404123530 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE attaque (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(100) NOT NULL, description LONGTEXT DEFAULT NULL, pts_degat INT DEFAULT NULL, effet VARCHAR(200) DEFAULT NULL, portee VARCHAR(20) DEFAULT NULL, dtype VARCHAR(255) NOT NULL, degat_de_contre INT DEFAULT NULL, type VARCHAR(30) DEFAULT NULL, pts_de_vie INT DEFAULT NULL, type_magique_id INT DEFAULT NULL, INDEX IDX_95751B92313CAE28 (type_magique_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE campagne (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(100) NOT NULL, scenario LONGTEXT DEFAULT NULL, carte_monde VARCHAR(255) DEFAULT NULL, text_presentation LONGTEXT DEFAULT NULL, etat VARCHAR(50) NOT NULL, user_id INT NOT NULL, INDEX IDX_539B5D16A76ED395 (user_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE campagne_plateau (campagne_id INT NOT NULL, plateau_id INT NOT NULL, INDEX IDX_D8247DE116227374 (campagne_id), INDEX IDX_D8247DE1927847DB (plateau_id), PRIMARY KEY (campagne_id, plateau_id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE personnage_objet (personnage_id INT NOT NULL, objet_id INT NOT NULL, INDEX IDX_EC9E40025E315342 (personnage_id), INDEX IDX_EC9E4002F520CF5A (objet_id), PRIMARY KEY (personnage_id, objet_id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE personnage_attaque (personnage_id INT NOT NULL, attaque_id INT NOT NULL, INDEX IDX_B96A23935E315342 (personnage_id), INDEX IDX_B96A2393118FE712 (attaque_id), PRIMARY KEY (personnage_id, attaque_id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE personnage_campagne (personnage_id INT NOT NULL, campagne_id INT NOT NULL, INDEX IDX_24B072B85E315342 (personnage_id), INDEX IDX_24B072B816227374 (campagne_id), PRIMARY KEY (personnage_id, campagne_id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE plateau (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(100) NOT NULL, tuile VARCHAR(255) DEFAULT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE pnj (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(100) NOT NULL, prenom VARCHAR(100) NOT NULL, type VARCHAR(50) NOT NULL, description VARCHAR(255) DEFAULT NULL, histoire LONGTEXT DEFAULT NULL, inventaire LONGTEXT DEFAULT NULL, armure_id INT DEFAULT NULL, arme_id INT DEFAULT NULL, INDEX IDX_FDA97F2DE4000E4F (armure_id), INDEX IDX_FDA97F2D21D9C0A (arme_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE pnj_objet (pnj_id INT NOT NULL, objet_id INT NOT NULL, INDEX IDX_813A775E51796E0B (pnj_id), INDEX IDX_813A775EF520CF5A (objet_id), PRIMARY KEY (pnj_id, objet_id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE pnj_attaque (pnj_id INT NOT NULL, attaque_id INT NOT NULL, INDEX IDX_17D9F96051796E0B (pnj_id), INDEX IDX_17D9F960118FE712 (attaque_id), PRIMARY KEY (pnj_id, attaque_id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE pnj_campagne (pnj_id INT NOT NULL, campagne_id INT NOT NULL, INDEX IDX_AA62C451796E0B (pnj_id), INDEX IDX_AA62C416227374 (campagne_id), PRIMARY KEY (pnj_id, campagne_id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE `role` (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(30) NOT NULL, UNIQUE INDEX UNIQ_57698A6AA4D60759 (libelle), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE type_magique (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(100) NOT NULL, type VARCHAR(50) DEFAULT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE user_role (user_id INT NOT NULL, role_id INT NOT NULL, INDEX IDX_2DE8C6A3A76ED395 (user_id), INDEX IDX_2DE8C6A3D60322AC (role_id), PRIMARY KEY (user_id, role_id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE attaque ADD CONSTRAINT FK_95751B92313CAE28 FOREIGN KEY (type_magique_id) REFERENCES type_magique (id)');
        $this->addSql('ALTER TABLE campagne ADD CONSTRAINT FK_539B5D16A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE campagne_plateau ADD CONSTRAINT FK_D8247DE116227374 FOREIGN KEY (campagne_id) REFERENCES campagne (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE campagne_plateau ADD CONSTRAINT FK_D8247DE1927847DB FOREIGN KEY (plateau_id) REFERENCES plateau (id) ON DELETE CASCADE');
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
        $this->addSql('ALTER TABLE personnage ADD armure_id INT DEFAULT NULL, ADD arme_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE personnage ADD CONSTRAINT FK_6AEA486DA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE personnage ADD CONSTRAINT FK_6AEA486DE4000E4F FOREIGN KEY (armure_id) REFERENCES armure (id)');
        $this->addSql('ALTER TABLE personnage ADD CONSTRAINT FK_6AEA486D21D9C0A FOREIGN KEY (arme_id) REFERENCES arme (id)');
        $this->addSql('CREATE INDEX IDX_6AEA486DE4000E4F ON personnage (armure_id)');
        $this->addSql('CREATE INDEX IDX_6AEA486D21D9C0A ON personnage (arme_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE attaque DROP FOREIGN KEY FK_95751B92313CAE28');
        $this->addSql('ALTER TABLE campagne DROP FOREIGN KEY FK_539B5D16A76ED395');
        $this->addSql('ALTER TABLE campagne_plateau DROP FOREIGN KEY FK_D8247DE116227374');
        $this->addSql('ALTER TABLE campagne_plateau DROP FOREIGN KEY FK_D8247DE1927847DB');
        $this->addSql('ALTER TABLE personnage_objet DROP FOREIGN KEY FK_EC9E40025E315342');
        $this->addSql('ALTER TABLE personnage_objet DROP FOREIGN KEY FK_EC9E4002F520CF5A');
        $this->addSql('ALTER TABLE personnage_attaque DROP FOREIGN KEY FK_B96A23935E315342');
        $this->addSql('ALTER TABLE personnage_attaque DROP FOREIGN KEY FK_B96A2393118FE712');
        $this->addSql('ALTER TABLE personnage_campagne DROP FOREIGN KEY FK_24B072B85E315342');
        $this->addSql('ALTER TABLE personnage_campagne DROP FOREIGN KEY FK_24B072B816227374');
        $this->addSql('ALTER TABLE pnj DROP FOREIGN KEY FK_FDA97F2DE4000E4F');
        $this->addSql('ALTER TABLE pnj DROP FOREIGN KEY FK_FDA97F2D21D9C0A');
        $this->addSql('ALTER TABLE pnj_objet DROP FOREIGN KEY FK_813A775E51796E0B');
        $this->addSql('ALTER TABLE pnj_objet DROP FOREIGN KEY FK_813A775EF520CF5A');
        $this->addSql('ALTER TABLE pnj_attaque DROP FOREIGN KEY FK_17D9F96051796E0B');
        $this->addSql('ALTER TABLE pnj_attaque DROP FOREIGN KEY FK_17D9F960118FE712');
        $this->addSql('ALTER TABLE pnj_campagne DROP FOREIGN KEY FK_AA62C451796E0B');
        $this->addSql('ALTER TABLE pnj_campagne DROP FOREIGN KEY FK_AA62C416227374');
        $this->addSql('ALTER TABLE user_role DROP FOREIGN KEY FK_2DE8C6A3A76ED395');
        $this->addSql('ALTER TABLE user_role DROP FOREIGN KEY FK_2DE8C6A3D60322AC');
        $this->addSql('DROP TABLE attaque');
        $this->addSql('DROP TABLE campagne');
        $this->addSql('DROP TABLE campagne_plateau');
        $this->addSql('DROP TABLE personnage_objet');
        $this->addSql('DROP TABLE personnage_attaque');
        $this->addSql('DROP TABLE personnage_campagne');
        $this->addSql('DROP TABLE plateau');
        $this->addSql('DROP TABLE pnj');
        $this->addSql('DROP TABLE pnj_objet');
        $this->addSql('DROP TABLE pnj_attaque');
        $this->addSql('DROP TABLE pnj_campagne');
        $this->addSql('DROP TABLE `role`');
        $this->addSql('DROP TABLE type_magique');
        $this->addSql('DROP TABLE user_role');
        $this->addSql('ALTER TABLE personnage DROP FOREIGN KEY FK_6AEA486DA76ED395');
        $this->addSql('ALTER TABLE personnage DROP FOREIGN KEY FK_6AEA486DE4000E4F');
        $this->addSql('ALTER TABLE personnage DROP FOREIGN KEY FK_6AEA486D21D9C0A');
        $this->addSql('DROP INDEX IDX_6AEA486DE4000E4F ON personnage');
        $this->addSql('DROP INDEX IDX_6AEA486D21D9C0A ON personnage');
        $this->addSql('ALTER TABLE personnage DROP armure_id, DROP arme_id');
    }
}
