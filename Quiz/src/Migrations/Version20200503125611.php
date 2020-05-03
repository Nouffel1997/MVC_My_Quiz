<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200503125611 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE question CHANGE id_categorie id_categorie INT DEFAULT NULL');
        $this->addSql('ALTER TABLE question ADD CONSTRAINT FK_B6F7494EC9486A13 FOREIGN KEY (id_categorie) REFERENCES categorie (id)');
        $this->addSql('CREATE INDEX IDX_B6F7494EC9486A13 ON question (id_categorie)');
        $this->addSql('ALTER TABLE user ADD token VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE reponse CHANGE id_question id_question INT DEFAULT NULL, CHANGE reponse_expected reponse_expected INT DEFAULT NULL');
        $this->addSql('ALTER TABLE reponse ADD CONSTRAINT FK_5FB6DEC7E62CA5DB FOREIGN KEY (id_question) REFERENCES question (id)');
        $this->addSql('ALTER TABLE reponse ADD CONSTRAINT FK_5FB6DEC7FEC37B3 FOREIGN KEY (reponse_expected) REFERENCES question (id)');
        $this->addSql('CREATE INDEX IDX_5FB6DEC7E62CA5DB ON reponse (id_question)');
        $this->addSql('CREATE INDEX IDX_5FB6DEC7FEC37B3 ON reponse (reponse_expected)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE question DROP FOREIGN KEY FK_B6F7494EC9486A13');
        $this->addSql('DROP INDEX IDX_B6F7494EC9486A13 ON question');
        $this->addSql('ALTER TABLE question CHANGE id_categorie id_categorie INT NOT NULL');
        $this->addSql('ALTER TABLE reponse DROP FOREIGN KEY FK_5FB6DEC7E62CA5DB');
        $this->addSql('ALTER TABLE reponse DROP FOREIGN KEY FK_5FB6DEC7FEC37B3');
        $this->addSql('DROP INDEX IDX_5FB6DEC7E62CA5DB ON reponse');
        $this->addSql('DROP INDEX IDX_5FB6DEC7FEC37B3 ON reponse');
        $this->addSql('ALTER TABLE reponse CHANGE id_question id_question INT NOT NULL, CHANGE reponse_expected reponse_expected INT NOT NULL');
        $this->addSql('ALTER TABLE user DROP token');
    }
}
