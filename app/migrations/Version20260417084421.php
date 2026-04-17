<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260417084421 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE contractor (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE delivery (date DATETIME NOT NULL, number INT NOT NULL, status VARCHAR(255) NOT NULL, id INT AUTO_INCREMENT NOT NULL, contractor_id INT NOT NULL, warehouse_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_3781EC10B0265DC7 (contractor_id), INDEX IDX_3781EC105080ECDE (warehouse_id), INDEX IDX_3781EC10A76ED395 (user_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE delivery_product (quantity INT NOT NULL, status VARCHAR(255) NOT NULL, id INT AUTO_INCREMENT NOT NULL, product_id INT NOT NULL, delivery_id INT NOT NULL, INDEX IDX_D954BB734584665A (product_id), INDEX IDX_D954BB7312136921 (delivery_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE product (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE product_movement (id INT AUTO_INCREMENT NOT NULL, quantity INT NOT NULL, type VARCHAR(255) NOT NULL, warehouse_id INT NOT NULL, product_id INT NOT NULL, purchase_order_product_id INT DEFAULT NULL, delivery_product_id INT DEFAULT NULL, INDEX IDX_3F6DFF605080ECDE (warehouse_id), INDEX IDX_3F6DFF604584665A (product_id), INDEX IDX_3F6DFF60279A1B2D (purchase_order_product_id), INDEX IDX_3F6DFF605BC1F17E (delivery_product_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE `purchase_order` (date DATETIME NOT NULL, number INT NOT NULL, status VARCHAR(255) NOT NULL, id INT AUTO_INCREMENT NOT NULL, contractor_id INT NOT NULL, warehouse_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_21E210B2B0265DC7 (contractor_id), INDEX IDX_21E210B25080ECDE (warehouse_id), INDEX IDX_21E210B2A76ED395 (user_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE purchase_order_product (quantity INT NOT NULL, status VARCHAR(255) NOT NULL, id INT AUTO_INCREMENT NOT NULL, product_id INT NOT NULL, purchase_order_id INT NOT NULL, INDEX IDX_F32214F94584665A (product_id), INDEX IDX_F32214F9A45D7E6A (purchase_order_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, first_name VARCHAR(64) NOT NULL, last_name VARCHAR(64) NOT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_USERNAME (username), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE warehouse (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE warehouse_product (id INT AUTO_INCREMENT NOT NULL, quantity INT NOT NULL, warehouse_id INT NOT NULL, product_id INT NOT NULL, INDEX IDX_F4AD11D85080ECDE (warehouse_id), INDEX IDX_F4AD11D84584665A (product_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE delivery ADD CONSTRAINT FK_3781EC10B0265DC7 FOREIGN KEY (contractor_id) REFERENCES contractor (id)');
        $this->addSql('ALTER TABLE delivery ADD CONSTRAINT FK_3781EC105080ECDE FOREIGN KEY (warehouse_id) REFERENCES warehouse (id)');
        $this->addSql('ALTER TABLE delivery ADD CONSTRAINT FK_3781EC10A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE delivery_product ADD CONSTRAINT FK_D954BB734584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE delivery_product ADD CONSTRAINT FK_D954BB7312136921 FOREIGN KEY (delivery_id) REFERENCES delivery (id)');
        $this->addSql('ALTER TABLE product_movement ADD CONSTRAINT FK_3F6DFF605080ECDE FOREIGN KEY (warehouse_id) REFERENCES warehouse (id)');
        $this->addSql('ALTER TABLE product_movement ADD CONSTRAINT FK_3F6DFF604584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE product_movement ADD CONSTRAINT FK_3F6DFF60279A1B2D FOREIGN KEY (purchase_order_product_id) REFERENCES purchase_order_product (id)');
        $this->addSql('ALTER TABLE product_movement ADD CONSTRAINT FK_3F6DFF605BC1F17E FOREIGN KEY (delivery_product_id) REFERENCES delivery_product (id)');
        $this->addSql('ALTER TABLE `purchase_order` ADD CONSTRAINT FK_21E210B2B0265DC7 FOREIGN KEY (contractor_id) REFERENCES contractor (id)');
        $this->addSql('ALTER TABLE `purchase_order` ADD CONSTRAINT FK_21E210B25080ECDE FOREIGN KEY (warehouse_id) REFERENCES warehouse (id)');
        $this->addSql('ALTER TABLE `purchase_order` ADD CONSTRAINT FK_21E210B2A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE purchase_order_product ADD CONSTRAINT FK_F32214F94584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE purchase_order_product ADD CONSTRAINT FK_F32214F9A45D7E6A FOREIGN KEY (purchase_order_id) REFERENCES `purchase_order` (id)');
        $this->addSql('ALTER TABLE warehouse_product ADD CONSTRAINT FK_F4AD11D85080ECDE FOREIGN KEY (warehouse_id) REFERENCES warehouse (id)');
        $this->addSql('ALTER TABLE warehouse_product ADD CONSTRAINT FK_F4AD11D84584665A FOREIGN KEY (product_id) REFERENCES product (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE delivery DROP FOREIGN KEY FK_3781EC10B0265DC7');
        $this->addSql('ALTER TABLE delivery DROP FOREIGN KEY FK_3781EC105080ECDE');
        $this->addSql('ALTER TABLE delivery DROP FOREIGN KEY FK_3781EC10A76ED395');
        $this->addSql('ALTER TABLE delivery_product DROP FOREIGN KEY FK_D954BB734584665A');
        $this->addSql('ALTER TABLE delivery_product DROP FOREIGN KEY FK_D954BB7312136921');
        $this->addSql('ALTER TABLE product_movement DROP FOREIGN KEY FK_3F6DFF605080ECDE');
        $this->addSql('ALTER TABLE product_movement DROP FOREIGN KEY FK_3F6DFF604584665A');
        $this->addSql('ALTER TABLE product_movement DROP FOREIGN KEY FK_3F6DFF60279A1B2D');
        $this->addSql('ALTER TABLE product_movement DROP FOREIGN KEY FK_3F6DFF605BC1F17E');
        $this->addSql('ALTER TABLE `purchase_order` DROP FOREIGN KEY FK_21E210B2B0265DC7');
        $this->addSql('ALTER TABLE `purchase_order` DROP FOREIGN KEY FK_21E210B25080ECDE');
        $this->addSql('ALTER TABLE `purchase_order` DROP FOREIGN KEY FK_21E210B2A76ED395');
        $this->addSql('ALTER TABLE purchase_order_product DROP FOREIGN KEY FK_F32214F94584665A');
        $this->addSql('ALTER TABLE purchase_order_product DROP FOREIGN KEY FK_F32214F9A45D7E6A');
        $this->addSql('ALTER TABLE warehouse_product DROP FOREIGN KEY FK_F4AD11D85080ECDE');
        $this->addSql('ALTER TABLE warehouse_product DROP FOREIGN KEY FK_F4AD11D84584665A');
        $this->addSql('DROP TABLE contractor');
        $this->addSql('DROP TABLE delivery');
        $this->addSql('DROP TABLE delivery_product');
        $this->addSql('DROP TABLE product');
        $this->addSql('DROP TABLE product_movement');
        $this->addSql('DROP TABLE `purchase_order`');
        $this->addSql('DROP TABLE purchase_order_product');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE warehouse');
        $this->addSql('DROP TABLE warehouse_product');
    }
}
