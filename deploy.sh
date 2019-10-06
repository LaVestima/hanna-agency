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

if [ ! "$(docker ps -q -f name=$CONTAINER_NAME)" ]; then
  docker-compose up -d
fi

git pull origin master

docker exec -it $CONTAINER_NAME composer update

docker exec -it $CONTAINER_NAME bin/console doctrine:migrations:migrate --no-interaction

yarn encore prod

docker exec -it $CONTAINER_NAME bin/console cache:clear --no-warmup
docker exec -it $CONTAINER_NAME bin/console cache:warmup

chmod -R 777 var

