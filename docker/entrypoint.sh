#!/bin/sh
set -e

# Wait for database to be ready
echo "Waiting for database connection..."
max_attempts=30
attempt=0
DB_CONNECTION=${DB_CONNECTION:-mysql}
if [ "$DB_CONNECTION" = "mysql" ]; then
    until mysqladmin ping -h"${DB_HOST:-127.0.0.1}" -P"${DB_PORT:-3306}" -u"${DB_USERNAME:-root}" -p"${DB_PASSWORD:-}" --silent 2>/dev/null || [ $attempt -ge $max_attempts ]; do
        attempt=$((attempt + 1))
        echo "Database not ready (attempt $attempt/$max_attempts), waiting 5 seconds..."
        sleep 5
    done
else
    echo "SQLite detected, no wait needed."
fi

if [ $attempt -ge $max_attempts ]; then
    echo "WARNING: Database connection failed after $max_attempts attempts. Continuing anyway..."
else
    echo "Database connection established."
fi

# Run migrations
echo "Running database migrations..."
php artisan migrate --force

# Ensure storage link exists
echo "Ensuring storage link..."
php artisan storage:link --force 2>/dev/null || true

# Set proper permissions
chmod -R 777 storage bootstrap/cache

echo "Startup complete, starting services..."
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
