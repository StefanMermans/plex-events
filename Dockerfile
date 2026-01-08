FROM composer:2.9.3 AS composer
WORKDIR /app/

COPY composer.json composer.lock /app/

RUN composer install \
    --no-dev \
    --no-interaction \
    --no-scripts \
    --prefer-dist \
    --optimize-autoloader

FROM node:24 AS node
WORKDIR /app/frontend/

COPY ./frontend/package.json ./frontend/package-lock.json /app/frontend/

RUN npm install

FROM node AS frontend
WORKDIR /app/frontend/

COPY ./frontend /app/frontend/

RUN npm run build

FROM php:8.4-fpm-alpine AS base

RUN apk add --no-cache \
    nginx \
    supervisor \
    icu-libs \
    libzip \
    icu-dev \
    libzip-dev \
    autoconf \
    g++ \
    make \
    && docker-php-ext-install intl pdo_mysql zip exif opcache \
    && pecl install redis \
    && docker-php-ext-enable redis \
    && apk del autoconf g++ make

FROM base AS app
WORKDIR /var/www/html/

ENV APP_ENV=prod

COPY --exclude=frontend . .
COPY --from=composer /app /var/www/html/
COPY --from=frontend /app/public/app/ /var/www/html/frontend/dist/
COPY ./docker/supervisord.conf /etc/supervisord.conf
COPY ./docker/nginx.conf /etc/nginx/http.d/default.conf.template
COPY ./docker/entrypoint.sh /entrypoint.sh

RUN chmod +x /entrypoint.sh

EXPOSE 80

CMD ["/entrypoint.sh"]
