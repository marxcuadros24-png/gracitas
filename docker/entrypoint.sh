#!/bin/sh
set -e

cd /var/www/html

# 1. Asegurar archivo .env
if [ ! -f .env ]; then
    echo ">> Creando archivo .env desde .env.example..."
    cp .env.example .env
fi

# 2. Asegurar APP_KEY
if ! grep -q "^APP_KEY=base64:" .env 2>/dev/null; then
    echo ">> Generando APP_KEY para Laravel..."
    php artisan key:generate --force --no-interaction
fi

# 3. Directorios de almacenamiento y permisos
mkdir -p storage/framework/sessions \
         storage/framework/views \
         storage/framework/cache/data \
         storage/logs \
         bootstrap/cache

chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache

# 4. Enlace simbólico de storage
if [ ! -L public/storage ]; then
    php artisan storage:link --no-interaction || true
fi

# 5. Migraciones y Seeders automáticos opcionales
if [ "$AUTO_MIGRATE" = "true" ] || [ "$MIGRATE" = "true" ]; then
    echo ">> Ejecutando migraciones de base de datos..."
    php artisan migrate --force --no-interaction || echo "Advertencia: No se pudieron ejecutar migraciones automáticas."
fi

if [ "$AUTO_SEED" = "true" ] || [ "$SEED" = "true" ]; then
    echo ">> Sembrando datos de prueba iniciales (DatabaseSeeder)..."
    php artisan db:seed --force --no-interaction || true
fi

# 6. Optimización en producción
if [ "$APP_ENV" = "production" ]; then
    echo ">> Optimizando cache de Laravel para producción..."
    php artisan optimize || true
fi

echo ">> GraCitas iniciado correctamente. Ejecutando servidor..."
exec "$@"
