<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220325095238 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE contest DROP FOREIGN KEY FK_1A95CB5FC53D4E9');
        $this->addSql('DROP INDEX IDX_1A95CB5FC53D4E9 ON contest');
        $this->addSql('ALTER TABLE contest CHANGE winner_id winner_id_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE contest ADD CONSTRAINT FK_1A95CB5FC53D4E9 FOREIGN KEY (winner_id_id) REFERENCES player (id)');
        $this->addSql('CREATE INDEX IDX_1A95CB5FC53D4E9 ON contest (winner_id_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE contest DROP FOREIGN KEY FK_1A95CB5FC53D4E9');
        $this->addSql('DROP INDEX IDX_1A95CB5FC53D4E9 ON contest');
        $this->addSql('ALTER TABLE contest CHANGE winner_id_id winner_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE contest ADD CONSTRAINT FK_1A95CB5FC53D4E9 FOREIGN KEY (winner_id) REFERENCES player (id)');
        $this->addSql('CREATE INDEX IDX_1A95CB5FC53D4E9 ON contest (winner_id)');
        $this->addSql('ALTER TABLE game CHANGE title title VARCHAR(30) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE player CHANGE email email VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE nickname nickname VARCHAR(20) NOT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
