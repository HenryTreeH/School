<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250417073924 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // Add new columns for the audit trail
        $this->addSql(<<<'SQL'
        ALTER TABLE scraped_page
        ADD COLUMN created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        ADD COLUMN updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        ADD COLUMN updated_by VARCHAR(255) DEFAULT NULL;
    SQL);
    }

    public function down(Schema $schema): void
    {
        // Remove the added columns (in reverse order)
        $this->addSql(<<<'SQL'
        ALTER TABLE scraped_page
        DROP COLUMN created_at,
        DROP COLUMN updated_at,
        DROP COLUMN updated_by;
    SQL);
    }

}
