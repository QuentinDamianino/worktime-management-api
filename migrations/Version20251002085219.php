<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251002085219 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE work_time (id INT AUTO_INCREMENT NOT NULL, employee_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', start_date_time DATE NOT NULL COMMENT \'(DC2Type:date_immutable)\', end_date_time DATE NOT NULL COMMENT \'(DC2Type:date_immutable)\', INDEX IDX_9657297D8C03F15C (employee_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE work_time ADD CONSTRAINT FK_9657297D8C03F15C FOREIGN KEY (employee_id) REFERENCES employee (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE work_time DROP FOREIGN KEY FK_9657297D8C03F15C');
        $this->addSql('DROP TABLE work_time');
    }
}
