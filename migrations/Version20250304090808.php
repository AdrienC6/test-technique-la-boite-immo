<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250304090808 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE export (id SERIAL NOT NULL, property_id INT NOT NULL, gateway_id INT NOT NULL, status VARCHAR(255) NOT NULL, external_id VARCHAR(255) DEFAULT NULL, response JSON DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_428C1694549213EC ON export (property_id)');
        $this->addSql('CREATE INDEX IDX_428C1694577F8E00 ON export (gateway_id)');
        $this->addSql('COMMENT ON COLUMN export.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN export.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE gateway (id SERIAL NOT NULL, name VARCHAR(255) NOT NULL, code VARCHAR(100) NOT NULL, active BOOLEAN NOT NULL, config JSON NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_14FEDD7F77153098 ON gateway (code)');
        $this->addSql('COMMENT ON COLUMN gateway.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN gateway.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE property (id SERIAL NOT NULL, title VARCHAR(255) NOT NULL, description TEXT DEFAULT NULL, price DOUBLE PRECISION NOT NULL, address VARCHAR(255) DEFAULT NULL, city VARCHAR(255) DEFAULT NULL, zip_code VARCHAR(20) DEFAULT NULL, country VARCHAR(2) DEFAULT NULL, surface DOUBLE PRECISION DEFAULT NULL, number_of_rooms INT DEFAULT NULL, property_type VARCHAR(50) NOT NULL, is_published BOOLEAN NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN property.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN property.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE export ADD CONSTRAINT FK_428C1694549213EC FOREIGN KEY (property_id) REFERENCES property (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE export ADD CONSTRAINT FK_428C1694577F8E00 FOREIGN KEY (gateway_id) REFERENCES gateway (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE export DROP CONSTRAINT FK_428C1694549213EC');
        $this->addSql('ALTER TABLE export DROP CONSTRAINT FK_428C1694577F8E00');
        $this->addSql('DROP TABLE export');
        $this->addSql('DROP TABLE gateway');
        $this->addSql('DROP TABLE property');
    }
}
