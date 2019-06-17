<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190330222511 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE cash_up (id INT AUTO_INCREMENT NOT NULL, creditor_id INT NOT NULL, debitor_id INT NOT NULL, state_id INT NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_C1D78508DF91AC92 (creditor_id), INDEX IDX_C1D7850872757D19 (debitor_id), UNIQUE INDEX UNIQ_C1D785085D83CC1 (state_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE cash_up ADD CONSTRAINT FK_C1D78508DF91AC92 FOREIGN KEY (creditor_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE cash_up ADD CONSTRAINT FK_C1D7850872757D19 FOREIGN KEY (debitor_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE cash_up ADD CONSTRAINT FK_C1D785085D83CC1 FOREIGN KEY (state_id) REFERENCES state (id)');
        $this->addSql('ALTER TABLE transaction DROP FOREIGN KEY FK_723705D1B5286BEF');
        $this->addSql('DROP INDEX IDX_723705D1B5286BEF ON transaction');
        $this->addSql('ALTER TABLE transaction CHANGE stateid cash_up_id INT NOT NULL');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D13611BF30 FOREIGN KEY (cash_up_id) REFERENCES cash_up (id)');
        $this->addSql('CREATE INDEX IDX_723705D13611BF30 ON transaction (cash_up_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE transaction DROP FOREIGN KEY FK_723705D13611BF30');
        $this->addSql('DROP TABLE cash_up');
        $this->addSql('DROP INDEX IDX_723705D13611BF30 ON transaction');
        $this->addSql('ALTER TABLE transaction CHANGE cash_up_id stateId INT NOT NULL');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D1B5286BEF FOREIGN KEY (stateId) REFERENCES state (id)');
        $this->addSql('CREATE INDEX IDX_723705D1B5286BEF ON transaction (stateId)');
    }
}
