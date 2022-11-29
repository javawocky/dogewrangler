README
======
Hi,

I approached this problem wanting to kill two birds with one stone.  I initially thought of just using plain PHP or even
Spring Boot to do this assignment, however I thought it would be good to setup a modern version of Symfony.  In my
current day job we use an old version of Silex so you don't typically get to set things up that often.

I have tried really hard to focus on the backend parts, but put one or two front end touches in place.
The instructions were not specific if the backend should be an API so I settled on just doing a traditional backend
web app.

The code certainly isn't production ready which will surely generate some discussion later, but I have tried to
keep things relatively clean.

Key bits off code are the IndexController.php which handles all incoming requests.  Technically I could have seporated
out different endpoints to different controllers like Job and Note, but there are not many off them so I kept them in
once place for convince.

I chose doctrine for the database calls, see the Entity folder and the Repository folder.

Anyway, hopefully it should be relatively easy to get up and running but if there are issues
I can demo later.

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



