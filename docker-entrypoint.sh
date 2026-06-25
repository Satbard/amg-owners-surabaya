#!/bin/bash
set -e

# Run database migrations
php artisan migrate --force

# Execute the default PHP Docker entrypoint to start Apache
exec docker-php-entrypoint apache2-foreground
