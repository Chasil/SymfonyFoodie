<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230103081135 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tags ADD recipie_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE tags ADD CONSTRAINT FK_6FBC94264D7054B8 FOREIGN KEY (recipie_id) REFERENCES recipie (id)');
        $this->addSql('CREATE INDEX IDX_6FBC94264D7054B8 ON tags (recipie_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tags DROP FOREIGN KEY FK_6FBC94264D7054B8');
        $this->addSql('DROP INDEX IDX_6FBC94264D7054B8 ON tags');
        $this->addSql('ALTER TABLE tags DROP recipie_id');
    }
}
