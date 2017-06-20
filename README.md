# Secret Management

Simple tool to manage and share secrets.

## Requirements

 * git (https://git-scm.com/)
 * docker (https://docs.docker.com/engine/installation)
 * docker-compose (https://docs.docker.com/compose)
 * composer (https://getcomposer.org)

## Install

    $ git clone https://github.com/egobude/secret.git
    $ composer install --no-dev

## Usage

### Build the containers    

    $ docker-compose build

### Start up your docker-compose file    

    $ docker-compose up -d   

You can reach your project under http://<YOUR_IP_ADRESS:8080

### Destroy the project

    $ docker-compose down -v
