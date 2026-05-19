#!/bin/sh

if [ ! -f /app/.env ]; then
    cp /app/.env.example /app/.env
fi

if [ -z "$(grep '^APP_SECRET=' /app/.env | cut -d '=' -f2)" ]; then
    SECRET=$(openssl rand -hex 32)
    sed -i "s/^APP_SECRET=.*/APP_SECRET=$SECRET/" /app/.env
fi

if [ -z "$(grep '^JWT_PASSPHRASE=' /app/.env | cut -d '=' -f2)" ]; then
    PASSPHRASE=$(openssl rand -hex 32)
    sed -i "s/^JWT_PASSPHRASE=.*/JWT_PASSPHRASE=$PASSPHRASE/" /app/.env
fi

php bin/console lexik:jwt:generate-keypair --skip-if-exists
php-fpm