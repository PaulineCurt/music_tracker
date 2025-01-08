<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250108000000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create favorite_album table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE favorite_album (
            id INT AUTO_INCREMENT NOT NULL,
            user_id INT NOT NULL,
            album_id INT NOT NULL,
            PRIMARY KEY(id)
        )');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE favorite_album');
    }
}
