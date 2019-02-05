# README #

* Artist production.
* Version 0.0.0

### Environment details ###

#### Dependencies
    1. php >=7
    2. laravel >=5.7
    3. docker >=1.10
    4. npm
    5. mysql (postgres) 
    
#### Database configuration
    - DB: artist_production
    - User: artist_production
    - Pass: artist_production

### Preparing to work ###

#### Summary of docker set up.
   1. Install [docker](https://www.docker.com). Link for [ubuntu](https://docs.docker.com/engine/installation/linux/docker-ce/ubuntu) 
        - Install [docker-compose](https://docs.docker.com/compose/install/#install-compose) >=2.0 (Depending on the system)
   1. Configure docker-compose.yml 
   1. Run docker containers:
        - docker-compose up
   1. Configure .env file
   1. Run php composer from web container: 
        - docker exec -it artist_production_web_1 /bin/bash 
        - composer install
   1. Generate application key in command line of container:
        - php artisan key:generate
   1. Install npm dependencies: 
        - npm install
        
#### Summary of set up without docker.
   1. Run php composer:
        - composer install
   1. Copy env.example to .env
   1. Configure .env file
   1. Generate application key:
        - php artisan key:generate
   1. Run migrations:
        - php artisan migrate
   1. Install npm dependencies: 
        - npm install      
        
#### Deployment instructions.
   1. If files on server have some necessary changes run:
        - git stash
   1. Update server git repository:
        - git pull
   1. To install composer dependencies always run:
        - composer install (not update)
   1. Run db migrations:
        - php artisan migrate
   1. Apply stashed changes if necessary:
        - git stash apply    

### Contribution guidelines ###

* Writing tests
* Code review
* Other guidelines