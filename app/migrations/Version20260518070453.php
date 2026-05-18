<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260518070453 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE UNIQUE INDEX UNIQ_IDENTIFIER_PRODUCT_ID_DELIVERY_ID ON delivery_product (product_id, delivery_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_IDENTIFIER_PRODUCT_ID_PURCHASE_ORDER_ID ON purchase_order_product (product_id, purchase_order_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_IDENTIFIER_PRODUCT_ID_DELIVERY_ID ON delivery_product');
        $this->addSql('DROP INDEX UNIQ_IDENTIFIER_PRODUCT_ID_PURCHASE_ORDER_ID ON purchase_order_product');
    }
}
