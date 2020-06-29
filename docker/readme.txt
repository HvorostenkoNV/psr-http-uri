requirements:
 - docker
 - docker compose

actions plan:
 - drag all project dependencies (PHPUnit includes)
 - get remote PHP server (no WEB server needs)
 - configure our IDE (PHPStorm) to use remote PHP server and run tests
 - run tests and be sure all tests path

dragging dependencies (using docker composer):
 - open CLI
 - go into project root, for example /home/current_user/projects/psr-http-uri
 - use docker composer container, running:
   $ docker run \
     --rm \
     --interactive \
     --tty \
     --volume $PWD:/app \
     composer install

getting remote PHP server:
 - open CLI
 - go into project docker directory, for example /home/current_user/projects/psr-http-uri/docker
 - edit .env, if it needs
 - up containers, running:
   $ docker-compose up -d
 - check them, running:
   $ docker container ls

PHPStorm configure (register remote PHP server SSH connection):
 - go to File | Settings | Tools | SSH Configurations -> add new
 - docker is on host machine case:
       Host         : 0.0.0.0
       Port         : ${parameter from .env file}
       User name    : root
       Password     : root
 - docker is on virtual machine with NAT network case:
       add ports rule on VM
           Host         : PHP server virtual host, for example 127.0.0.10
           Host port    : PHP server virtual SSH port, for example 33
           Guest port   : ${parameter from .env file}

       Host         : PHP server virtual host, added into VM ports rules
       Port         : PHP server virtual SSH port, added into VM ports rules
       User name    : root
       Password     : root

PHPStorm configure (add remote interpreter):
 - go to File | Settings | Languages & Frameworks | PHP -> CLI Interpreters
 - add new, using SSH connection, added before

PHPStorm configure (add PHPUnit run configuration):
 - go to Run | Edit Configurations
 - add new, using PHPUnit template
 - parameters:
       Test scope   : Directory
       Directory    : local path to project "tests" directory
       Interpreter  : PHP remote interpreter, added before

running tests:
 - fire Run | Run
 - read