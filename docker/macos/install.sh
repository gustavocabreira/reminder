#!/bin/sh

set -e

echo "Starting..."

export USER_ID=${USER_ID:-$(id -u)}
export USER_GROUP=${USER_GROUP:-$(id -g)}
export HOST_USER=${HOST_USER:-$(whoami)}

export APP_NAME='laravel'

OPTIONS=$(getopt -o n: --long app-name: -- "$@")
if [ $? -ne 0 ]; then
    echo "Incorrect options provided"
    exit 1
fi

eval set -- "$OPTIONS"

while true; do
    case "$1" in
        -n|--app-name) APP_NAME="$2"; shift 2 ;;
        --) shift; break ;;
        *) echo "Unknown option: $1"; exit 1 ;;
    esac
done

cp .env.example .env

sed -i '' "s|app_name|$APP_NAME|g" .env

./set_storage.sh

# docker compose stop
docker compose up -d --build

counter=0
while ! docker compose exec -T laravel test -f /var/www/artisan; do
  echo "Waiting for Laravel container to be ready: ${counter}s"
  sleep 5
  counter=$((counter + 5))
done
echo "Laravel container is ready after $counter seconds."

until docker compose logs mysql | grep -q "ready for connections"; do
    echo "Waiting Database setup...${counter}s"
    sleep 5
    counter=$((counter + 5))
done
echo "Database is ready to use after $counter seconds."

docker compose cp .env laravel:/var/www/
docker compose cp laravel:/var/www/.env .env
docker compose exec -t laravel composer install
docker compose exec -t laravel npm install
docker compose exec -t laravel php artisan key:generate
docker compose exec -it laravel php artisan migrate
docker compose exec -it laravel npm i chokidar
docker compose exec -it laravel php artisan storage:link
docker compose exec laravel npm run dev
docker compose restart laravel

echo "Started!"