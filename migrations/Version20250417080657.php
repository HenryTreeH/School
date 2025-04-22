<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250417080657 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE audit_log CHANGE loggged_at logged_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)'
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE scraped_page DROP created_at, DROP updated_at, DROP updated_by
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE audit_log CHANGE logged_at loggged_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)'
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE scraped_page ADD created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, ADD updated_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, ADD updated_by VARCHAR(255) DEFAULT NULL
        SQL);
    }
}
