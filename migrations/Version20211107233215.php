<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211107233215 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE weather_hourly_records_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE weather_hourly_records (id INT NOT NULL, country VARCHAR(255) DEFAULT NULL, city VARCHAR(255) DEFAULT NULL, measure_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, temperature DOUBLE PRECISION NOT NULL, humidity DOUBLE PRECISION NOT NULL, rain DOUBLE PRECISION NOT NULL, wind DOUBLE PRECISION NOT NULL, light DOUBLE PRECISION DEFAULT NULL, battery_level VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN weather_hourly_records.measure_at IS \'(DC2Type:datetime_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE weather_hourly_records_id_seq CASCADE');
        $this->addSql('DROP TABLE weather_hourly_records');
    }
}
