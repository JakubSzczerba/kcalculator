<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211008122933 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(128) NOT NULL, email VARCHAR(128) NOT NULL, password VARCHAR(4096) NOT NULL, UNIQUE INDEX UNIQ_8D93D649F85E0677 (username), UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), UNIQUE INDEX UNIQ_8D93D64935C246D5 (password), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_preferention (id INT AUTO_INCREMENT NOT NULL, users_id INT NOT NULL, gender VARCHAR(255) NOT NULL, weight DOUBLE PRECISION NOT NULL, height DOUBLE PRECISION NOT NULL, age INT NOT NULL, activity VARCHAR(255) NOT NULL, caloric_requirement INT NOT NULL, intentions VARCHAR(255) NOT NULL, kcal_day INT NOT NULL, protein_per_day INT NOT NULL, fat_per_day INT NOT NULL, carbo_per_day INT NOT NULL, UNIQUE INDEX UNIQ_175923FA67B3B43D (users_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE users_entries (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, datetime DATE NOT NULL, meal_type VARCHAR(255) NOT NULL, grammage DOUBLE PRECISION NOT NULL, energy_xgram DOUBLE PRECISION NOT NULL, protein_xgram DOUBLE PRECISION NOT NULL, fat_xgram DOUBLE PRECISION NOT NULL, carbo_xgram DOUBLE PRECISION NOT NULL, INDEX IDX_DDF8C70FA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE users_entries_products (users_entries_id INT NOT NULL, products_id INT NOT NULL, INDEX IDX_CB91E58A5AC0B977 (users_entries_id), INDEX IDX_CB91E58A6C8A81A9 (products_id), PRIMARY KEY(users_entries_id, products_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_preferention ADD CONSTRAINT FK_175923FA67B3B43D FOREIGN KEY (users_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE users_entries ADD CONSTRAINT FK_DDF8C70FA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE users_entries_products ADD CONSTRAINT FK_CB91E58A5AC0B977 FOREIGN KEY (users_entries_id) REFERENCES users_entries (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE users_entries_products ADD CONSTRAINT FK_CB91E58A6C8A81A9 FOREIGN KEY (products_id) REFERENCES products (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_weightHistory ADD CONSTRAINT FK_34E417DDA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_preferention DROP FOREIGN KEY FK_175923FA67B3B43D');
        $this->addSql('ALTER TABLE user_weightHistory DROP FOREIGN KEY FK_34E417DDA76ED395');
        $this->addSql('ALTER TABLE users_entries DROP FOREIGN KEY FK_DDF8C70FA76ED395');
        $this->addSql('ALTER TABLE users_entries_products DROP FOREIGN KEY FK_CB91E58A5AC0B977');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE user_preferention');
        $this->addSql('DROP TABLE users_entries');
        $this->addSql('DROP TABLE users_entries_products');
    }
}
