<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260504084413 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add a unique function index on LOWER(name) for the product table';
    }

    public function up(Schema $schema): void
    {
        // Functional index on LOWER(name) added manually instead of relying on
        // Doctrine's unique: true, because LOWER() in queries cannot use a plain
        // column index — it would cause a full table scan on every uniqueness check.
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D34A04AD5E237E06 ON product ((LOWER(name)))');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_D34A04AD5E237E06 ON product');
    }
}
