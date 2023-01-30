<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230130125051 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE recipie_tag (recipie_id INT NOT NULL, tag_id INT NOT NULL, INDEX IDX_ADAA94FA4D7054B8 (recipie_id), INDEX IDX_ADAA94FABAD26311 (tag_id), PRIMARY KEY(recipie_id, tag_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tag (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE recipie_tag ADD CONSTRAINT FK_ADAA94FA4D7054B8 FOREIGN KEY (recipie_id) REFERENCES recipie (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE recipie_tag ADD CONSTRAINT FK_ADAA94FABAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE recipie_tags DROP FOREIGN KEY FK_46CB7E9D4D7054B8');
        $this->addSql('ALTER TABLE recipie_tags DROP FOREIGN KEY FK_46CB7E9D8D7B4FB4');
        $this->addSql('DROP TABLE recipie_tags');
        $this->addSql('DROP TABLE tags');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE recipie_tags (recipie_id INT NOT NULL, tags_id INT NOT NULL, INDEX IDX_46CB7E9D4D7054B8 (recipie_id), INDEX IDX_46CB7E9D8D7B4FB4 (tags_id), PRIMARY KEY(recipie_id, tags_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE tags (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE recipie_tags ADD CONSTRAINT FK_46CB7E9D4D7054B8 FOREIGN KEY (recipie_id) REFERENCES recipie (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE recipie_tags ADD CONSTRAINT FK_46CB7E9D8D7B4FB4 FOREIGN KEY (tags_id) REFERENCES tags (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE recipie_tag DROP FOREIGN KEY FK_ADAA94FA4D7054B8');
        $this->addSql('ALTER TABLE recipie_tag DROP FOREIGN KEY FK_ADAA94FABAD26311');
        $this->addSql('DROP TABLE recipie_tag');
        $this->addSql('DROP TABLE tag');
    }
}
