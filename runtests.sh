#!/bin/bash

clear
rm -rf var/cache/test/

./bin/console app:create-database --env=test

./bin/phpunit --stop-on-failure