<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class MigrationsFile extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE city (
            id SERIAL PRIMARY KEY,
            name VARCHAR(255) NOT NULL
        )');

        $this->addSql('CREATE TABLE weather (
            id SERIAL PRIMARY KEY,
            city_id INT,
            temperature DOUBLE PRECISION NOT NULL,
            description VARCHAR(255) NOT NULL,
            CONSTRAINT fk_weather_city FOREIGN KEY (city_id) REFERENCES city (id)
        )');

        $this->addSql('CREATE TABLE "user" (
            id SERIAL PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            email VARCHAR(255) NOT NULL
        )');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE weather');
        $this->addSql('DROP TABLE city');
        $this->addSql('DROP TABLE "user"');
    }
} 