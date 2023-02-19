<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230219223456 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add Pizza, PizzaIngredient and Ingredient entities';
    }

    public function up(Schema $schema): void
    {
        // create Pizza table
        $this->addSql('CREATE TABLE pizza (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description TEXT DEFAULT NULL, price NUMERIC(6, 2) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        // create Ingredient table
        $this->addSql('CREATE TABLE ingredient (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, price NUMERIC(6, 2) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        // create PizzaIngredient table
        $this->addSql('CREATE TABLE pizza_ingredient (id INT AUTO_INCREMENT NOT NULL, pizza_id INT NOT NULL, ingredient_id INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        // add foreign keys to PizzaIngredient table
        $this->addSql('ALTER TABLE pizza_ingredient ADD CONSTRAINT FK_46F2B1C28FB9A7F7 FOREIGN KEY (pizza_id) REFERENCES pizza (id)');
        $this->addSql('ALTER TABLE pizza_ingredient ADD CONSTRAINT FK_46F2B1C2B178ECCB FOREIGN KEY (ingredient_id) REFERENCES ingredient (id)');

        // insert some data into Ingredient table
        $this->addSql("INSERT INTO ingredient (name, price) VALUES
                                ('Tomatoes', 0.50),
                                ('Mozzarella', 1.20),
                                ('Mushrooms', 0.80),
                                ('Black Olives', 0.70),
                                ('Green Peppers', 0.60),
                                ('Ham', 1.50),
                                ('Pineapple', 1.00),
                                ('Pepperoni', 1.20),
                                ('Onions', 0.50),
                                ('Garlic', 0.30);
                            ");

        // insert some data into Pizza table
        $this->addSql("INSERT INTO pizza (name, price) VALUES ('Margherita', 8.50)");
        $this->addSql("INSERT INTO pizza (name, price) VALUES ('Pepperoni', 10.00)");

        // insert some data into PizzaIngredient table
        $this->addSql("INSERT INTO pizza_ingredient (quantity, pizza_id, ingredient_id) VALUES (100, 1, 1)");
        $this->addSql("INSERT INTO pizza_ingredient (quantity, pizza_id, ingredient_id) VALUES (100, 2, 1);");
    }

    public function down(Schema $schema): void
    {
        // drop tables
        $this->addSql('DROP TABLE pizza_ingredient');
        $this->addSql('DROP TABLE pizza');
        $this->addSql('DROP TABLE ingredient');
    }
}
