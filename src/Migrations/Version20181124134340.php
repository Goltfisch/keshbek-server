<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181124134340 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');        
        
        $this->addSql("INSERT INTO state (id, label) VALUES (1, 'new'), (2, 'open'), (3, 'paid');");
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');        
        
        $this->addSql("DELETE FROM state WHERE id = 1 AND label = 'new'");
        $this->addSql("DELETE FROM state WHERE id = 2 AND label = 'open'");
        $this->addSql("DELETE FROM state WHERE id = 3 AND label = 'paid'");
    }   
}
