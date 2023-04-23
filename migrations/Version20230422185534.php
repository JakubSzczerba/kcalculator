<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230422185534 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE products_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE user_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE user_preferention_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE user_weightHistory_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE users_entries_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE products (id INT NOT NULL, product VARCHAR(255) NOT NULL, energy INT NOT NULL, protein DOUBLE PRECISION NOT NULL, fat DOUBLE PRECISION NOT NULL, carbo DOUBLE PRECISION NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE "user" (id INT NOT NULL, full_name VARCHAR(255) NOT NULL, username VARCHAR(128) NOT NULL, email VARCHAR(128) NOT NULL, password VARCHAR(4096) NOT NULL, roles JSON NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649F85E0677 ON "user" (username)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON "user" (email)');
        $this->addSql('CREATE TABLE user_preferention (id INT NOT NULL, users_id INT NOT NULL, gender VARCHAR(255) NOT NULL, weight DOUBLE PRECISION NOT NULL, height DOUBLE PRECISION NOT NULL, age INT NOT NULL, activity VARCHAR(255) NOT NULL, caloric_requirement INT NOT NULL, intentions VARCHAR(255) NOT NULL, kcal_day INT NOT NULL, protein_per_day INT NOT NULL, fat_per_day INT NOT NULL, carbo_per_day INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_175923FA67B3B43D ON user_preferention (users_id)');
        $this->addSql('CREATE TABLE user_weightHistory (id INT NOT NULL, user_id INT NOT NULL, datetime DATE NOT NULL, user_weight DOUBLE PRECISION NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_34E417DDA76ED395 ON user_weightHistory (user_id)');
        $this->addSql('CREATE TABLE users_entries (id INT NOT NULL, user_id INT NOT NULL, datetime DATE NOT NULL, meal_type VARCHAR(255) NOT NULL, grammage DOUBLE PRECISION NOT NULL, energy_xgram DOUBLE PRECISION NOT NULL, protein_xgram DOUBLE PRECISION NOT NULL, fat_xgram DOUBLE PRECISION NOT NULL, carbo_xgram DOUBLE PRECISION NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_DDF8C70FA76ED395 ON users_entries (user_id)');
        $this->addSql('CREATE TABLE users_entries_products (users_entries_id INT NOT NULL, products_id INT NOT NULL, PRIMARY KEY(users_entries_id, products_id))');
        $this->addSql('CREATE INDEX IDX_CB91E58A5AC0B977 ON users_entries_products (users_entries_id)');
        $this->addSql('CREATE INDEX IDX_CB91E58A6C8A81A9 ON users_entries_products (products_id)');
        $this->addSql('ALTER TABLE user_preferention ADD CONSTRAINT FK_175923FA67B3B43D FOREIGN KEY (users_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_weightHistory ADD CONSTRAINT FK_34E417DDA76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE users_entries ADD CONSTRAINT FK_DDF8C70FA76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE users_entries_products ADD CONSTRAINT FK_CB91E58A5AC0B977 FOREIGN KEY (users_entries_id) REFERENCES users_entries (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE users_entries_products ADD CONSTRAINT FK_CB91E58A6C8A81A9 FOREIGN KEY (products_id) REFERENCES products (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE products_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE user_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE user_preferention_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE user_weightHistory_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE users_entries_id_seq CASCADE');
        $this->addSql('ALTER TABLE user_preferention DROP CONSTRAINT FK_175923FA67B3B43D');
        $this->addSql('ALTER TABLE user_weightHistory DROP CONSTRAINT FK_34E417DDA76ED395');
        $this->addSql('ALTER TABLE users_entries DROP CONSTRAINT FK_DDF8C70FA76ED395');
        $this->addSql('ALTER TABLE users_entries_products DROP CONSTRAINT FK_CB91E58A5AC0B977');
        $this->addSql('ALTER TABLE users_entries_products DROP CONSTRAINT FK_CB91E58A6C8A81A9');
        $this->addSql('DROP TABLE products');
        $this->addSql('DROP TABLE "user"');
        $this->addSql('DROP TABLE user_preferention');
        $this->addSql('DROP TABLE user_weightHistory');
        $this->addSql('DROP TABLE users_entries');
        $this->addSql('DROP TABLE users_entries_products');
    }
}
