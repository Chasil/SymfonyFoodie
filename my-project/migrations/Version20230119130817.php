<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230119130817 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE recipie_tags (recipie_id INT NOT NULL, tags_id INT NOT NULL, INDEX IDX_46CB7E9D4D7054B8 (recipie_id), INDEX IDX_46CB7E9D8D7B4FB4 (tags_id), PRIMARY KEY(recipie_id, tags_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE recipie_tags ADD CONSTRAINT FK_46CB7E9D4D7054B8 FOREIGN KEY (recipie_id) REFERENCES recipie (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE recipie_tags ADD CONSTRAINT FK_46CB7E9D8D7B4FB4 FOREIGN KEY (tags_id) REFERENCES tags (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tags DROP FOREIGN KEY FK_6FBC94264D7054B8');
        $this->addSql('DROP INDEX IDX_6FBC94264D7054B8 ON tags');
        $this->addSql('ALTER TABLE tags DROP recipie_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE recipie_tags DROP FOREIGN KEY FK_46CB7E9D4D7054B8');
        $this->addSql('ALTER TABLE recipie_tags DROP FOREIGN KEY FK_46CB7E9D8D7B4FB4');
        $this->addSql('DROP TABLE recipie_tags');
        $this->addSql('ALTER TABLE tags ADD recipie_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE tags ADD CONSTRAINT FK_6FBC94264D7054B8 FOREIGN KEY (recipie_id) REFERENCES recipie (id)');
        $this->addSql('CREATE INDEX IDX_6FBC94264D7054B8 ON tags (recipie_id)');
    }
}
