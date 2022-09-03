<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220804120953 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE citas (idcita INT AUTO_INCREMENT NOT NULL, fecha DATE NOT NULL, hora TIME NOT NULL, disponible TINYINT(1) NOT NULL, PRIMARY KEY(idcita)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE codigospostales (idcp INT AUTO_INCREMENT NOT NULL, provincia VARCHAR(22) NOT NULL, poblacion VARCHAR(47) NOT NULL, PRIMARY KEY(idcp)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE facultativos (idfacultativo INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(40) NOT NULL, apellido1 VARCHAR(40) NOT NULL, apellido2 VARCHAR(40) DEFAULT NULL, telefono VARCHAR(15) NOT NULL, especialidad VARCHAR(20) NOT NULL, PRIMARY KEY(idfacultativo)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE informes (idinforme INT AUTO_INCREMENT NOT NULL, fecha DATE NOT NULL, tipoinforme VARCHAR(20) NOT NULL, detalle LONGTEXT DEFAULT NULL, anexo VARCHAR(100) DEFAULT NULL, PRIMARY KEY(idinforme)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pacientes (idpaciente INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(40) NOT NULL, apellido1 VARCHAR(40) NOT NULL, apellido2 VARCHAR(40) DEFAULT NULL, telefono VARCHAR(15) NOT NULL, direccion VARCHAR(80) DEFAULT NULL, codigopostal INT DEFAULT NULL, poblacion VARCHAR(60) DEFAULT NULL, provincia VARCHAR(60) DEFAULT NULL, PRIMARY KEY(idpaciente)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE turnos (idturno INT AUTO_INCREMENT NOT NULL, fecha DATE NOT NULL, horainicio TIME NOT NULL, horafin TIME NOT NULL, turno VARCHAR(10) NOT NULL, disponible TINYINT(1) NOT NULL, PRIMARY KEY(idturno)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE vacaciones (idvacaciones INT AUTO_INCREMENT NOT NULL, fecha DATE NOT NULL, vacaciones TINYINT(1) NOT NULL, PRIMARY KEY(idvacaciones)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE citas');
        $this->addSql('DROP TABLE codigospostales');
        $this->addSql('DROP TABLE facultativos');
        $this->addSql('DROP TABLE informes');
        $this->addSql('DROP TABLE pacientes');
        $this->addSql('DROP TABLE turnos');
        $this->addSql('DROP TABLE vacaciones');
    }
}
