<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260404112547 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE arme (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(100) NOT NULL, description LONGTEXT DEFAULT NULL, bonus INT NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE armure (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(100) NOT NULL, description LONGTEXT DEFAULT NULL, bonus INT NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE objet (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(100) NOT NULL, description LONGTEXT DEFAULT NULL, pts_degat INT DEFAULT NULL, pts_de_vie INT DEFAULT NULL, effet VARCHAR(200) DEFAULT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE personnage ADD CONSTRAINT FK_6AEA486DA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE arme');
        $this->addSql('DROP TABLE armure');
        $this->addSql('DROP TABLE objet');
        $this->addSql('ALTER TABLE personnage DROP FOREIGN KEY FK_6AEA486DA76ED395');
    }
}
