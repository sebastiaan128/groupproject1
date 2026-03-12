<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260312000001 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE Stemmen (
            Stem_ID INT AUTO_INCREMENT NOT NULL,
            Profiel_ID INT NOT NULL,
            Vraag_ID INT DEFAULT NULL,
            Antwoord_ID INT DEFAULT NULL,
            Type VARCHAR(4) NOT NULL,
            PRIMARY KEY (Stem_ID),
            UNIQUE INDEX unique_vraag_stem (Profiel_ID, Vraag_ID),
            UNIQUE INDEX unique_antwoord_stem (Profiel_ID, Antwoord_ID),
            INDEX IDX_Profiel (Profiel_ID),
            INDEX IDX_Vraag (Vraag_ID),
            INDEX IDX_Antwoord (Antwoord_ID),
            CONSTRAINT FK_Stemmen_Profiel FOREIGN KEY (Profiel_ID) REFERENCES Profiel (Profiel_ID),
            CONSTRAINT FK_Stemmen_Vraag FOREIGN KEY (Vraag_ID) REFERENCES Vragen (Vraag_ID),
            CONSTRAINT FK_Stemmen_Antwoord FOREIGN KEY (Antwoord_ID) REFERENCES Antwoorden (Antwoorden_ID)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE Stemmen');
    }
}
