<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231016130434 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE boardgame (id INT AUTO_INCREMENT NOT NULL, categorie_id INT NOT NULL, owner_id INT NOT NULL, name VARCHAR(100) NOT NULL, year_release INT NOT NULL, nbr_players INT NOT NULL, age_min INT NOT NULL, duration INT NOT NULL, img VARCHAR(100) NOT NULL, INDEX IDX_98A1DB1DBCF5E72D (categorie_id), INDEX IDX_98A1DB1D7E3C61F9 (owner_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE categories (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE comments (id INT AUTO_INCREMENT NOT NULL, writer_id INT NOT NULL, game_id INT NOT NULL, comment LONGTEXT NOT NULL, date DATETIME NOT NULL, INDEX IDX_5F9E962A1BC7E6B6 (writer_id), INDEX IDX_5F9E962AE48FD905 (game_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reservations (id INT AUTO_INCREMENT NOT NULL, game_id INT NOT NULL, rental_id INT NOT NULL, start_rent DATETIME NOT NULL, end_rent DATETIME NOT NULL, INDEX IDX_4DA239E48FD905 (game_id), INDEX IDX_4DA239A7CF2329 (rental_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `user` (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE boardgame ADD CONSTRAINT FK_98A1DB1DBCF5E72D FOREIGN KEY (categorie_id) REFERENCES categories (id)');
        $this->addSql('ALTER TABLE boardgame ADD CONSTRAINT FK_98A1DB1D7E3C61F9 FOREIGN KEY (owner_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE comments ADD CONSTRAINT FK_5F9E962A1BC7E6B6 FOREIGN KEY (writer_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE comments ADD CONSTRAINT FK_5F9E962AE48FD905 FOREIGN KEY (game_id) REFERENCES boardgame (id)');
        $this->addSql('ALTER TABLE reservations ADD CONSTRAINT FK_4DA239E48FD905 FOREIGN KEY (game_id) REFERENCES boardgame (id)');
        $this->addSql('ALTER TABLE reservations ADD CONSTRAINT FK_4DA239A7CF2329 FOREIGN KEY (rental_id) REFERENCES `user` (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE boardgame DROP FOREIGN KEY FK_98A1DB1DBCF5E72D');
        $this->addSql('ALTER TABLE boardgame DROP FOREIGN KEY FK_98A1DB1D7E3C61F9');
        $this->addSql('ALTER TABLE comments DROP FOREIGN KEY FK_5F9E962A1BC7E6B6');
        $this->addSql('ALTER TABLE comments DROP FOREIGN KEY FK_5F9E962AE48FD905');
        $this->addSql('ALTER TABLE reservations DROP FOREIGN KEY FK_4DA239E48FD905');
        $this->addSql('ALTER TABLE reservations DROP FOREIGN KEY FK_4DA239A7CF2329');
        $this->addSql('DROP TABLE boardgame');
        $this->addSql('DROP TABLE categories');
        $this->addSql('DROP TABLE comments');
        $this->addSql('DROP TABLE reservations');
        $this->addSql('DROP TABLE `user`');
        $this->addSql('DROP TABLE messenger_messages');
    }
}