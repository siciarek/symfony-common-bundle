#!/usr/bin/env bash

RESET=0

# Zatrzymanie serwera mysql pracującego na localhost, w celu uwolnienia domyślnego portu
sudo service mysql stop

# Usuwanie kontenerów:
docker rm --force `docker ps --no-trunc -aq`

if [ "$1" == "--reset" ]
then
    # Usuwanie obrazów:
    docker rmi --force `docker images -q`

    rm -rvf docker
    git clone https://github.com/Laradock/laradock.git docker

    echo 'KONIEC RESETU'
fi


# Zatrzymanie na wypadek niechęcie podjęcia dalszych kroków
sleep 10

set -x

APACHE_DOCKERFILE=$PWD/docker/apache2/Dockerfile
APACHE_SITES=$PWD/docker/apache2/sites/*.conf

sed -i "s/public/web/g" $APACHE_DOCKERFILE
sed -i "s/public/web/g" $APACHE_SITES

cd docker

ls

ENV_FILE=.env

echo DB_HOST=0.0.0.0 > $ENV_FILE
echo REDIS_HOST=redis >> $ENV_FILE
echo QUEUE_HOST=beanstalkd >> $ENV_FILE
echo '' >> $ENV_FILE
cat env-example >> $ENV_FILE

PHP_VERSION=71
sed -i "s/PHP_VERSION=.*/PHP_VERSION=$PHP_VERSION/g" $ENV_FILE


if [ "$1" == "--reset" ]
then
    docker-compose --build up -d apache2 mysql
else
    docker-compose up -d apache2 mysql
fi

docker ps

docker-compose exec workspace bin/console doctrine:database:drop --force
docker-compose exec workspace bin/console doctrine:database:create
docker-compose exec workspace bin/console doctrine:schema:update --force
docker-compose exec workspace bin/console doctrine:schema:validate
docker-compose exec workspace bin/console doctrine:fixtures:load --no-interaction


