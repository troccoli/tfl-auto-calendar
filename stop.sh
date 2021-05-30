#!/usr/bin/env bash
set -e

echo "Starting Docker"

if ! docker info > /dev/null 2>&1; then
    exit 0
fi

echo "Stopping the application ..."

# Stop servers
./vendor/bin/sail down

echo "Application stopped!"
