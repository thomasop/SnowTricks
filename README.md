
[![Codacy Badge](https://api.codacy.com/project/badge/Grade/96e306c369e24df984dcf501535ffb5c)](https://app.codacy.com/gh/thomasop/SnowTricks?utm_source=github.com&utm_medium=referral&utm_content=thomasop/SnowTricks&utm_campaign=Badge_Grade_Settings)

Openclassroom-P6-SnowTricks

Welcome in your Symfony site.

How to install the project?

Prerequisite:
PHP 7.2.5 or higher
Download Wamp, Xampp, Mamp or WebHost
Symfony 5.1 or higher
Composer

Clone:
Go in directory.
Make a clone with git clone https://github.com/thomasop/SnowTricks.git

Configuration:
Update environnements variables in the .env file with your values.
At the very least you need to define the SYMFONY_ENV=prod
DATABASE_URL
MAILER_URL

Composer:
Install composer with composer install and init the projet with composer init in SnowTricks

Database creation:
Use the command php bin/console doctrine:database:create for database creation.
Use the command php bin/console doctrine:migrations:migrate for creation of the tables.
Use the command php bin/console doctrine:fixtures:load for load some data in database

Start the project:
At the root of your project use the command php bin/console server:start -d
Default connection use Email :test@fixture.fr - Password :Test1234?