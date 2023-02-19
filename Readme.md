# Projet Symfony Pizza

## Description
Projet Symfony pour la gestion des pizzas et des ingrédients.

## Prérequis
- Docker
- Docker Compose

## Installation
1. Clonez le repository : `git clone https://github.com/ayoubra49/catalog_pizza.git`
2. Dans le répertoire du projet, lancez les conteneurs Docker : `docker-compose up -d`
3. Accédez au conteneur PHP : `docker-compose exec php bash`
4. Installez les dépendances : `composer install`
5. Créez la base de données : `php bin/console doctrine:database:create`
6. Migrer les tables : `php bin/console doctrine:migrations:migrate`

## Utilisation
Accédez à l'application via votre navigateur à l'adresse suivante : http://localhost:8081
