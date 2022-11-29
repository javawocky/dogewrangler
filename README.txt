README
======
Hi.  This is a Symfony project.  It should be relatively easy to get up and running but if there are issues
I can demo.

Requirements
============
You will need PHP 8.1 or higher.  It was written with 8.1
You will need composer installed.
I am not sure if you will need to have symfony commands.  I think composer should be enough.

Composer
========
From the commandline run
composer install

Database
========
Enter your database connection string in .env or .env.local.
More info can be found here https://symfony.com/doc/current/configuration.html
You may need to run the migration to create the tables
php bin/console make:migration
php bin/console doctrine:migrations:migrate

Start the webserver
===================
From the root folder type
symfony server:start
It should display the port if all went well.  Default is 8000

Load some test data
===================
Visit this URL to load some test data into the database.
localhost:8000/addsometestdatabycallingthis

Finally, hit the root URL to interact with the app.

Thanks!



