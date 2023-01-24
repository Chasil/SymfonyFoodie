<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230124082748 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE recipie_category (recipie_id INT NOT NULL, category_id INT NOT NULL, INDEX IDX_4A86F2714D7054B8 (recipie_id), INDEX IDX_4A86F27112469DE2 (category_id), PRIMARY KEY(recipie_id, category_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE recipie_category ADD CONSTRAINT FK_4A86F2714D7054B8 FOREIGN KEY (recipie_id) REFERENCES recipie (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE recipie_category ADD CONSTRAINT FK_4A86F27112469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE recipie_category DROP FOREIGN KEY FK_4A86F2714D7054B8');
        $this->addSql('ALTER TABLE recipie_category DROP FOREIGN KEY FK_4A86F27112469DE2');
        $this->addSql('DROP TABLE recipie_category');
    }
}
