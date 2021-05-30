#!/usr/bin/env bash
set -e

echo "Starting Docker"

if ! docker info > /dev/null 2>&1; then
    open --hide --background -a Docker &&
        while ! docker system info >/dev/null 2>&1; do sleep 2; done
fi

echo "Deploying application ..."

# Start servers
./vendor/bin/sail up -d

# Enter maintenance mode
(./vendor/bin/sail artisan down --render="errors::503") || true

# Install dependencies based on lock file
./vendor/bin/sail composer install --no-interaction --prefer-dist --optimize-autoloader
./vendor/bin/sail npm install

# Migrate database
./vendor/bin/sail artisan migrate --force --no-interaction

# Make sure the public disk is available
./vendor/bin/sail artisan storage:link

# Clear caches
./vendor/bin/sail artisan optimize:clear --no-interaction
if [ -f ./bootstrap/cache/services.php ]; then
    rm ./bootstrap/cache/*.php
fi

# Rebuild cache
./vendor/bin/sail artisan optimize --no-interaction
./vendor/bin/sail artisan view:cache --no-interaction
./vendor/bin/sail artisan event:cache --no-interaction
./vendor/bin/sail artisan livewire:discover

# Rebuild artifacts
./vendor/bin/sail npm run dev

# Fix permissions
chgrp -R staff .

# Exit maintenance mode
./vendor/bin/sail artisan up

# Shut down servers
./vendor/bin/sail down

echo "Application deployed!"
