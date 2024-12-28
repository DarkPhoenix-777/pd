#!/bin/sh
set -e

sleep 10

php bin/console doctrine:database:create --if-not-exists --no-interaction || true

mkdir -p src/Migrations

php bin/console doctrine:migrations:status --no-interaction || true
php bin/console doctrine:migrations:migrate --no-interaction || true

php bin/console cache:clear

chown -R www-data:www-data var

exec "$@" 