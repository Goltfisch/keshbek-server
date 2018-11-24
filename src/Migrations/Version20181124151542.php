<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181124151542 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D19C00E884 FOREIGN KEY (creditorId) REFERENCES user (id)');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D11F5925EE FOREIGN KEY (debitorId) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_723705D19C00E884 ON transaction (creditorId)');
        $this->addSql('CREATE INDEX IDX_723705D11F5925EE ON transaction (debitorId)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE transaction DROP FOREIGN KEY FK_723705D19C00E884');
        $this->addSql('ALTER TABLE transaction DROP FOREIGN KEY FK_723705D11F5925EE');
        $this->addSql('DROP INDEX IDX_723705D19C00E884 ON transaction');
        $this->addSql('DROP INDEX IDX_723705D11F5925EE ON transaction');
    }
}
