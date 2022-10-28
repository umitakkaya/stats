# Super-Assignment

This is the Supermetrics PHP assignment. Please read this README.md and then checkout the task(s) in `public/`.

The detailed information about the assignment tasks should have been provided to you before you got here.

## Environmant

You should be running PHP 8.1+, the latest version of composer, and available to use command line on your machine for any commands listed below. How you run PHP is up to you, but we do provide you with docker containers if you wish to use them.

## Local setup

**1. Setup .env file** 

Copy `/.env.example` to `/.env`. Add required settings there. Finding out the required settings is part of the assignment.

**2. Instantiate Docker containers (optional)**

To build the containerized environment, run command `composer serve`. This will setup the containers for you, and run `composer install` on your behalf in the container. You will get two containers: `supermetrics_php` and `supermetrics_db`, both in Docker project `supermetrics`.

PHP container is listening on `localhost:7777`, and MySQL is listening on `localhost:7778`.

**Note:** Locally changed files will be updated to the Docker container automatically as mirroring is enabled. If you need to run any commands inside the containers, you can use `docker exec -ti supermetrics_php /bin/bash` and `docker exec -ti supermetrics_db /bin/bash` to enter the containers.  

## Executing PHP code
The application is a CLI app, so executing the code happens in CLI. You can either login to the container, use `composer exec-php public/task_1.php`, or setup you favorite IDE to execute scripts inside the container.

## Testing

**Running tests**

**1. Run tests**

Run the tests with command `composer test` or `composer test-coverage`.

## Debugging

If you want to enable Xdebug, uncomment these lines in `./Dockerfile-php`:

```dockerfile
RUN pecl install xdebug \
	&& docker-php-ext-enable xdebug
```

and execute command `composer serve` to rebuild the containers. By default, Xdebug is configured to listen on port `9933` on `host.docker.internal`. See more the settings in the very end of the file `./docker/php/php.ini`.

**Note:** Xdebug is required for test coverage!

## Notes about the database

The database is initiated with `./docker/mariadb/schema.sql`, and it's listening on `localhost:7778` for external connections.

You can find the schema `assignment` with the table `user` in it with a couple of rows pre-populated.

If you're using the container provided by us, you can access the database from PHP container with host `db` and port `3306`.

**Connection example**

```php
$pdo = new PDO('mysql:host=db;port=3306;dbname=assignment', 'root', 'root');
```

## Tips

If you want to use **PhpStorm** and execute your scripts directly from PhpStorm, set the Docker network mode (see setting: PHP > Docker container > Network mode) to `sm_assignment`. This allows your scripts to connect to the database. 
