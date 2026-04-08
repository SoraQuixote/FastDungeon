<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260403164426 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE arme');
        $this->addSql('DROP TABLE parhemin');
        $this->addSql('ALTER TABLE personnage ADD personnage_user VARCHAR(100) DEFAULT NULL, ADD point_de_vie INT NOT NULL, ADD histoire LONGTEXT DEFAULT NULL, ADD niveau INT NOT NULL, ADD state_force INT NOT NULL, ADD state_constitution INT NOT NULL, ADD state_rapidite INT NOT NULL, ADD state_intelligence INT NOT NULL, ADD resistance_physique INT NOT NULL, ADD resistance_magique INT NOT NULL, ADD resistance_mentale INT NOT NULL, DROP nom_image, CHANGE inventaire inventaire LONGTEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE personnage ADD CONSTRAINT FK_6AEA486DA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE arme (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(100) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_0900_ai_ci`, type_degat VARCHAR(50) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_0900_ai_ci`, degat DOUBLE PRECISION DEFAULT NULL, effet VARCHAR(50) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_0900_ai_ci`, rarete VARCHAR(50) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_0900_ai_ci`, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_0900_ai_ci` ENGINE = MyISAM COMMENT = \'\' ');
        $this->addSql('CREATE TABLE parhemin (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(100) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_0900_ai_ci`, type VARCHAR(50) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_0900_ai_ci`, degat DOUBLE PRECISION DEFAULT NULL, effet VARCHAR(100) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_0900_ai_ci`, rarete VARCHAR(50) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_0900_ai_ci`, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_0900_ai_ci` ENGINE = MyISAM COMMENT = \'\' ');
        $this->addSql('ALTER TABLE personnage DROP FOREIGN KEY FK_6AEA486DA76ED395');
        $this->addSql('ALTER TABLE personnage ADD nom_image VARCHAR(255) DEFAULT NULL, DROP personnage_user, DROP point_de_vie, DROP histoire, DROP niveau, DROP state_force, DROP state_constitution, DROP state_rapidite, DROP state_intelligence, DROP resistance_physique, DROP resistance_magique, DROP resistance_mentale, CHANGE inventaire inventaire LONGTEXT NOT NULL');
    }
}
