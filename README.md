# Basic Symfony From via Docker

## Getting Started

1. If not already done, [install Docker Compose](https://docs.docker.com/compose/install/) (v2.10+)
2. Run `docker compose build --no-cache` to build fresh images
3. Run `SERVER_NAME="symfony.localhost" docker compose up -d --wait` to set up and start a fresh Symfony project
4. From same directory as `composer.yml`, run `docker compose exec php composer install`.
5. From same directory as `composer.yml`, run `docker compose exec php bin/console doctrine:migrations:migrate` (to create db tables)
6. Open `https://symfony.localhost` in your favorite web browser (you may get warning about certificates, click to proceed).
7. When you've finished, run `docker compose down --remove-orphans` to stop the Docker containers.

## Features

-   Basic sign up form connected to DB.
-   Basic validation on form fields.
-   Uses ajax via stimulus controllers.
-   Entity, controllers, event listeners created via symfony `console`.
-   Event driven flow, controller handles navigation and logic, DB operations handed off to listener.

## Unable to complete

-   Dynamic loading of form fields. This was due to time.
-   Real-time validation via JS
-   Obfuscation of sensitive data like card number and ccv.
-   I would have spent more time centralising classes with dependency injection in constructor (RegisterController).

## Access To DB

DB Tables:

|     | DB NAME           | Description                                                        |
| --- | ----------------- | ------------------------------------------------------------------ |
| 1   | user_registration | Step one of registration                                           |
| 2   | address           | Step 2 (designed for multiple addresses)                           |
| 3   | payment_details   | Step 3 (once again, designed to allow for multiple payment types). |

-   Run `docker exec -it forms-database-1 bin/bash` to enter db (postgres) service.
-   Once your inside, run `psql -U app`. If you're asked about a password use `!ChangeMe!`.
-   See data eg `SELECT * FROM user_registration;`
-   Describe table eg `\d address`.

**Enjoy!**
