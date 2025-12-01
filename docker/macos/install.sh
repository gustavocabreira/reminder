#!/bin/sh
set -e

echo "Starting..."

APP_NAME='laravel'

# parse flags
OPTIONS=$(getopt -o n: --long app-name: -- "$@") || { echo "Incorrect options"; exit 1; }
eval set -- "$OPTIONS"
while true; do
  case "$1" in
    -n|--app-name) APP_NAME="$2"; shift 2 ;;
    --) shift; break ;;
    *) echo "Unknown option: $1"; exit 1 ;;
  esac
done

# prepara .env local e injeta APP_NAME
cp -f .env.example .env

# compatibilidade sed (macOS vs GNU)
if sed --version >/dev/null 2>&1; then
  sed -i "s|app_name|$APP_NAME|g" .env
else
  sed -i '' "s|app_name|$APP_NAME|g" .env
fi

# sobe tudo (rebuilda a imagem da app caso mude USER_ID/GROUP_ID)
docker compose up -d --build

# espera container da app ter /var/www/artisan
counter=0
while ! docker compose exec -T laravel test -f /var/www/artisan; do
  echo "Waiting Laravel container: ${counter}s"
  sleep 5; counter=$((counter+5))
done
echo "Laravel container is ready after $counter s."

# espera MySQL ficar pronto
until docker compose logs mysql | grep -q "ready for connections"; do
  echo "Waiting Database setup... ${counter}s"
  sleep 5; counter=$((counter+5))
done
echo "Database is ready after $counter s."

# garante dirs e permissões DENTRO do container
docker compose exec --user root -T laravel sh -lc '
  set -e
  mkdir -p /var/www/storage/logs /var/www/bootstrap/cache
  chown -R $(id -u laravel):$(id -g laravel) /var/www/storage /var/www/bootstrap
  chmod -R ug+rwX /var/www/storage /var/www/bootstrap
'

# copia .env para dentro da app
docker compose cp .env laravel:/var/www/.env

# instala deps e inicializa
docker compose exec -T laravel composer update --no-interaction
docker compose exec -T laravel php artisan key:generate --force
docker compose exec -T laravel php artisan migrate --force
docker compose exec -T laravel php artisan storage:link || true

# npm é opcional; evite -it
docker compose exec -T laravel sh -lc 'command -v npm >/dev/null && npm install && npm i chokidar || true'

# reinicia para pegar tudo pronto
docker compose restart laravel

echo "Started!"
