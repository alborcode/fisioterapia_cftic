<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220912070918 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE citas (idcita INT AUTO_INCREMENT NOT NULL, idfacultativo INT NOT NULL, idpaciente INT NOT NULL, fecha DATE NOT NULL, hora INT NOT NULL, INDEX IDX_B88CF8E5D5988285 (idfacultativo), INDEX IDX_B88CF8E56C1EE153 (idpaciente), PRIMARY KEY(idcita)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE citas_disponibles (id INT AUTO_INCREMENT NOT NULL, idfacultativo INT NOT NULL, fecha DATE NOT NULL, hora INT NOT NULL, disponible TINYINT(1) NOT NULL, INDEX IDX_AB4E2BFBD5988285 (idfacultativo), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE especialidades (idespecialidad INT NOT NULL, especialidad VARCHAR(60) NOT NULL, PRIMARY KEY(idespecialidad)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE facultativos (idfacultativo INT AUTO_INCREMENT NOT NULL, especialidad INT NOT NULL, idusuario INT NOT NULL, nombre VARCHAR(40) NOT NULL, apellido1 VARCHAR(40) NOT NULL, apellido2 VARCHAR(40) DEFAULT NULL, telefono VARCHAR(15) NOT NULL, INDEX IDX_C0A65F2FACB064F9 (especialidad), UNIQUE INDEX UNIQ_C0A65F2FFD61E233 (idusuario), PRIMARY KEY(idfacultativo)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE informes (idinforme INT AUTO_INCREMENT NOT NULL, idfacultativo INT NOT NULL, idpaciente INT NOT NULL, fecha DATE NOT NULL, tipoinforme VARCHAR(20) NOT NULL, detalle LONGTEXT DEFAULT NULL, INDEX IDX_E47FD09AD5988285 (idfacultativo), INDEX IDX_E47FD09A6C1EE153 (idpaciente), PRIMARY KEY(idinforme)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pacientes (idpaciente INT AUTO_INCREMENT NOT NULL, provincia INT NOT NULL, idusuario INT NOT NULL, nombre VARCHAR(40) NOT NULL, apellido1 VARCHAR(40) NOT NULL, apellido2 VARCHAR(40) DEFAULT NULL, telefono VARCHAR(15) NOT NULL, direccion VARCHAR(80) DEFAULT NULL, codigopostal INT DEFAULT NULL, poblacion VARCHAR(60) DEFAULT NULL, INDEX IDX_971B7851D39AF213 (provincia), UNIQUE INDEX UNIQ_971B7851FD61E233 (idusuario), PRIMARY KEY(idpaciente)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE provincias (idprovincia INT NOT NULL, provincia VARCHAR(60) NOT NULL, PRIMARY KEY(idprovincia)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reset_password_request (idusuario INT AUTO_INCREMENT NOT NULL, selector VARCHAR(20) NOT NULL, hashed_token VARCHAR(100) NOT NULL, requested_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', expires_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(idusuario)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE turnos (idturno INT AUTO_INCREMENT NOT NULL, idfacultativo INT NOT NULL, diasemana VARCHAR(10) NOT NULL, horainicio INT NOT NULL, horafin INT NOT NULL, INDEX IDX_B8555818D5988285 (idfacultativo), PRIMARY KEY(idturno)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE usuarios (idusuario INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, is_verified TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_EF687F2E7927C74 (email), PRIMARY KEY(idusuario)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE vacaciones (idvacaciones INT AUTO_INCREMENT NOT NULL, idfacultativo INT NOT NULL, fecha DATE NOT NULL, dianotrabajado TINYINT(1) NOT NULL, INDEX IDX_CAA83E94D5988285 (idfacultativo), PRIMARY KEY(idvacaciones)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE citas ADD CONSTRAINT FK_B88CF8E5D5988285 FOREIGN KEY (idfacultativo) REFERENCES facultativos (idfacultativo)');
        $this->addSql('ALTER TABLE citas ADD CONSTRAINT FK_B88CF8E56C1EE153 FOREIGN KEY (idpaciente) REFERENCES pacientes (idpaciente)');
        $this->addSql('ALTER TABLE citas_disponibles ADD CONSTRAINT FK_AB4E2BFBD5988285 FOREIGN KEY (idfacultativo) REFERENCES facultativos (idfacultativo)');
        $this->addSql('ALTER TABLE facultativos ADD CONSTRAINT FK_C0A65F2FACB064F9 FOREIGN KEY (especialidad) REFERENCES especialidades (idespecialidad)');
        $this->addSql('ALTER TABLE facultativos ADD CONSTRAINT FK_C0A65F2FFD61E233 FOREIGN KEY (idusuario) REFERENCES usuarios (idusuario)');
        $this->addSql('ALTER TABLE informes ADD CONSTRAINT FK_E47FD09AD5988285 FOREIGN KEY (idfacultativo) REFERENCES facultativos (idfacultativo)');
        $this->addSql('ALTER TABLE informes ADD CONSTRAINT FK_E47FD09A6C1EE153 FOREIGN KEY (idpaciente) REFERENCES pacientes (idpaciente)');
        $this->addSql('ALTER TABLE pacientes ADD CONSTRAINT FK_971B7851D39AF213 FOREIGN KEY (provincia) REFERENCES provincias (idprovincia)');
        $this->addSql('ALTER TABLE pacientes ADD CONSTRAINT FK_971B7851FD61E233 FOREIGN KEY (idusuario) REFERENCES usuarios (idusuario)');
        $this->addSql('ALTER TABLE reset_password_request ADD CONSTRAINT FK_7CE748AFD61E233 FOREIGN KEY (idusuario) REFERENCES usuarios (idusuario)');
        $this->addSql('ALTER TABLE turnos ADD CONSTRAINT FK_B8555818D5988285 FOREIGN KEY (idfacultativo) REFERENCES facultativos (idfacultativo)');
        $this->addSql('ALTER TABLE vacaciones ADD CONSTRAINT FK_CAA83E94D5988285 FOREIGN KEY (idfacultativo) REFERENCES facultativos (idfacultativo)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE facultativos DROP FOREIGN KEY FK_C0A65F2FACB064F9');
        $this->addSql('ALTER TABLE citas DROP FOREIGN KEY FK_B88CF8E5D5988285');
        $this->addSql('ALTER TABLE citas_disponibles DROP FOREIGN KEY FK_AB4E2BFBD5988285');
        $this->addSql('ALTER TABLE informes DROP FOREIGN KEY FK_E47FD09AD5988285');
        $this->addSql('ALTER TABLE turnos DROP FOREIGN KEY FK_B8555818D5988285');
        $this->addSql('ALTER TABLE vacaciones DROP FOREIGN KEY FK_CAA83E94D5988285');
        $this->addSql('ALTER TABLE citas DROP FOREIGN KEY FK_B88CF8E56C1EE153');
        $this->addSql('ALTER TABLE informes DROP FOREIGN KEY FK_E47FD09A6C1EE153');
        $this->addSql('ALTER TABLE pacientes DROP FOREIGN KEY FK_971B7851D39AF213');
        $this->addSql('ALTER TABLE facultativos DROP FOREIGN KEY FK_C0A65F2FFD61E233');
        $this->addSql('ALTER TABLE pacientes DROP FOREIGN KEY FK_971B7851FD61E233');
        $this->addSql('ALTER TABLE reset_password_request DROP FOREIGN KEY FK_7CE748AFD61E233');
        $this->addSql('DROP TABLE citas');
        $this->addSql('DROP TABLE citas_disponibles');
        $this->addSql('DROP TABLE especialidades');
        $this->addSql('DROP TABLE facultativos');
        $this->addSql('DROP TABLE informes');
        $this->addSql('DROP TABLE pacientes');
        $this->addSql('DROP TABLE provincias');
        $this->addSql('DROP TABLE reset_password_request');
        $this->addSql('DROP TABLE turnos');
        $this->addSql('DROP TABLE usuarios');
        $this->addSql('DROP TABLE vacaciones');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
