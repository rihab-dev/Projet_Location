<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250409180435 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE rendez_vous ADD etudiant_id INT DEFAULT NULL, ADD proprietaire_id INT DEFAULT NULL, ADD user_id INT NOT NULL');
        $this->addSql('ALTER TABLE rendez_vous ADD CONSTRAINT FK_65E8AA0ADDEAB1A3 FOREIGN KEY (etudiant_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE rendez_vous ADD CONSTRAINT FK_65E8AA0A76C50E4A FOREIGN KEY (proprietaire_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE rendez_vous ADD CONSTRAINT FK_65E8AA0AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_65E8AA0ADDEAB1A3 ON rendez_vous (etudiant_id)');
        $this->addSql('CREATE INDEX IDX_65E8AA0A76C50E4A ON rendez_vous (proprietaire_id)');
        $this->addSql('CREATE INDEX IDX_65E8AA0AA76ED395 ON rendez_vous (user_id)');
        $this->addSql('ALTER TABLE user ADD roles JSON NOT NULL COMMENT \'(DC2Type:json)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE rendez_vous DROP FOREIGN KEY FK_65E8AA0ADDEAB1A3');
        $this->addSql('ALTER TABLE rendez_vous DROP FOREIGN KEY FK_65E8AA0A76C50E4A');
        $this->addSql('ALTER TABLE rendez_vous DROP FOREIGN KEY FK_65E8AA0AA76ED395');
        $this->addSql('DROP INDEX IDX_65E8AA0ADDEAB1A3 ON rendez_vous');
        $this->addSql('DROP INDEX IDX_65E8AA0A76C50E4A ON rendez_vous');
        $this->addSql('DROP INDEX IDX_65E8AA0AA76ED395 ON rendez_vous');
        $this->addSql('ALTER TABLE rendez_vous DROP etudiant_id, DROP proprietaire_id, DROP user_id');
        $this->addSql('ALTER TABLE user DROP roles');
    }
}
