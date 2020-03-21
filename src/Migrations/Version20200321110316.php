<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200321110316 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE user ADD email_id INT NOT NULL, ADD adresse1_id INT NOT NULL, ADD identifiant VARCHAR(255) NOT NULL, ADD password VARCHAR(255) NOT NULL, ADD civilite TINYINT(1) NOT NULL, ADD prenom VARCHAR(255) NOT NULL, ADD pays VARCHAR(255) NOT NULL, ADD code_postal VARCHAR(255) NOT NULL, ADD telephone VARCHAR(255) NOT NULL, ADD date_anniversaire DATE NOT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649A832C1C9 FOREIGN KEY (email_id) REFERENCES information_personelle (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649B4FCD790 FOREIGN KEY (adresse1_id) REFERENCES adresse_info (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649A832C1C9 ON user (email_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649B4FCD790 ON user (adresse1_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649A832C1C9');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649B4FCD790');
        $this->addSql('DROP INDEX UNIQ_8D93D649A832C1C9 ON user');
        $this->addSql('DROP INDEX UNIQ_8D93D649B4FCD790 ON user');
        $this->addSql('ALTER TABLE user DROP email_id, DROP adresse1_id, DROP identifiant, DROP password, DROP civilite, DROP prenom, DROP pays, DROP code_postal, DROP telephone, DROP date_anniversaire');
    }
}
