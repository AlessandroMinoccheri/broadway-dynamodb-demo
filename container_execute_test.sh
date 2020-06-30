#!/bin/sh
echo "[WARNING] Make sure [php_fpm_broadway_demo] container is running"

docker exec  php_fpm_broadway_demo bash -c "cd /var/www/web;./runtests.sh"    