<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200428015443 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE question CHANGE id_categorie id_categorie INT NOT NULL, CHANGE question question LONGTEXT NOT NULL');
        $this->addSql('ALTER TABLE user ADD reset_token VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE reponse CHANGE id_question id_question INT NOT NULL, CHANGE reponse reponse LONGTEXT NOT NULL, CHANGE reponse_expected reponse_expected INT NOT NULL');
        $this->addSql('ALTER TABLE categorie CHANGE name name LONGTEXT NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE categorie CHANGE name name VARCHAR(100) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_0900_ai_ci`');
        $this->addSql('ALTER TABLE question CHANGE id_categorie id_categorie INT DEFAULT NULL, CHANGE question question VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_0900_ai_ci`');
        $this->addSql('ALTER TABLE reponse CHANGE id_question id_question INT DEFAULT NULL, CHANGE reponse reponse VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_0900_ai_ci`, CHANGE reponse_expected reponse_expected TINYINT(1) DEFAULT NULL');
        $this->addSql('ALTER TABLE user DROP reset_token');
    }
}
