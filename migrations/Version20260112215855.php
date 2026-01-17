<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260112215855 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE series (
              id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
              title VARCHAR(255) NOT NULL,
              status VARCHAR(255) NOT NULL
            )
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE "release" (
              id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
              type VARCHAR(255) NOT NULL,
              number INTEGER NOT NULL,
              title VARCHAR(255) NOT NULL,
              release_date_time DATETIME DEFAULT NULL,
              series_id INTEGER NOT NULL,
              CONSTRAINT FK_9E47031D5278319C FOREIGN KEY (series_id) REFERENCES series (id) NOT DEFERRABLE INITIALLY IMMEDIATE
            )
        SQL);
        $this->addSql('CREATE INDEX IDX_9E47031D5278319C ON "release" (series_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE "release"');
        $this->addSql('DROP TABLE series');
    }
}
