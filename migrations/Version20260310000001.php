<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260310000001 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE Profiel ADD FirebaseUid VARCHAR(128) DEFAULT NULL, ADD CreatedAt DATETIME DEFAULT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_FIREBASE_UID ON Profiel (FirebaseUid)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP INDEX UNIQ_FIREBASE_UID ON Profiel');
        $this->addSql('ALTER TABLE Profiel DROP FirebaseUid, DROP CreatedAt');
    }
}
