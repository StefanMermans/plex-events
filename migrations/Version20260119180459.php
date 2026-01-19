<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260119180459 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE "order" (
              id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
              quantity CHAR(36) NOT NULL
            )
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TEMPORARY TABLE __temp__release AS
            SELECT
              id,
              type,
              number,
              title,
              release_date_time,
              series_id,
              tvdb_id
            FROM
              "release"
        SQL);
        $this->addSql('DROP TABLE "release"');
        $this->addSql(<<<'SQL'
            CREATE TABLE "release" (
              id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
              type VARCHAR(255) NOT NULL,
              number INTEGER DEFAULT NULL,
              title VARCHAR(255) NOT NULL,
              release_date_time DATETIME DEFAULT NULL,
              series_id INTEGER DEFAULT NULL,
              tvdb_id BIGINT DEFAULT NULL,
              CONSTRAINT FK_9E47031D5278319C FOREIGN KEY (series_id) REFERENCES series (id) ON
              UPDATE
                NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE
            )
        SQL);
        $this->addSql(<<<'SQL'
            INSERT INTO "release" (
              id, type, number, title, release_date_time,
              series_id, tvdb_id
            )
            SELECT
              id,
              type,
              number,
              title,
              release_date_time,
              series_id,
              tvdb_id
            FROM
              __temp__release
        SQL);
        $this->addSql('DROP TABLE __temp__release');
        $this->addSql('CREATE INDEX IDX_9E47031D5278319C ON "release" (series_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE "order"');
        $this->addSql(<<<'SQL'
            CREATE TEMPORARY TABLE __temp__release AS
            SELECT
              id,
              type,
              number,
              title,
              release_date_time,
              tvdb_id,
              series_id
            FROM
              "release"
        SQL);
        $this->addSql('DROP TABLE "release"');
        $this->addSql(<<<'SQL'
            CREATE TABLE "release" (
              id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
              type VARCHAR(255) NOT NULL,
              number INTEGER DEFAULT NULL,
              title VARCHAR(255) NOT NULL,
              release_date_time DATETIME DEFAULT NULL,
              tvdb_id BIGINT DEFAULT NULL,
              series_id INTEGER NOT NULL,
              CONSTRAINT FK_9E47031D5278319C FOREIGN KEY (series_id) REFERENCES series (id) NOT DEFERRABLE INITIALLY IMMEDIATE
            )
        SQL);
        $this->addSql(<<<'SQL'
            INSERT INTO "release" (
              id, type, number, title, release_date_time,
              tvdb_id, series_id
            )
            SELECT
              id,
              type,
              number,
              title,
              release_date_time,
              tvdb_id,
              series_id
            FROM
              __temp__release
        SQL);
        $this->addSql('DROP TABLE __temp__release');
        $this->addSql('CREATE INDEX IDX_9E47031D5278319C ON "release" (series_id)');
    }
}
