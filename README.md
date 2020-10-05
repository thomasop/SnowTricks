Openclassroom-P6-SnowTricks

Welcome in your Symfony site.


Prerequisite:
Download Wamp, Xampp or Mamp.
Symfony 5.1 or higher
Composer

Clone
Go in directory.
Make a clone with git clone https://github.com/thomasop/SnowTricks.git .

Configuration
Update environnements variables in the .env file with your values.
DATABASE_URL
MAILER_DSN

Composer
Install composer with composer install and init the projet with composer init in SnowTricks

Database creation

Use the command php bin/console doctrine:database:create for database creation.
Use the command php bin/console doctrine:migrations:migrate for creation of the tables.
Use the command php bin/console doctrine:fixtures:load for load some data in database

