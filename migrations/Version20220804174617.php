<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220804174617 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE turnos ADD idfacultativo INT NOT NULL');
        $this->addSql('ALTER TABLE turnos ADD CONSTRAINT FK_B8555818D5988285 FOREIGN KEY (idfacultativo) REFERENCES facultativos (idfacultativo)');
        $this->addSql('CREATE INDEX IDX_B8555818D5988285 ON turnos (idfacultativo)');
        $this->addSql('ALTER TABLE vacaciones ADD idfacultativo INT NOT NULL');
        $this->addSql('ALTER TABLE vacaciones ADD CONSTRAINT FK_CAA83E94D5988285 FOREIGN KEY (idfacultativo) REFERENCES facultativos (idfacultativo)');
        $this->addSql('CREATE INDEX IDX_CAA83E94D5988285 ON vacaciones (idfacultativo)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE turnos DROP FOREIGN KEY FK_B8555818D5988285');
        $this->addSql('DROP INDEX IDX_B8555818D5988285 ON turnos');
        $this->addSql('ALTER TABLE turnos DROP idfacultativo');
        $this->addSql('ALTER TABLE vacaciones DROP FOREIGN KEY FK_CAA83E94D5988285');
        $this->addSql('DROP INDEX IDX_CAA83E94D5988285 ON vacaciones');
        $this->addSql('ALTER TABLE vacaciones DROP idfacultativo');
    }
}
