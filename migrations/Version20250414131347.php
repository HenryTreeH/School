<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250414131347 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE scrape_log (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, config_id INT DEFAULT NULL, status VARCHAR(20) NOT NULL, message LONGTEXT DEFAULT NULL, scraper_name VARCHAR(100) NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_E14E61EFA76ED395 (user_id), INDEX IDX_E14E61EF24DB0683 (config_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE scrape_log ADD CONSTRAINT FK_E14E61EFA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE scrape_log ADD CONSTRAINT FK_E14E61EF24DB0683 FOREIGN KEY (config_id) REFERENCES scrape_config (id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE scrape_log DROP FOREIGN KEY FK_E14E61EFA76ED395
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE scrape_log DROP FOREIGN KEY FK_E14E61EF24DB0683
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE scrape_log
        SQL);
    }
}
