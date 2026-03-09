<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260306120000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create Profiel, Vragen, Antwoorden, Tags tables and many-to-many junction tables';
    }

    public function up(Schema $schema): void
    {
        // Drop existing tables in reverse order (due to foreign keys)
        $this->addSql('SET FOREIGN_KEY_CHECKS=0');
        $this->addSql('DROP TABLE IF EXISTS `vraag_tags`');
        $this->addSql('DROP TABLE IF EXISTS `profiel_tags`');
        $this->addSql('DROP TABLE IF EXISTS `antwoorden`');
        $this->addSql('DROP TABLE IF EXISTS `vragen`');
        $this->addSql('DROP TABLE IF EXISTS `tags`');
        $this->addSql('DROP TABLE IF EXISTS `profiel`');
        $this->addSql('SET FOREIGN_KEY_CHECKS=1');

        // Create Profiel table first (no dependencies)
        $this->addSql('CREATE TABLE `profiel` (
            `Profiel_ID` INT AUTO_INCREMENT NOT NULL, 
            `Name` VARCHAR(50) NOT NULL, 
            `Email` VARCHAR(255) NOT NULL, 
            `Studie` VARCHAR(50), 
            `Jaar` INT NOT NULL, 
            `Bio` VARCHAR(250) NOT NULL, 
            PRIMARY KEY(`Profiel_ID`)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        // Create Tags table (no dependencies)
        $this->addSql('CREATE TABLE `tags` (
            `Tags_ID` INT AUTO_INCREMENT NOT NULL, 
            `naam` VARCHAR(50) NOT NULL, 
            PRIMARY KEY(`Tags_ID`)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        // Create Vragen table (depends on Profiel)
        $this->addSql('CREATE TABLE `vragen` (
            `Vraag_ID` INT AUTO_INCREMENT NOT NULL, 
            `Profiel_ID` INT NOT NULL, 
            `Titel` VARCHAR(50) NOT NULL, 
            `Beschrijving` LONGTEXT NOT NULL, 
            `Upvotes` INT, 
            `Downvotes` INT, 
            `Views` INT, 
            `Status` VARCHAR(50) NOT NULL, 
            PRIMARY KEY(`Vraag_ID`),
            KEY `IDX_vragen_profiel` (`Profiel_ID`),
            CONSTRAINT `FK_vragen_profiel` FOREIGN KEY (`Profiel_ID`) REFERENCES `profiel` (`Profiel_ID`) ON DELETE CASCADE
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        // Create Antwoorden table (depends on Vragen and Profiel)
        $this->addSql('CREATE TABLE `antwoorden` (
            `Antwoorden_ID` INT AUTO_INCREMENT NOT NULL, 
            `Vraag_ID` INT NOT NULL, 
            `Profiel_ID` INT NOT NULL, 
            `Beschrijving` LONGTEXT NOT NULL, 
            `Upvotes` INT, 
            `Downvotes` INT, 
            PRIMARY KEY(`Antwoorden_ID`),
            KEY `IDX_antwoorden_vraag` (`Vraag_ID`),
            KEY `IDX_antwoorden_profiel` (`Profiel_ID`),
            CONSTRAINT `FK_antwoorden_vraag` FOREIGN KEY (`Vraag_ID`) REFERENCES `vragen` (`Vraag_ID`) ON DELETE CASCADE,
            CONSTRAINT `FK_antwoorden_profiel` FOREIGN KEY (`Profiel_ID`) REFERENCES `profiel` (`Profiel_ID`) ON DELETE CASCADE
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        // Create Profiel_Tags junction table
        $this->addSql('CREATE TABLE `profiel_tags` (
            `Profiel_ID` INT NOT NULL, 
            `Tags_ID` INT NOT NULL, 
            PRIMARY KEY(`Profiel_ID`, `Tags_ID`),
            KEY `IDX_profiel_tags` (`Tags_ID`),
            CONSTRAINT `FK_profiel_tags_profiel` FOREIGN KEY (`Profiel_ID`) REFERENCES `profiel` (`Profiel_ID`) ON DELETE CASCADE,
            CONSTRAINT `FK_profiel_tags_tags` FOREIGN KEY (`Tags_ID`) REFERENCES `tags` (`Tags_ID`) ON DELETE CASCADE
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        // Create Vraag_Tags junction table
        $this->addSql('CREATE TABLE `vraag_tags` (
            `Vraag_ID` INT NOT NULL, 
            `Tags_ID` INT NOT NULL, 
            PRIMARY KEY(`Vraag_ID`, `Tags_ID`),
            KEY `IDX_vraag_tags` (`Tags_ID`),
            CONSTRAINT `FK_vraag_tags_vraag` FOREIGN KEY (`Vraag_ID`) REFERENCES `vragen` (`Vraag_ID`) ON DELETE CASCADE,
            CONSTRAINT `FK_vraag_tags_tags` FOREIGN KEY (`Tags_ID`) REFERENCES `tags` (`Tags_ID`) ON DELETE CASCADE
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        // Drop in reverse order due to foreign keys
        $this->addSql('SET FOREIGN_KEY_CHECKS=0');
        $this->addSql('DROP TABLE IF EXISTS `vraag_tags`');
        $this->addSql('DROP TABLE IF EXISTS `profiel_tags`');
        $this->addSql('DROP TABLE IF EXISTS `antwoorden`');
        $this->addSql('DROP TABLE IF EXISTS `vragen`');
        $this->addSql('DROP TABLE IF EXISTS `tags`');
        $this->addSql('DROP TABLE IF EXISTS `profiel`');
        $this->addSql('SET FOREIGN_KEY_CHECKS=1');
    }
}
