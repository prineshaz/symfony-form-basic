<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250403000820 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE address (id SERIAL NOT NULL, registered_user_id INT NOT NULL, first_line VARCHAR(255) NOT NULL, second_line VARCHAR(255) DEFAULT '' NOT NULL, post_code VARCHAR(255) NOT NULL, city VARCHAR(255) NOT NULL, state_province VARCHAR(255) DEFAULT '' NOT NULL, country VARCHAR(255) NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_D4E6F81A6A12EC1 ON address (registered_user_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE payment_details (id SERIAL NOT NULL, registered_user_id INT NOT NULL, card_number VARCHAR(20) NOT NULL, cvv VARCHAR(3) NOT NULL, expiration_date VARCHAR(7) NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_6B6F0560A6A12EC1 ON payment_details (registered_user_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE user_registration (id SERIAL NOT NULL, name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, subscription VARCHAR(255) NOT NULL, phone VARCHAR(15) NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX UNIQ_E264DBA5E7927C74 ON user_registration (email)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE address ADD CONSTRAINT FK_D4E6F81A6A12EC1 FOREIGN KEY (registered_user_id) REFERENCES user_registration (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE payment_details ADD CONSTRAINT FK_6B6F0560A6A12EC1 FOREIGN KEY (registered_user_id) REFERENCES user_registration (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE SCHEMA public
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE address DROP CONSTRAINT FK_D4E6F81A6A12EC1
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE payment_details DROP CONSTRAINT FK_6B6F0560A6A12EC1
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE address
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE payment_details
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE user_registration
        SQL);
    }
}
