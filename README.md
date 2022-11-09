# PSR URI pack
### Installation
```
cd docker
docker compose up -d
docker exec -it psr-http-uri-php sh
```
###### or you can use "make" utility
```
apt install make
make up # up all containers and get into php container
make down # down all containers
```
###### install all dependencies
```
# get into php container
composer install
```
### Creating SSH connection to docker PHP container
* go to File | Settings | Tools | SSH Configurations
* add new connection with parameters
  * host     : 10.10.1.2 (set in docker/docker-compose.yml file)
  * port     : 22
  * user     : root
  * password : root
### Adding PHP CLI interpreter
* go to File | Settings | PHP
* CLI Interpreters -> add new with parameters
  * SSH configuration: set added before
  * PHP executable: /usr/local/bin/php
    (can be known by running "which php" in command line inside PHP container)
* Path mapping -> add new with parameters
  * Local path  : PHPStorm project root
  * Remote path : /var/www/html
### Register tests framework
* go to File | Settings | PHP | Test Frameworks
* add new configuration type (PHPUnit Local)
  * Use composer autoloader
  * Path to script : local path to composer autoloader file (autoload.php)
### Create PHPUnit run configuration
* go to Run | Edit Configurations
* add new, using PHPUnit template
  * Test scope   : Directory
  * Directory    : local path to project "tests" directory
  * Interpreter  : PHP remote interpreter, added before
### Running tests
* fire Run | Run
* read