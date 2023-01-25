<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230125103612 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_64C19C15E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ingredients (id INT AUTO_INCREMENT NOT NULL, recipie_id INT NOT NULL, name VARCHAR(255) NOT NULL, measure VARCHAR(255) NOT NULL, INDEX IDX_4B60114F4D7054B8 (recipie_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE recipie (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(1000) NOT NULL, preparation VARCHAR(10000) NOT NULL, is_visible TINYINT(1) NOT NULL, photo VARCHAR(255) NOT NULL, INDEX IDX_FB0818E8A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE recipie_tags (recipie_id INT NOT NULL, tags_id INT NOT NULL, INDEX IDX_46CB7E9D4D7054B8 (recipie_id), INDEX IDX_46CB7E9D8D7B4FB4 (tags_id), PRIMARY KEY(recipie_id, tags_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE recipie_category (recipie_id INT NOT NULL, category_id INT NOT NULL, INDEX IDX_4A86F2714D7054B8 (recipie_id), INDEX IDX_4A86F27112469DE2 (category_id), PRIMARY KEY(recipie_id, category_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tags (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649F85E0677 (username), UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE ingredients ADD CONSTRAINT FK_4B60114F4D7054B8 FOREIGN KEY (recipie_id) REFERENCES recipie (id)');
        $this->addSql('ALTER TABLE recipie ADD CONSTRAINT FK_FB0818E8A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE recipie_tags ADD CONSTRAINT FK_46CB7E9D4D7054B8 FOREIGN KEY (recipie_id) REFERENCES recipie (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE recipie_tags ADD CONSTRAINT FK_46CB7E9D8D7B4FB4 FOREIGN KEY (tags_id) REFERENCES tags (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE recipie_category ADD CONSTRAINT FK_4A86F2714D7054B8 FOREIGN KEY (recipie_id) REFERENCES recipie (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE recipie_category ADD CONSTRAINT FK_4A86F27112469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ingredients DROP FOREIGN KEY FK_4B60114F4D7054B8');
        $this->addSql('ALTER TABLE recipie DROP FOREIGN KEY FK_FB0818E8A76ED395');
        $this->addSql('ALTER TABLE recipie_tags DROP FOREIGN KEY FK_46CB7E9D4D7054B8');
        $this->addSql('ALTER TABLE recipie_tags DROP FOREIGN KEY FK_46CB7E9D8D7B4FB4');
        $this->addSql('ALTER TABLE recipie_category DROP FOREIGN KEY FK_4A86F2714D7054B8');
        $this->addSql('ALTER TABLE recipie_category DROP FOREIGN KEY FK_4A86F27112469DE2');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE ingredients');
        $this->addSql('DROP TABLE recipie');
        $this->addSql('DROP TABLE recipie_tags');
        $this->addSql('DROP TABLE recipie_category');
        $this->addSql('DROP TABLE tags');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
