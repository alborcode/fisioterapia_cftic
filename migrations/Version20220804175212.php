<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220804175212 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(
            'ALTER TABLE informes ADD idfacultativo INT NOT NULL, ADD idpaciente INT NOT NULL'
        );
        $this->addSql(
            'ALTER TABLE informes ADD CONSTRAINT FK_E47FD09AD5988285 FOREIGN KEY (idfacultativo) REFERENCES facultativos (idfacultativo)'
        );
        $this->addSql(
            'ALTER TABLE informes ADD CONSTRAINT FK_E47FD09A6C1EE153 FOREIGN KEY (idpaciente) REFERENCES pacientes (idpaciente)'
        );
        $this->addSql(
            'CREATE INDEX IDX_E47FD09AD5988285 ON informes (idfacultativo)'
        );
        $this->addSql(
            'CREATE INDEX IDX_E47FD09A6C1EE153 ON informes (idpaciente)'
        );
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(
            'ALTER TABLE informes DROP FOREIGN KEY FK_E47FD09AD5988285'
        );
        $this->addSql(
            'ALTER TABLE informes DROP FOREIGN KEY FK_E47FD09A6C1EE153'
        );
        $this->addSql('DROP INDEX IDX_E47FD09AD5988285 ON informes');
        $this->addSql('DROP INDEX IDX_E47FD09A6C1EE153 ON informes');
        $this->addSql(
            'ALTER TABLE informes DROP idfacultativo, DROP idpaciente'
        );
    }
}
