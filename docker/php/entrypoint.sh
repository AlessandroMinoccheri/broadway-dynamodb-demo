#!/bin/sh
set -e

# install composer at run time to avoide dependency issue
cd /var/www/web
echo "[INFO] installing dependency...."
composer install

# first arg is `-f` or `--some-option`
if [ "${1#-}" != "$1" ]; then
	set -- php-fpm "$@"
fi

exec "$@"