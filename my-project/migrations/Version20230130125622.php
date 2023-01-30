<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230130125622 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE ingredient (id INT AUTO_INCREMENT NOT NULL, recipie_id INT NOT NULL, name VARCHAR(255) NOT NULL, measure VARCHAR(255) NOT NULL, INDEX IDX_6BAF78704D7054B8 (recipie_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE ingredient ADD CONSTRAINT FK_6BAF78704D7054B8 FOREIGN KEY (recipie_id) REFERENCES recipie (id)');
        $this->addSql('ALTER TABLE ingredients DROP FOREIGN KEY FK_4B60114F4D7054B8');
        $this->addSql('DROP TABLE ingredients');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE ingredients (id INT AUTO_INCREMENT NOT NULL, recipie_id INT NOT NULL, name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, measure VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, INDEX IDX_4B60114F4D7054B8 (recipie_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE ingredients ADD CONSTRAINT FK_4B60114F4D7054B8 FOREIGN KEY (recipie_id) REFERENCES recipie (id)');
        $this->addSql('ALTER TABLE ingredient DROP FOREIGN KEY FK_6BAF78704D7054B8');
        $this->addSql('DROP TABLE ingredient');
    }
}
