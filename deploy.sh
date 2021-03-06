#!/bin/sh

if [ "$(whoami)" != "root" ]
  then echo "Please run as root"
  exit
fi

CONTAINER_NAME="ejas"

if [ ! -f "docker-compose.yml" ]; then
    echo "$0: Copying docker-compose.yml."
    cp docker-compose.yml.prod docker-compose.yml
fi

# TODO: copy .env, set parameters

if [ ! -f ".env" ]; then
    echo "$0: Copying .env."
    cp .env.dist .env
fi

if [ ! "$(docker ps -q -f name=$CONTAINER_NAME)" ]; then
  docker-compose up -d
fi

chmod 777 bin/console

docker exec -it $CONTAINER_NAME composer install
#docker exec -it $CONTAINER_NAME composer update

docker exec -it ejas_mysql mysql -u root -ptoor -h localhost -e "DROP DATABASE IF EXISTS ejas; CREATE DATABASE ejas CHARACTER SET utf8 COLLATE utf8_general_ci;"

docker exec -it $CONTAINER_NAME bin/console doctrine:migrations:migrate --no-interaction

docker exec -it ejas yarn install
yarn encore prod

docker exec -it $CONTAINER_NAME bin/console fos:elastica:populate

docker exec -it $CONTAINER_NAME bin/console cache:clear --no-warmup
docker exec -it $CONTAINER_NAME bin/console cache:warmup

chmod -R 777 var
chmod -R 777 public/uploads
