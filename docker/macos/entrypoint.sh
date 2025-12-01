set -e

mkdir -p /var/www/storage/framework/views
mkdir -p /var/www/storage/framework/cache
mkdir -p /var/www/storage/framework/sessions
mkdir -p /var/www/storage/logs

sudo chown -R laravel:laravel /var/www/storage
chmod -R 775 /var/www/storage

exec "$@"