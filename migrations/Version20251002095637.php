<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251002095637 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE work_time CHANGE start_date_time start_date_time DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', CHANGE end_date_time end_date_time DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE work_time CHANGE start_date_time start_date_time DATE NOT NULL COMMENT \'(DC2Type:date_immutable)\', CHANGE end_date_time end_date_time DATE NOT NULL COMMENT \'(DC2Type:date_immutable)\'');
    }
}
