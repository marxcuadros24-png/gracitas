# ==========================================
# ETAPA 1: Construcción de Assets Frontend
# ==========================================
FROM node:20-alpine AS stage-frontend
WORKDIR /app

# Instalar dependencias npm
COPY package*.json ./
RUN npm ci

# Copiar archivos fuente necesarios para Vite y Tailwind
COPY resources/ ./resources/
COPY public/ ./public/
COPY vite.config.js ./

# Compilar assets de producción
RUN npm run build


# ==========================================
# ETAPA 2: Dependencias PHP con Composer
# ==========================================
FROM composer:2 AS stage-composer
WORKDIR /app

COPY composer*.json ./
RUN composer install \
    --no-dev \
    --prefer-dist \
    --no-scripts \
    --no-autoloader \
    --no-interaction \
    --ignore-platform-reqs

COPY app/ ./app/
COPY bootstrap/ ./bootstrap/
COPY config/ ./config/
COPY database/ ./database/
COPY routes/ ./routes/

RUN composer dump-autoload --no-dev --optimize --no-scripts --no-interaction


# ==========================================
# ETAPA 3: Imagen Final de Producción
# ==========================================
FROM php:8.4-fpm-alpine AS stage-production

LABEL maintainer="GraCitas Team <soporte@gracitas.com>"
LABEL description="GraCitas - Sistema Web de Gestión y Reserva de Canchas Deportivas"

# Instalar dependencias del sistema y librerías C
RUN apk add --no-cache \
    nginx \
    supervisor \
    curl \
    bash \
    freetype-dev \
    libjpeg-turbo-dev \
    libpng-dev \
    libzip-dev \
    icu-dev \
    oniguruma-dev \
    sqlite-dev

# Configurar e instalar extensiones PHP requeridas por Laravel
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
        bcmath \
        gd \
        intl \
        mbstring \
        opcache \
        pcntl \
        pdo_mysql \
        pdo_sqlite \
        zip

# Copiar configuraciones de Nginx, PHP y Supervisor
COPY docker/nginx.conf /etc/nginx/nginx.conf
COPY docker/php.ini /usr/local/etc/php/conf.d/99-custom.ini
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY docker/entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

# Directorio de trabajo
WORKDIR /var/www/html

# Copiar código fuente de la aplicación
COPY . .

# Copiar vendor y assets compilados de las etapas anteriores
COPY --from=stage-composer /app/vendor ./vendor
COPY --from=stage-frontend /app/public/build ./public/build

# Crear directorios requeridos y asignar permisos a www-data
RUN mkdir -p storage/framework/sessions \
             storage/framework/views \
             storage/framework/cache/data \
             storage/logs \
             bootstrap/cache \
    && chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache \
    && php artisan package:discover --ansi

# Exponer puerto HTTP (80) y WebSocket Reverb (8080)
EXPOSE 80 8080

# Monitoreo de salud del contenedor
HEALTHCHECK --interval=30s --timeout=5s --start-period=10s --retries=3 \
    CMD curl -f http://localhost/health || exit 1

ENTRYPOINT ["/entrypoint.sh"]
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
