<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260304081834 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE profiel (id INT AUTO_INCREMENT NOT NULL, name LONGTEXT NOT NULL, email VARCHAR(255) NOT NULL, studie VARCHAR(50) DEFAULT NULL, jaar INT NOT NULL, bio VARCHAR(255) NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE tags (id INT AUTO_INCREMENT NOT NULL, javascript TINYINT NOT NULL, html TINYINT NOT NULL, css TINYINT NOT NULL, mysql TINYINT NOT NULL, cplus TINYINT NOT NULL, c TINYINT NOT NULL, csharp TINYINT NOT NULL, java TINYINT NOT NULL, php TINYINT NOT NULL, typescript TINYINT NOT NULL, nodejs TINYINT NOT NULL, laravel TINYINT NOT NULL, react TINYINT NOT NULL, python TINYINT NOT NULL, symfony TINYINT NOT NULL, scss TINYINT NOT NULL, bootstrap TINYINT NOT NULL, tailwind TINYINT NOT NULL, rust TINYINT NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE vragen (id INT AUTO_INCREMENT NOT NULL, titel VARCHAR(50) NOT NULL, beschrijving LONGTEXT NOT NULL, answers LONGTEXT DEFAULT NULL, upvotes INT DEFAULT NULL, downvotes INT NOT NULL, views INT DEFAULT NULL, status VARCHAR(50) NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE profiel');
        $this->addSql('DROP TABLE tags');
        $this->addSql('DROP TABLE vragen');
    }
}
