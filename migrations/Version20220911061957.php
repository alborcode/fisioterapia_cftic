<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220911061957 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE citas_disponibles (id INT AUTO_INCREMENT NOT NULL, idfacultativo INT NOT NULL, fecha DATE NOT NULL, hora TIME NOT NULL, disponible TINYINT(1) NOT NULL, INDEX IDX_AB4E2BFBD5988285 (idfacultativo), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reset_password_request (idusuario INT AUTO_INCREMENT NOT NULL, selector VARCHAR(20) NOT NULL, hashed_token VARCHAR(100) NOT NULL, requested_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', expires_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(idusuario)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE citas_disponibles ADD CONSTRAINT FK_AB4E2BFBD5988285 FOREIGN KEY (idfacultativo) REFERENCES facultativos (idfacultativo)');
        $this->addSql('ALTER TABLE reset_password_request ADD CONSTRAINT FK_7CE748AFD61E233 FOREIGN KEY (idusuario) REFERENCES usuarios (idusuario)');
        $this->addSql('DROP INDEX especialidad ON especialidades');
        $this->addSql('ALTER TABLE especialidades CHANGE idespecialidad idespecialidad INT NOT NULL');
        $this->addSql('ALTER TABLE facultativos DROP FOREIGN KEY FK_C0A56F2FFD16E332');
        $this->addSql('DROP INDEX fk_c0a56f2ffd16e332 ON facultativos');
        $this->addSql('CREATE INDEX IDX_C0A65F2FACB064F9 ON facultativos (especialidad)');
        $this->addSql('ALTER TABLE facultativos ADD CONSTRAINT FK_C0A56F2FFD16E332 FOREIGN KEY (especialidad) REFERENCES especialidades (idespecialidad)');
        $this->addSql('ALTER TABLE pacientes DROP FOREIGN KEY FK_917B71581FD62E322');
        $this->addSql('ALTER TABLE pacientes CHANGE provincia provincia INT NOT NULL');
        $this->addSql('DROP INDEX fk_917b71581fd62e322 ON pacientes');
        $this->addSql('CREATE INDEX IDX_971B7851D39AF213 ON pacientes (provincia)');
        $this->addSql('ALTER TABLE pacientes ADD CONSTRAINT FK_917B71581FD62E322 FOREIGN KEY (provincia) REFERENCES provincias (idprovincia)');
        $this->addSql('DROP INDEX provincia ON provincias');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE citas_disponibles');
        $this->addSql('DROP TABLE reset_password_request');
        $this->addSql('ALTER TABLE especialidades CHANGE idespecialidad idespecialidad INT AUTO_INCREMENT NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX especialidad ON especialidades (especialidad)');
        $this->addSql('ALTER TABLE facultativos DROP FOREIGN KEY FK_C0A65F2FACB064F9');
        $this->addSql('DROP INDEX idx_c0a65f2facb064f9 ON facultativos');
        $this->addSql('CREATE INDEX FK_C0A56F2FFD16E332 ON facultativos (especialidad)');
        $this->addSql('ALTER TABLE facultativos ADD CONSTRAINT FK_C0A65F2FACB064F9 FOREIGN KEY (especialidad) REFERENCES especialidades (idespecialidad)');
        $this->addSql('ALTER TABLE pacientes DROP FOREIGN KEY FK_971B7851D39AF213');
        $this->addSql('ALTER TABLE pacientes CHANGE provincia provincia INT DEFAULT NULL');
        $this->addSql('DROP INDEX idx_971b7851d39af213 ON pacientes');
        $this->addSql('CREATE INDEX FK_917B71581FD62E322 ON pacientes (provincia)');
        $this->addSql('ALTER TABLE pacientes ADD CONSTRAINT FK_971B7851D39AF213 FOREIGN KEY (provincia) REFERENCES provincias (idprovincia)');
        $this->addSql('CREATE INDEX provincia ON provincias (provincia)');
    }
}
