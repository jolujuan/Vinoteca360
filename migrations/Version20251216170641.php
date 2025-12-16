<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251216170641 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE mediciones (id SERIAL NOT NULL, id_sensor INT NOT NULL, id_vino INT NOT NULL, año INT NOT NULL, color VARCHAR(15) NOT NULL, temperatura DOUBLE PRECISION NOT NULL, graduacion DOUBLE PRECISION NOT NULL, ph DOUBLE PRECISION NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_712052F99AB1A25D ON mediciones (id_sensor)');
        $this->addSql('CREATE INDEX IDX_712052F98545F611 ON mediciones (id_vino)');
        $this->addSql('CREATE TABLE sensores (id SERIAL NOT NULL, id_usuario INT NOT NULL, nombre VARCHAR(50) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_F7493A62FCF8192D ON sensores (id_usuario)');
        $this->addSql('CREATE TABLE usuario (id SERIAL NOT NULL, nombre VARCHAR(50) NOT NULL, apellido VARCHAR(50) NOT NULL, email VARCHAR(50) NOT NULL, contraseña VARCHAR(255) NOT NULL, roles JSON NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE vino (id SERIAL NOT NULL, id_usuario INT NOT NULL, nombre VARCHAR(50) NOT NULL, año INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_E65EA13FCF8192D ON vino (id_usuario)');
        $this->addSql('ALTER TABLE mediciones ADD CONSTRAINT FK_712052F99AB1A25D FOREIGN KEY (id_sensor) REFERENCES sensores (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE mediciones ADD CONSTRAINT FK_712052F98545F611 FOREIGN KEY (id_vino) REFERENCES vino (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE sensores ADD CONSTRAINT FK_F7493A62FCF8192D FOREIGN KEY (id_usuario) REFERENCES usuario (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE vino ADD CONSTRAINT FK_E65EA13FCF8192D FOREIGN KEY (id_usuario) REFERENCES usuario (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE mediciones DROP CONSTRAINT FK_712052F99AB1A25D');
        $this->addSql('ALTER TABLE mediciones DROP CONSTRAINT FK_712052F98545F611');
        $this->addSql('ALTER TABLE sensores DROP CONSTRAINT FK_F7493A62FCF8192D');
        $this->addSql('ALTER TABLE vino DROP CONSTRAINT FK_E65EA13FCF8192D');
        $this->addSql('DROP TABLE mediciones');
        $this->addSql('DROP TABLE sensores');
        $this->addSql('DROP TABLE usuario');
        $this->addSql('DROP TABLE vino');
    }
}
