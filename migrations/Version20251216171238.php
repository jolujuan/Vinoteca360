<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251216171238 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(
            "INSERT INTO public.usuario (id, nombre, apellido, email, \"contraseña\", roles) VALUES
                (1, 'Jose', 'Luis', 'jose@hotmail.com', 'password123', '[\"ROLE_USER\"]'),
                (2, 'Ana', 'Sanz', 'ana.sanz@gmail.com', 'securepass456', '[\"ROLE_USER\"]'),
                (3, 'Jonhy', 'Sanchez', 'john@outlook.com', 'pass789', '[\"ROLE_USER\"]'),
                (4, 'admin', 'admin', 'admin', 'admin', '[\"ROLE_ADMIN\"]')
                ON CONFLICT (id) DO NOTHING"
        );

        $this->addSql(
            "INSERT INTO public.vino (id, id_usuario, nombre, \"año\") VALUES
                (1, 1, 'Chardonnay', 2021),
               (2, 1, 'Cabernet Sauvignon', 2020),
               (3, 2, 'Merlot', 2019)
               ON CONFLICT (id) DO NOTHING"
        );

        $this->addSql(
            "INSERT INTO public.sensores (id, id_usuario, nombre) VALUES
               (1, 1, 'Temperatura Sensor 1'),
               (2, 2, 'Humedad Sensor 1'),
               (3, 3, 'PH Sensor 1')
               ON CONFLICT (id) DO NOTHING"
        );

        $this->addSql(
            "INSERT INTO public.mediciones (id, id_sensor, id_vino, \"año\", color, temperatura, graduacion, ph) VALUES
               (1, 1, 1, 2024, 'Amarillo', 15.5, 13, 3.8),
               (2, 2, 2, 2023, 'Rojo', 12.8, 12.5, 3.5),
               (3, 3, 3, 2025, 'Rojo Claro', 14.2, 14, 3.9)
               ON CONFLICT (id) DO NOTHING"
        );

    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
