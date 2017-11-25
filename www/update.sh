#!/bin/sh
composer install
php7.1 bin/console c:c
php7.1 bin/console doctrine:schema:create
php7.1 bin/console doctrine:fixtures:load -e=dev
php7.1 bin/console assets:install --symlink
php7.1 bin/console assetic:dump
