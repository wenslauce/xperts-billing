#!/bin/sh
set -e

# Wait for MySQL to be ready
echo "Waiting for database connection..."
until php artisan db:monitor --timeout=60 2>/dev/null; do
    echo "Database not ready, waiting 5 seconds..."
    sleep 5
done

# Run migrations
echo "Running database migrations..."
php artisan migrate --force

# Ensure storage link exists
echo "Ensuring storage link..."
php artisan storage:link || true

# Set proper permissions
chmod -R 777 storage bootstrap/cache

echo "Startup complete, starting services..."
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf