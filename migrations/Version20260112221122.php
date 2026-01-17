<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260112221122 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE release_user (
              id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
              watched_at DATETIME DEFAULT NULL,
              user_id INTEGER NOT NULL,
              series_release_id INTEGER NOT NULL,
              CONSTRAINT FK_C064665EA76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE,
              CONSTRAINT FK_C064665EC866262F FOREIGN KEY (series_release_id) REFERENCES "release" (id) NOT DEFERRABLE INITIALLY IMMEDIATE
            )
        SQL);
        $this->addSql('CREATE INDEX IDX_C064665EA76ED395 ON release_user (user_id)');
        $this->addSql('CREATE INDEX IDX_C064665EC866262F ON release_user (series_release_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE release_user');
    }
}
