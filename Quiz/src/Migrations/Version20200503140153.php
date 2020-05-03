<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200503140153 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE user ADD roles JSON NOT NULL');
        $this->addSql('ALTER TABLE reponse ADD CONSTRAINT FK_5FB6DEC7FEC37B3 FOREIGN KEY (reponse_expected) REFERENCES question (id)');
        $this->addSql('CREATE INDEX IDX_5FB6DEC7FEC37B3 ON reponse (reponse_expected)');
        $this->addSql('ALTER TABLE reponse RENAME INDEX fk_5fb6dec7e62ca5db TO IDX_5FB6DEC7E62CA5DB');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE reponse DROP FOREIGN KEY FK_5FB6DEC7FEC37B3');
        $this->addSql('DROP INDEX IDX_5FB6DEC7FEC37B3 ON reponse');
        $this->addSql('ALTER TABLE reponse RENAME INDEX idx_5fb6dec7e62ca5db TO FK_5FB6DEC7E62CA5DB');
        $this->addSql('ALTER TABLE user DROP roles');
    }
}
