<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260306102626 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE Antwoorden (Antwoorden_ID INT AUTO_INCREMENT NOT NULL, Beschrijving TINYTEXT NOT NULL, Upvotes INT DEFAULT NULL, Downvotes INT DEFAULT NULL, Vraag_ID INT NOT NULL, Profiel_ID INT NOT NULL, INDEX IDX_D92EAA547B7DB1DA (Vraag_ID), INDEX IDX_D92EAA545D5538F9 (Profiel_ID), PRIMARY KEY (Antwoorden_ID)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE Profiel (Profiel_ID INT AUTO_INCREMENT NOT NULL, Name VARCHAR(50) NOT NULL, Email VARCHAR(255) NOT NULL, Studie VARCHAR(50) DEFAULT NULL, Jaar INT NOT NULL, Bio VARCHAR(250) NOT NULL, PRIMARY KEY (Profiel_ID)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE Profiel_Tags (Profiel_ID INT NOT NULL, Tags_ID INT NOT NULL, INDEX IDX_D3E07B6F5D5538F9 (Profiel_ID), INDEX IDX_D3E07B6FEC2C7242 (Tags_ID), PRIMARY KEY (Profiel_ID, Tags_ID)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE Tags (Tags_ID INT AUTO_INCREMENT NOT NULL, naam VARCHAR(50) NOT NULL, PRIMARY KEY (Tags_ID)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE Vragen (Vraag_ID INT AUTO_INCREMENT NOT NULL, Titel VARCHAR(50) NOT NULL, Beschrijving LONGTEXT NOT NULL, Upvotes INT DEFAULT NULL, Downvotes INT DEFAULT NULL, Views INT DEFAULT NULL, Status VARCHAR(50) NOT NULL, Profiel_ID INT NOT NULL, INDEX IDX_948567985D5538F9 (Profiel_ID), PRIMARY KEY (Vraag_ID)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE Vraag_Tags (Vraag_ID INT NOT NULL, Tags_ID INT NOT NULL, INDEX IDX_F92D11A27B7DB1DA (Vraag_ID), INDEX IDX_F92D11A2EC2C7242 (Tags_ID), PRIMARY KEY (Vraag_ID, Tags_ID)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE Antwoorden ADD CONSTRAINT FK_D92EAA547B7DB1DA FOREIGN KEY (Vraag_ID) REFERENCES Vragen (Vraag_ID)');
        $this->addSql('ALTER TABLE Antwoorden ADD CONSTRAINT FK_D92EAA545D5538F9 FOREIGN KEY (Profiel_ID) REFERENCES Profiel (Profiel_ID)');
        $this->addSql('ALTER TABLE Profiel_Tags ADD CONSTRAINT FK_D3E07B6F5D5538F9 FOREIGN KEY (Profiel_ID) REFERENCES Profiel (Profiel_ID)');
        $this->addSql('ALTER TABLE Profiel_Tags ADD CONSTRAINT FK_D3E07B6FEC2C7242 FOREIGN KEY (Tags_ID) REFERENCES Tags (Tags_ID)');
        $this->addSql('ALTER TABLE Vragen ADD CONSTRAINT FK_948567985D5538F9 FOREIGN KEY (Profiel_ID) REFERENCES Profiel (Profiel_ID)');
        $this->addSql('ALTER TABLE Vraag_Tags ADD CONSTRAINT FK_F92D11A27B7DB1DA FOREIGN KEY (Vraag_ID) REFERENCES Vragen (Vraag_ID)');
        $this->addSql('ALTER TABLE Vraag_Tags ADD CONSTRAINT FK_F92D11A2EC2C7242 FOREIGN KEY (Tags_ID) REFERENCES Tags (Tags_ID)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE Antwoorden DROP FOREIGN KEY FK_D92EAA547B7DB1DA');
        $this->addSql('ALTER TABLE Antwoorden DROP FOREIGN KEY FK_D92EAA545D5538F9');
        $this->addSql('ALTER TABLE Profiel_Tags DROP FOREIGN KEY FK_D3E07B6F5D5538F9');
        $this->addSql('ALTER TABLE Profiel_Tags DROP FOREIGN KEY FK_D3E07B6FEC2C7242');
        $this->addSql('ALTER TABLE Vragen DROP FOREIGN KEY FK_948567985D5538F9');
        $this->addSql('ALTER TABLE Vraag_Tags DROP FOREIGN KEY FK_F92D11A27B7DB1DA');
        $this->addSql('ALTER TABLE Vraag_Tags DROP FOREIGN KEY FK_F92D11A2EC2C7242');
        $this->addSql('DROP TABLE Antwoorden');
        $this->addSql('DROP TABLE Profiel');
        $this->addSql('DROP TABLE Profiel_Tags');
        $this->addSql('DROP TABLE Tags');
        $this->addSql('DROP TABLE Vragen');
        $this->addSql('DROP TABLE Vraag_Tags');
    }
}
