<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260316092204 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Align index and constraint names with Doctrine auto-generated names';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE vragen DROP FOREIGN KEY FK_vragen_profiel');
        $this->addSql('ALTER TABLE vragen ADD CONSTRAINT FK_948567985D5538F9 FOREIGN KEY (Profiel_ID) REFERENCES Profiel (Profiel_ID)');
        $this->addSql('ALTER TABLE vragen RENAME INDEX idx_vragen_profiel TO IDX_948567985D5538F9');
        $this->addSql('ALTER TABLE vraag_tags DROP FOREIGN KEY FK_vraag_tags_tags');
        $this->addSql('ALTER TABLE vraag_tags DROP FOREIGN KEY FK_vraag_tags_vraag');
        $this->addSql('ALTER TABLE vraag_tags ADD CONSTRAINT FK_F92D11A27B7DB1DA FOREIGN KEY (Vraag_ID) REFERENCES Vragen (Vraag_ID)');
        $this->addSql('ALTER TABLE vraag_tags ADD CONSTRAINT FK_F92D11A2EC2C7242 FOREIGN KEY (Tags_ID) REFERENCES Tags (Tags_ID)');
        $this->addSql('ALTER TABLE vraag_tags RENAME INDEX idx_1642bd4c7b7db1da TO IDX_F92D11A27B7DB1DA');
        $this->addSql('ALTER TABLE vraag_tags RENAME INDEX idx_vraag_tags TO IDX_F92D11A2EC2C7242');
        $this->addSql('ALTER TABLE profiel CHANGE Studie Studie VARCHAR(50) DEFAULT NULL, CHANGE FirebaseUid FirebaseUid VARCHAR(128) DEFAULT NULL, CHANGE CreatedAt CreatedAt DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE profiel RENAME INDEX uniq_firebase_uid TO UNIQ_E6F4907EC74A8D9F');
        $this->addSql('ALTER TABLE profiel_tags DROP FOREIGN KEY FK_profiel_tags_profiel');
        $this->addSql('ALTER TABLE profiel_tags DROP FOREIGN KEY FK_profiel_tags_tags');
        $this->addSql('ALTER TABLE profiel_tags ADD CONSTRAINT FK_D3E07B6F5D5538F9 FOREIGN KEY (Profiel_ID) REFERENCES Profiel (Profiel_ID)');
        $this->addSql('ALTER TABLE profiel_tags ADD CONSTRAINT FK_D3E07B6FEC2C7242 FOREIGN KEY (Tags_ID) REFERENCES Tags (Tags_ID)');
        $this->addSql('ALTER TABLE profiel_tags RENAME INDEX idx_6be30fa95d5538f9 TO IDX_D3E07B6F5D5538F9');
        $this->addSql('ALTER TABLE profiel_tags RENAME INDEX idx_profiel_tags TO IDX_D3E07B6FEC2C7242');
        $this->addSql('ALTER TABLE Stemmen RENAME INDEX idx_profiel TO IDX_75F33FAE5D5538F9');
        $this->addSql('ALTER TABLE Stemmen RENAME INDEX idx_vraag TO IDX_75F33FAE7B7DB1DA');
        $this->addSql('ALTER TABLE Stemmen RENAME INDEX idx_antwoord TO IDX_75F33FAE3C550360');
        $this->addSql('ALTER TABLE antwoorden DROP FOREIGN KEY FK_antwoorden_profiel');
        $this->addSql('ALTER TABLE antwoorden DROP FOREIGN KEY FK_antwoorden_vraag');
        $this->addSql('ALTER TABLE antwoorden CHANGE Beschrijving Beschrijving TINYTEXT NOT NULL');
        $this->addSql('ALTER TABLE antwoorden ADD CONSTRAINT FK_D92EAA547B7DB1DA FOREIGN KEY (Vraag_ID) REFERENCES Vragen (Vraag_ID)');
        $this->addSql('ALTER TABLE antwoorden ADD CONSTRAINT FK_D92EAA545D5538F9 FOREIGN KEY (Profiel_ID) REFERENCES Profiel (Profiel_ID)');
        $this->addSql('ALTER TABLE antwoorden RENAME INDEX idx_antwoorden_vraag TO IDX_D92EAA547B7DB1DA');
        $this->addSql('ALTER TABLE antwoorden RENAME INDEX idx_antwoorden_profiel TO IDX_D92EAA545D5538F9');
        $this->addSql('ALTER TABLE Notificatie RENAME INDEX idx_notificatie_profiel TO IDX_ED34CAB25D5538F9');
        $this->addSql('ALTER TABLE Notificatie RENAME INDEX idx_notificatie_vraag TO IDX_ED34CAB27B7DB1DA');
    }

    public function down(Schema $schema): void
    {
    }
}
