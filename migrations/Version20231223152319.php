<?php

/*
 * This file was created by Jakub Szczerba
 * It is part of an engineering project - Kcalculator - copyright is reserved
 * Contact: https://www.linkedin.com/in/jakub-szczerba-3492751b4/
*/

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20231223152319 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE users (id INT AUTO_INCREMENT NOT NULL, full_name VARCHAR(255) NOT NULL, username VARCHAR(128) NOT NULL, email VARCHAR(128) NOT NULL, password VARCHAR(4096) NOT NULL, roles JSON NOT NULL COMMENT \'(DC2Type:json)\', UNIQUE INDEX UNIQ_1483A5E9F85E0677 (username), UNIQUE INDEX UNIQ_1483A5E9E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE products (id INT AUTO_INCREMENT NOT NULL, product VARCHAR(255) NOT NULL, energy DOUBLE PRECISION NOT NULL, protein DOUBLE PRECISION NOT NULL, fat DOUBLE PRECISION NOT NULL, carbo DOUBLE PRECISION NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE entries (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, datetime DATETIME NOT NULL, meal_type VARCHAR(255) NOT NULL, grammage DOUBLE PRECISION NOT NULL, energy_xgram DOUBLE PRECISION NOT NULL, protein_xgram DOUBLE PRECISION NOT NULL, fat_xgram DOUBLE PRECISION NOT NULL, carbo_xgram DOUBLE PRECISION NOT NULL, INDEX IDX_2DF8B3C5A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE entries_products (entry_id INT NOT NULL, product_id INT NOT NULL, INDEX IDX_DBF0EA2FBA364942 (entry_id), INDEX IDX_DBF0EA2F4584665A (product_id), PRIMARY KEY(entry_id, product_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE preferences (id INT AUTO_INCREMENT NOT NULL, users_id INT NOT NULL, gender VARCHAR(255) NOT NULL, weight DOUBLE PRECISION NOT NULL, height DOUBLE PRECISION NOT NULL, age INT NOT NULL, activity VARCHAR(255) NOT NULL, caloric_requirement INT NOT NULL, intentions VARCHAR(255) NOT NULL, kcal_day INT NOT NULL, protein_per_day INT NOT NULL, fat_per_day INT NOT NULL, carbo_per_day INT NOT NULL, UNIQUE INDEX UNIQ_E931A6F567B3B43D (users_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE weight_history (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, datetime DATETIME NOT NULL, user_weight DOUBLE PRECISION NOT NULL, INDEX IDX_D87E442FA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE entries ADD CONSTRAINT FK_2DF8B3C5A76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE entries_products ADD CONSTRAINT FK_DBF0EA2FBA364942 FOREIGN KEY (entry_id) REFERENCES entries (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE entries_products ADD CONSTRAINT FK_DBF0EA2F4584665A FOREIGN KEY (product_id) REFERENCES products (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE preferences ADD CONSTRAINT FK_E931A6F567B3B43D FOREIGN KEY (users_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE weight_history ADD CONSTRAINT FK_D87E442FA76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE entries DROP FOREIGN KEY FK_2DF8B3C5A76ED395');
        $this->addSql('ALTER TABLE entries_products DROP FOREIGN KEY FK_DBF0EA2FBA364942');
        $this->addSql('ALTER TABLE entries_products DROP FOREIGN KEY FK_DBF0EA2F4584665A');
        $this->addSql('ALTER TABLE preferences DROP FOREIGN KEY FK_E931A6F567B3B43D');
        $this->addSql('ALTER TABLE weight_history DROP FOREIGN KEY FK_D87E442FA76ED395');
        $this->addSql('DROP TABLE entries');
        $this->addSql('DROP TABLE entries_products');
        $this->addSql('DROP TABLE preferences');
        $this->addSql('DROP TABLE products');
        $this->addSql('DROP TABLE users');
        $this->addSql('DROP TABLE weight_history');
    }
}
