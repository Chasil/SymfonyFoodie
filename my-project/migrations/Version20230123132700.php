<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230123132700 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE category_recipie (category_id INT NOT NULL, recipie_id INT NOT NULL, INDEX IDX_3CD0589612469DE2 (category_id), INDEX IDX_3CD058964D7054B8 (recipie_id), PRIMARY KEY(category_id, recipie_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE category_recipie ADD CONSTRAINT FK_3CD0589612469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE category_recipie ADD CONSTRAINT FK_3CD058964D7054B8 FOREIGN KEY (recipie_id) REFERENCES recipie (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE category_recipie DROP FOREIGN KEY FK_3CD0589612469DE2');
        $this->addSql('ALTER TABLE category_recipie DROP FOREIGN KEY FK_3CD058964D7054B8');
        $this->addSql('DROP TABLE category_recipie');
    }
}
