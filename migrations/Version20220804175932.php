<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220804175932 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE citas ADD idfacultativo INT NOT NULL, ADD idpaciente INT NOT NULL');
        $this->addSql('ALTER TABLE citas ADD CONSTRAINT FK_B88CF8E5D5988285 FOREIGN KEY (idfacultativo) REFERENCES facultativos (idfacultativo)');
        $this->addSql('ALTER TABLE citas ADD CONSTRAINT FK_B88CF8E56C1EE153 FOREIGN KEY (idpaciente) REFERENCES pacientes (idpaciente)');
        $this->addSql('CREATE INDEX IDX_B88CF8E5D5988285 ON citas (idfacultativo)');
        $this->addSql('CREATE INDEX IDX_B88CF8E56C1EE153 ON citas (idpaciente)');
        $this->addSql('ALTER TABLE rehabilitaciones ADD idaseguradora INT NOT NULL');
        $this->addSql('ALTER TABLE rehabilitaciones ADD CONSTRAINT FK_92DD334061237021 FOREIGN KEY (idaseguradora) REFERENCES aseguradoras (idaseguradora)');
        $this->addSql('CREATE INDEX IDX_92DD334061237021 ON rehabilitaciones (idaseguradora)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE citas DROP FOREIGN KEY FK_B88CF8E5D5988285');
        $this->addSql('ALTER TABLE citas DROP FOREIGN KEY FK_B88CF8E56C1EE153');
        $this->addSql('DROP INDEX IDX_B88CF8E5D5988285 ON citas');
        $this->addSql('DROP INDEX IDX_B88CF8E56C1EE153 ON citas');
        $this->addSql('ALTER TABLE citas DROP idfacultativo, DROP idpaciente');
        $this->addSql('ALTER TABLE rehabilitaciones DROP FOREIGN KEY FK_92DD334061237021');
        $this->addSql('DROP INDEX IDX_92DD334061237021 ON rehabilitaciones');
        $this->addSql('ALTER TABLE rehabilitaciones DROP idaseguradora');
    }
}
