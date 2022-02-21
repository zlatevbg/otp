#!/bin/bash

chown -R www-data:www-data .

# Wait for MySQL
while ! mysqladmin ping -h"mysql"; do sleep 1; done

set -e

# first arg is `-f` or `--some-option`
if [ "${1#-}" != "$1" ]; then
    set -- php-fpm "$@"
fi

exec "$@"
