#!/bin/bash

cp .env-example .env

echo "Do you want to use the develop version of docker containers? y/n"
read develop

if [[ $develop = y ]]
then
    docker compose -f develop.compose.yml up -d --build
else
    docker compose up -d --build
fi

echo "Do you want to install and activate xdebug? y/n"
read xdebug

if [[ $xdebug = y ]]
then
    bash docker/configs/phpfpm/init-xdebug.sh
fi
