#!/usr/bin/env bash
set -e

echo "Starting Docker"

if ! docker info > /dev/null 2>&1; then
    open --hide --background -a Docker &&
        while ! docker system info >/dev/null 2>&1; do sleep 2; done
fi

echo "Starting the application ..."

# Start servers
./vendor/bin/sail up -d

# Start the queue workers
./vendor/bin/sail exec --detach laravel.test php artisan queue:work

echo "Application started!"
echo
echo "Now open your browser and go to http://localhost"
echo
echo "When you have finished don't forget to come back here and run"
echo "the stop.sh command."
