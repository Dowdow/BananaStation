<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180309161045 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE banana_commentaire (id INT AUTO_INCREMENT NOT NULL, utilisateur_id INT NOT NULL, projet_id INT NOT NULL, contenu LONGTEXT NOT NULL, date DATETIME NOT NULL, INDEX IDX_45FAD0ABFB88E14F (utilisateur_id), INDEX IDX_45FAD0ABC18272 (projet_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE banana_projet (id INT AUTO_INCREMENT NOT NULL, utilisateur_id INT NOT NULL, nom VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, date DATETIME NOT NULL, image VARCHAR(255) NOT NULL, etat VARCHAR(1) NOT NULL, progression INT NOT NULL, slug VARCHAR(255) NOT NULL, INDEX IDX_520185C7FB88E14F (utilisateur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE banana_utilisateur (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(30) NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, salt VARCHAR(255) NOT NULL, roles VARCHAR(1) NOT NULL, date DATETIME NOT NULL, token VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_3F16DBA4F85E0677 (username), UNIQUE INDEX UNIQ_3F16DBA4E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE banana_note (id INT AUTO_INCREMENT NOT NULL, utilisateur_id INT NOT NULL, projet_id INT NOT NULL, contenu LONGTEXT NOT NULL, date DATETIME NOT NULL, INDEX IDX_99619D1DFB88E14F (utilisateur_id), INDEX IDX_99619D1DC18272 (projet_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE banana_avis (id INT AUTO_INCREMENT NOT NULL, utilisateur_id INT NOT NULL, projet_id INT NOT NULL, pouce VARCHAR(1) NOT NULL, date DATETIME NOT NULL, INDEX IDX_D94DCCF9FB88E14F (utilisateur_id), INDEX IDX_D94DCCF9C18272 (projet_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE music_music (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, youtubeid LONGTEXT NOT NULL, style VARCHAR(1) NOT NULL, date DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE banana_commentaire ADD CONSTRAINT FK_45FAD0ABFB88E14F FOREIGN KEY (utilisateur_id) REFERENCES banana_utilisateur (id)');
        $this->addSql('ALTER TABLE banana_commentaire ADD CONSTRAINT FK_45FAD0ABC18272 FOREIGN KEY (projet_id) REFERENCES banana_projet (id)');
        $this->addSql('ALTER TABLE banana_projet ADD CONSTRAINT FK_520185C7FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES banana_utilisateur (id)');
        $this->addSql('ALTER TABLE banana_note ADD CONSTRAINT FK_99619D1DFB88E14F FOREIGN KEY (utilisateur_id) REFERENCES banana_utilisateur (id)');
        $this->addSql('ALTER TABLE banana_note ADD CONSTRAINT FK_99619D1DC18272 FOREIGN KEY (projet_id) REFERENCES banana_projet (id)');
        $this->addSql('ALTER TABLE banana_avis ADD CONSTRAINT FK_D94DCCF9FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES banana_utilisateur (id)');
        $this->addSql('ALTER TABLE banana_avis ADD CONSTRAINT FK_D94DCCF9C18272 FOREIGN KEY (projet_id) REFERENCES banana_projet (id)');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE banana_commentaire DROP FOREIGN KEY FK_45FAD0ABC18272');
        $this->addSql('ALTER TABLE banana_note DROP FOREIGN KEY FK_99619D1DC18272');
        $this->addSql('ALTER TABLE banana_avis DROP FOREIGN KEY FK_D94DCCF9C18272');
        $this->addSql('ALTER TABLE banana_commentaire DROP FOREIGN KEY FK_45FAD0ABFB88E14F');
        $this->addSql('ALTER TABLE banana_projet DROP FOREIGN KEY FK_520185C7FB88E14F');
        $this->addSql('ALTER TABLE banana_note DROP FOREIGN KEY FK_99619D1DFB88E14F');
        $this->addSql('ALTER TABLE banana_avis DROP FOREIGN KEY FK_D94DCCF9FB88E14F');
        $this->addSql('DROP TABLE banana_commentaire');
        $this->addSql('DROP TABLE banana_projet');
        $this->addSql('DROP TABLE banana_utilisateur');
        $this->addSql('DROP TABLE banana_note');
        $this->addSql('DROP TABLE banana_avis');
        $this->addSql('DROP TABLE music_music');
    }
}
