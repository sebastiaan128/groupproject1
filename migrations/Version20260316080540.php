<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260316080540 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create Notificatie table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE Notificatie (
            Notificatie_ID INT AUTO_INCREMENT NOT NULL,
            Profiel_ID INT NOT NULL,
            Vraag_ID INT NOT NULL,
            Bericht VARCHAR(255) NOT NULL,
            IsGelezen TINYINT(1) NOT NULL DEFAULT 0,
            AangemaaktOp DATETIME NOT NULL,
            PRIMARY KEY (Notificatie_ID),
            INDEX IDX_Notificatie_Profiel (Profiel_ID),
            INDEX IDX_Notificatie_Vraag (Vraag_ID),
            CONSTRAINT FK_Notificatie_Profiel FOREIGN KEY (Profiel_ID) REFERENCES profiel (Profiel_ID) ON DELETE CASCADE,
            CONSTRAINT FK_Notificatie_Vraag FOREIGN KEY (Vraag_ID) REFERENCES vragen (Vraag_ID) ON DELETE CASCADE
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE Notificatie');
    }
}
