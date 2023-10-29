<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231028165138 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE affix_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE flag_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE word_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE affix (id INT NOT NULL, flag_id INT NOT NULL, stripping VARCHAR(255) NOT NULL, prefix VARCHAR(255) NOT NULL, condition VARCHAR(255) DEFAULT NULL, morphological_fields VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_DA869F4D919FE4E5 ON affix (flag_id)');
        $this->addSql('CREATE TABLE flag (id INT NOT NULL, type VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE flag_word (flag_id INT NOT NULL, word_id INT NOT NULL, PRIMARY KEY(flag_id, word_id))');
        $this->addSql('CREATE INDEX IDX_2EF2D3D0919FE4E5 ON flag_word (flag_id)');
        $this->addSql('CREATE INDEX IDX_2EF2D3D0E357438D ON flag_word (word_id)');
        $this->addSql('CREATE TABLE word (id INT NOT NULL, word VARCHAR(255) NOT NULL, morphological_fields VARCHAR(255) DEFAULT NULL, comment VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE messenger_messages (id BIGSERIAL NOT NULL, body TEXT NOT NULL, headers TEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, available_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, delivered_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_75EA56E0FB7336F0 ON messenger_messages (queue_name)');
        $this->addSql('CREATE INDEX IDX_75EA56E0E3BD61CE ON messenger_messages (available_at)');
        $this->addSql('CREATE INDEX IDX_75EA56E016BA31DB ON messenger_messages (delivered_at)');
        $this->addSql('COMMENT ON COLUMN messenger_messages.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN messenger_messages.available_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN messenger_messages.delivered_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE OR REPLACE FUNCTION notify_messenger_messages() RETURNS TRIGGER AS $$
            BEGIN
                PERFORM pg_notify(\'messenger_messages\', NEW.queue_name::text);
                RETURN NEW;
            END;
        $$ LANGUAGE plpgsql;');
        $this->addSql('DROP TRIGGER IF EXISTS notify_trigger ON messenger_messages;');
        $this->addSql('CREATE TRIGGER notify_trigger AFTER INSERT OR UPDATE ON messenger_messages FOR EACH ROW EXECUTE PROCEDURE notify_messenger_messages();');
        $this->addSql('ALTER TABLE affix ADD CONSTRAINT FK_DA869F4D919FE4E5 FOREIGN KEY (flag_id) REFERENCES flag (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE flag_word ADD CONSTRAINT FK_2EF2D3D0919FE4E5 FOREIGN KEY (flag_id) REFERENCES flag (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE flag_word ADD CONSTRAINT FK_2EF2D3D0E357438D FOREIGN KEY (word_id) REFERENCES word (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE affix_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE flag_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE word_id_seq CASCADE');
        $this->addSql('ALTER TABLE affix DROP CONSTRAINT FK_DA869F4D919FE4E5');
        $this->addSql('ALTER TABLE flag_word DROP CONSTRAINT FK_2EF2D3D0919FE4E5');
        $this->addSql('ALTER TABLE flag_word DROP CONSTRAINT FK_2EF2D3D0E357438D');
        $this->addSql('DROP TABLE affix');
        $this->addSql('DROP TABLE flag');
        $this->addSql('DROP TABLE flag_word');
        $this->addSql('DROP TABLE word');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
