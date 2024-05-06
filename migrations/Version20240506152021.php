<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240506152021 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE "category_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE "order_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE "product_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE "purchase_item_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE "user_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE "category" (id INT NOT NULL, owner_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_64C19C17E3C61F9 ON "category" (owner_id)');
        $this->addSql('CREATE TABLE "order" (id INT NOT NULL, user_id INT DEFAULT NULL, fullname VARCHAR(255) NOT NULL, adress VARCHAR(255) NOT NULL, postal_code VARCHAR(255) NOT NULL, city VARCHAR(255) NOT NULL, total INT NOT NULL, status VARCHAR(255) NOT NULL, purchased_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_F5299398A76ED395 ON "order" (user_id)');
        $this->addSql('CREATE TABLE "product" (id INT NOT NULL, category_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, price INT NOT NULL, slug VARCHAR(255) NOT NULL, main_picture VARCHAR(255) NOT NULL, short_description TEXT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_D34A04AD12469DE2 ON "product" (category_id)');
        $this->addSql('CREATE TABLE "purchase_item" (id INT NOT NULL, product_id INT DEFAULT NULL, purchase_id INT NOT NULL, product_name VARCHAR(255) NOT NULL, product_price INT NOT NULL, quantity INT NOT NULL, total INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_6FA8ED7D4584665A ON "purchase_item" (product_id)');
        $this->addSql('CREATE INDEX IDX_6FA8ED7D558FBEB9 ON "purchase_item" (purchase_id)');
        $this->addSql('CREATE TABLE "user" (id INT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON "user" (email)');
        $this->addSql('ALTER TABLE "category" ADD CONSTRAINT FK_64C19C17E3C61F9 FOREIGN KEY (owner_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "order" ADD CONSTRAINT FK_F5299398A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "product" ADD CONSTRAINT FK_D34A04AD12469DE2 FOREIGN KEY (category_id) REFERENCES "category" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "purchase_item" ADD CONSTRAINT FK_6FA8ED7D4584665A FOREIGN KEY (product_id) REFERENCES "product" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "purchase_item" ADD CONSTRAINT FK_6FA8ED7D558FBEB9 FOREIGN KEY (purchase_id) REFERENCES "order" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE "category_id_seq" CASCADE');
        $this->addSql('DROP SEQUENCE "order_id_seq" CASCADE');
        $this->addSql('DROP SEQUENCE "product_id_seq" CASCADE');
        $this->addSql('DROP SEQUENCE "purchase_item_id_seq" CASCADE');
        $this->addSql('DROP SEQUENCE "user_id_seq" CASCADE');
        $this->addSql('ALTER TABLE "category" DROP CONSTRAINT FK_64C19C17E3C61F9');
        $this->addSql('ALTER TABLE "order" DROP CONSTRAINT FK_F5299398A76ED395');
        $this->addSql('ALTER TABLE "product" DROP CONSTRAINT FK_D34A04AD12469DE2');
        $this->addSql('ALTER TABLE "purchase_item" DROP CONSTRAINT FK_6FA8ED7D4584665A');
        $this->addSql('ALTER TABLE "purchase_item" DROP CONSTRAINT FK_6FA8ED7D558FBEB9');
        $this->addSql('DROP TABLE "category"');
        $this->addSql('DROP TABLE "order"');
        $this->addSql('DROP TABLE "product"');
        $this->addSql('DROP TABLE "purchase_item"');
        $this->addSql('DROP TABLE "user"');
    }
}
